<?php
/**
 * api/state.php
 * Returns public-safe event phase and programme state for polling clients.
 * Includes: bootstrap.php. Reads: events.json and programme.json. Writes: nothing.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    http_response_code(405);
    echo json_encode(['error' => 'Use GET to request event state.']);
    exit;
}

$ghtEventId = filter_input(INPUT_GET, 'event', FILTER_UNSAFE_RAW);
$ghtEventId = is_string($ghtEventId) ? trim($ghtEventId) : '';

if ($ghtEventId === '') {
    http_response_code(400);
    echo json_encode(['error' => 'An event ID is required.']);
    exit;
}

try {
    $ghtEventLookup = ghtFindEvent(ghtReadJson('events.json'), $ghtEventId);

    if ($ghtEventLookup === null) {
        http_response_code(404);
        echo json_encode(['error' => 'Event not found.']);
        exit;
    }

    $ghtEvent = $ghtEventLookup['event'];
    $ghtProgramme = ghtReadJson('programme.json');
} catch (RuntimeException $exception) {
    error_log('Foliioo state endpoint failed: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Event state is temporarily unavailable.']);
    exit;
}

$ghtItems = array_values(array_filter(
    $ghtProgramme,
    static function ($item) use ($ghtEventId): bool {
        return is_array($item) && ($item['event_id'] ?? '') === $ghtEventId;
    }
));
usort($ghtItems, static fn(array $first, array $second): int => (int) ($first['order'] ?? 0) <=> (int) ($second['order'] ?? 0));
$ghtCurrentItem = null;

foreach ($ghtItems as $ghtItem) {
    if (($ghtItem['status'] ?? '') === 'in_progress') {
        $ghtCurrentItem = [
            'id' => (string) ($ghtItem['id'] ?? ''),
            'title' => (string) ($ghtItem['title'] ?? ''),
            'description' => (string) ($ghtItem['description'] ?? ''),
            'time' => (string) ($ghtItem['time'] ?? ''),
            'status' => 'in_progress',
        ];
        break;
    }
}

echo json_encode([
    'event_id' => (string) ($ghtEvent['id'] ?? ''),
    'publication' => (string) ($ghtEvent['publication'] ?? 'draft'),
    'phase' => (string) ($ghtEvent['phase'] ?? 'pre_event'),
    'exception' => (string) ($ghtEvent['exception'] ?? 'none'),
    'programme_paused' => (bool) ($ghtEvent['programme_paused'] ?? false),
    'programme_status' => $ghtCurrentItem['status'] ?? 'upcoming',
    'current_item' => $ghtCurrentItem,
    'programme' => array_map(static function (array $item): array {
        return [
            'id' => (string) ($item['id'] ?? ''),
            'order' => (int) ($item['order'] ?? 0),
            'time' => (string) ($item['time'] ?? ''),
            'title' => (string) ($item['title'] ?? ''),
            'description' => (string) ($item['description'] ?? ''),
            'status' => (string) ($item['status'] ?? 'upcoming'),
        ];
    }, $ghtItems),
], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
