<?php
/**
 * Router for the PHP built-in server (used for local/preview runs only).
 *
 * Serves existing static files (CSS, JS, images, uploads) as-is and lets
 * PHP handle .php routes. On Apache/MySQL this file is unused.
 */

declare(strict_types=1);

$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Default document.
if ($uri === '/' || $uri === '') {
    require __DIR__ . '/index.php';
    return true;
}

// Serve real static files directly.
if ($uri !== '/' && is_file($file)) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext !== 'php') {
        return false; // let the built-in server handle static content
    }
    require $file;
    return true;
}

// Unknown path -> 404.
http_response_code(404);
echo '404 Not Found';
return true;
