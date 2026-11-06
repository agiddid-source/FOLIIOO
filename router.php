<?php
/**
 * Foliioo Built-in Development Server Router
 * Provides clean URL routing (/e/{event}, /gallery/{event}, /checkout/{event})
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

// Let static files (CSS, JS, images, fonts) be served directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Clean Event Path: /e/{event} or /event/{event}
if (preg_match('#^/e(?:vent)?/([^/]+)#', $uri, $matches)) {
    $_GET['event'] = $matches[1];
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/event.php';
    exit;
}

// Clean Gallery Path: /gallery/{event}
if (preg_match('#^/gallery/([^/]+)#', $uri, $matches)) {
    $_GET['event'] = $matches[1];
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/gallery.php';
    exit;
}

// Clean Checkout Path: /checkout/{event}
if (preg_match('#^/checkout/([^/]+)#', $uri, $matches)) {
    $_GET['event'] = $matches[1];
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/checkout.php';
    exit;
}

// Clean PHP file alias (e.g., /dashboard, /pricing, /login)
$phpFile = __DIR__ . $uri . '.php';
if (file_exists($phpFile)) {
    require $phpFile;
    exit;
}

// Root page
if ($uri === '/' || $uri === '/index') {
    require __DIR__ . '/index.php';
    exit;
}

return false;
