<?php
/**
 * JSON data access and formatting helpers for files in /data.
 * Reads and writes only the allowlisted JSON files defined in constants.php.
 */

declare(strict_types=1);

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/components/ui.php';

/**
 * Resolves an allowlisted data filename to its project path.
 */
function ghtDataFilePath(string $fileName): string
{
    if (!in_array($fileName, GHT_DATA_FILES, true)) {
        throw new InvalidArgumentException('Unknown data file.');
    }

    return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $fileName;
}

/**
 * Reads a JSON file and returns its top-level list of records.
 *
 * @return array<int|string, mixed>
 */
function ghtReadJson(string $fileName): array
{
    $path = ghtDataFilePath($fileName);

    if (!is_file($path) || !is_readable($path)) {
        throw new RuntimeException('The requested data file is missing or unreadable.');
    }

    $contents = file_get_contents($path);

    if ($contents === false) {
        throw new RuntimeException('The requested data file could not be read.');
    }

    try {
        $records = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        throw new RuntimeException('The requested data file contains invalid JSON.', 0, $exception);
    }

    if (!is_array($records)) {
        throw new RuntimeException('The requested data file must contain a JSON array.');
    }

    return $records;
}

/**
 * Writes a complete list of records to an allowlisted JSON file.
 *
 * @param array<int|string, mixed> $records
 */
function ghtWriteJson(string $fileName, array $records): void
{
    $path = ghtDataFilePath($fileName);
    $temporaryPath = '';

    try {
        $contents = json_encode(
            $records,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        ) . PHP_EOL;
    } catch (JsonException $exception) {
        throw new RuntimeException('The data could not be encoded as JSON.', 0, $exception);
    }

    $temporaryPath = tempnam(dirname($path), '.ght-');

    if ($temporaryPath === false) {
        throw new RuntimeException('A temporary data file could not be created.');
    }

    try {
        $bytesWritten = file_put_contents($temporaryPath, $contents, LOCK_EX);

        if ($bytesWritten === false || $bytesWritten !== strlen($contents)) {
            throw new RuntimeException('The requested data file could not be fully written.');
        }

        if (!rename($temporaryPath, $path)) {
            throw new RuntimeException('The requested data file could not be safely replaced.');
        }
    } finally {
        if (is_file($temporaryPath)) {
            unlink($temporaryPath);
        }
    }
}

/**
 * Escapes a value for safe insertion into HTML.
 *
 * @param mixed $value
 */
function ghtEscape($value): string
{
    if (!is_scalar($value) && $value !== null) {
        throw new InvalidArgumentException('Only scalar values can be escaped for HTML.');
    }

    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Formats an integer amount in naira for display.
 */
function ghtFormatNaira(int $amount): string
{
    return '₦' . number_format($amount);
}

/**
 * Formats a valid YYYY-MM-DD date for the application interface.
 *
 * @param mixed $value
 */
function ghtFormatDate($value): string
{
    if (!is_string($value) || $value === '') {
        return 'Date not set';
    }

    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

    if ($date === false) {
        return 'Date not set';
    }

    $dateErrors = DateTimeImmutable::getLastErrors();

    if ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) {
        return 'Date not set';
    }

    return $date->format('j M Y');
}

/**
 * Formats a valid 24-hour HH:MM time for the event interface.
 *
 * @param mixed $value
 */
function ghtFormatTime($value): string
{
    if (!is_string($value) || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $value)) {
        return 'Time not set';
    }

    $time = DateTimeImmutable::createFromFormat('!H:i', $value);

    return $time === false ? 'Time not set' : $time->format('g:i A');
}

/**
 * Resolves the display and filter state for an event.
 *
 * @param array<string, mixed> $event
 */
function ghtGetEventStatus(array $event): string
{
    $exception = $event['exception'] ?? 'none';

    if ($exception === 'postponed' || $exception === 'cancelled') {
        return $exception;
    }

    $phase = $event['phase'] ?? 'pre_event';

    if ($phase === 'live') {
        return 'live';
    }

    if ($phase === 'post_event') {
        return 'completed';
    }

    $publication = $event['publication'] ?? 'draft';

    return in_array($publication, GHT_EVENT_PUBLICATIONS, true) ? $publication : 'draft';
}

/**
 * Provides a consistent label, token classes, and Font Awesome icon per event status.
 *
 * @return array{label: string, classes: string, icon: string}
 */
function ghtGetEventStatusPresentation(string $status): array
{
    $presentations = [
        'draft' => ['label' => 'Draft', 'classes' => 'bg-[#F3EDE5] text-[#6B675F]', 'icon' => 'fa-pen-to-square'],
        'published' => ['label' => 'Published', 'classes' => 'bg-[#EEF0EA] text-[#59634F]', 'icon' => 'fa-circle-check'],
        'live' => ['label' => 'Live', 'classes' => 'bg-[#F5E9E2] text-[#7E4A33]', 'icon' => 'fa-signal'],
        'completed' => ['label' => 'Completed', 'classes' => 'bg-[#F3EDE5] text-[#6B675F]', 'icon' => 'fa-check'],
        'postponed' => ['label' => 'Postponed', 'classes' => 'bg-[#FBF1DC] text-[#8F5F12]', 'icon' => 'fa-calendar-xmark'],
        'cancelled' => ['label' => 'Cancelled', 'classes' => 'bg-[#F8E6E6] text-[#B54747]', 'icon' => 'fa-ban'],
        'unpublished' => ['label' => 'Unpublished', 'classes' => 'bg-[#F3EDE5] text-[#6B675F]', 'icon' => 'fa-eye-slash'],
    ];

    return $presentations[$status] ?? $presentations['draft'];
}

/**
 * Starts a session only when the current request has not already started one.
 */
function ghtStartSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    if (!session_start()) {
        throw new RuntimeException('The application session could not be started.');
    }
}

/**
 * Returns a session-bound CSRF token for forms that write shared mock data.
 */
function ghtGetCsrfToken(): string
{
    ghtStartSession();

    if (!isset($_SESSION['ght_csrf_token']) || !is_string($_SESSION['ght_csrf_token'])) {
        $_SESSION['ght_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['ght_csrf_token'];
}

/**
 * Validates a submitted form token against the active session token.
 *
 * @param mixed $submittedToken
 */
function ghtValidateCsrfToken($submittedToken): bool
{
    $expectedToken = ghtGetCsrfToken();

    return is_string($submittedToken) && hash_equals($expectedToken, $submittedToken);
}

/**
 * Looks up an event record by its stable ID.
 *
 * @param array<int, mixed> $events
 * @return array{index: int, event: array<string, mixed>}|null
 */
function ghtFindEvent(array $events, string $eventId): ?array
{
    foreach ($events as $index => $event) {
        if (is_array($event) && isset($event['id']) && hash_equals((string) $event['id'], $eventId)) {
            return ['index' => $index, 'event' => $event];
        }
    }

    return null;
}

/**
 * Returns an HTML response for a Ghost page using the shared shell.
 *
 * @param array<string, mixed>|null $event
 */
function ghtRenderPage(string $title, string $activePage, ?array $event, string $content, string $script = ''): void
{
    $ghtPageTitle = $title;
    $ghtActivePage = $activePage;
    $ghtEvent = $event;
    $ghtPageContent = $content;
    $ghtPageScript = $script;

    require dirname(__DIR__) . '/includes/dashboard-shell.php';
}
