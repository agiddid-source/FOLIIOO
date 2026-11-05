<?php
/**
 * Shared bootstrap for Ghost-owned multi-page PHP entry points.
 * Loads constants and JSON helpers; it does not read or write data by itself.
 */

declare(strict_types=1);

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/data.php';
