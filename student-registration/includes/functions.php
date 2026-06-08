<?php
/**
 * Shared helper functions and bootstrap.
 */

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../classes/Student.php';
require_once __DIR__ . '/../classes/Validator.php';
require_once __DIR__ . '/../classes/FileUploader.php';

/**
 * Escape output to prevent XSS.
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Set a one-time flash message.
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Retrieve and clear the flash message.
 *
 * @return array{type:string,message:string}|null
 */
function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redirect to a path and stop execution.
 */
function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

/**
 * Build a list of country options.
 *
 * @return string[]
 */
function countries(): array
{
    return [
        'India', 'United States', 'United Kingdom', 'Canada', 'Australia',
        'Germany', 'France', 'Japan', 'China', 'Brazil', 'South Africa',
        'United Arab Emirates', 'Singapore', 'Other',
    ];
}
