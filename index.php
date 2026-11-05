<?php
/**
 * index.php
 * Sends the app root to the Dashboard multi-page entry point.
 * Includes: nothing. Reads and writes: nothing. Animations: none.
 */

declare(strict_types=1);

$ghtRequestedEventId = filter_input(INPUT_GET, 'event', FILTER_UNSAFE_RAW);
$ghtEventQuery = is_string($ghtRequestedEventId) && trim($ghtRequestedEventId) !== ''
    ? '?event=' . rawurlencode(trim($ghtRequestedEventId))
    : '';

header('Location: dashboard.php' . $ghtEventQuery, true, 302);
exit;
