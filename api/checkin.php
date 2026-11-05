<?php
/**
 * api/checkin.php
 * Validates a pass token or guest record and records one guest check-in.
 * Includes: bootstrap.php. Reads and writes: events.json, guests.json.
 * Responses are JSON. Check-ins are mock local data, not a production ticket scan.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

/**
 * Sends a JSON response and ends this endpoint request.
 *
 * @param array<string, mixed> $payload
 */
function ghtCheckinRespond(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    ghtCheckinRespond(405, ['ok' => false, 'message' => 'Use POST to check a guest in.']);
}

$ghtEventId = isset($_POST['event_id']) && is_string($_POST['event_id']) ? trim($_POST['event_id']) : '';
$ghtToken = $_POST['csrf_token'] ?? '';
$ghtPassToken = isset($_POST['pass_token']) && is_string($_POST['pass_token'])
    ? strtoupper(trim($_POST['pass_token']))
    : '';
$ghtGuestId = isset($_POST['guest_id']) && is_string($_POST['guest_id']) ? trim($_POST['guest_id']) : '';

if (!ghtValidateCsrfToken($ghtToken)) {
    ghtCheckinRespond(403, ['ok' => false, 'message' => 'Your session expired. Reload the page and try again.']);
}

if ($ghtEventId === '' || ($ghtGuestId === '' && $ghtPassToken === '')) {
    ghtCheckinRespond(422, ['ok' => false, 'message' => 'Enter a pass code or select a guest first.']);
}

try {
    $ghtEvents = ghtReadJson('events.json');
    $ghtEventLookup = ghtFindEvent($ghtEvents, $ghtEventId);

    if ($ghtEventLookup === null) {
        ghtCheckinRespond(404, ['ok' => false, 'message' => 'Event not found. Return to the dashboard and choose an event.']);
    }

    $ghtGuests = ghtReadJson('guests.json');
} catch (RuntimeException $exception) {
    error_log('Foliioo check-in data load failed: ' . $exception->getMessage());
    ghtCheckinRespond(500, ['ok' => false, 'message' => 'Guest records could not be loaded. Please try again.']);
}

$ghtGuestIndex = null;
foreach ($ghtGuests as $ghtIndex => $ghtGuest) {
    if (!is_array($ghtGuest) || ($ghtGuest['event_id'] ?? '') !== $ghtEventId) {
        continue;
    }

    $ghtMatchesId = $ghtGuestId !== '' && ($ghtGuest['id'] ?? '') === $ghtGuestId;
    $ghtMatchesToken = $ghtPassToken !== ''
        && strtoupper((string) ($ghtGuest['pass_token'] ?? '')) === $ghtPassToken;

    if ($ghtMatchesId || $ghtMatchesToken) {
        $ghtGuestIndex = $ghtIndex;
        break;
    }
}

if ($ghtGuestIndex === null) {
    ghtCheckinRespond(200, [
        'ok' => true,
        'result' => 'invalid',
        'message' => 'Invalid pass. Check the code or search for the guest by name.',
    ]);
}

$ghtGuest = $ghtGuests[$ghtGuestIndex];

if (($ghtGuest['response'] ?? '') !== 'attending') {
    ghtCheckinRespond(200, [
        'ok' => true,
        'result' => 'invalid',
        'message' => 'This guest has not confirmed attendance and cannot be checked in.',
    ]);
}

if ((bool) ($ghtGuest['checked_in'] ?? false)) {
    ghtCheckinRespond(200, [
        'ok' => true,
        'result' => 'already_used',
        'message' => 'Already checked in.',
        'guest' => [
            'name' => (string) ($ghtGuest['name'] ?? 'Guest'),
            'checked_in_at' => (string) ($ghtGuest['checked_in_at'] ?? ''),
        ],
    ]);
}

$ghtTimestamp = date(DATE_ATOM);
$ghtGuests[$ghtGuestIndex]['checked_in'] = true;
$ghtGuests[$ghtGuestIndex]['checked_in_at'] = $ghtTimestamp;

try {
    ghtWriteJson('guests.json', $ghtGuests);
} catch (RuntimeException $exception) {
    error_log('Foliioo check-in record write failed: ' . $exception->getMessage());
    ghtCheckinRespond(500, ['ok' => false, 'message' => 'Check-in could not be saved. The guest record is unchanged.']);
}

ghtCheckinRespond(200, [
    'ok' => true,
    'result' => 'valid',
    'message' => 'Valid pass. Guest checked in.',
    'guest' => [
        'name' => (string) ($ghtGuests[$ghtGuestIndex]['name'] ?? 'Guest'),
        'checked_in_at' => $ghtTimestamp,
    ],
]);
