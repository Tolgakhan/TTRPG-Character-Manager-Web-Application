<?php


declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}


function redirectIfLoggedIn(string $destination = 'index.php'): void
{
    if (isLoggedIn()) {
        header('Location: ' . $destination);
        exit;
    }
}


function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
}


function currentUserId(): int
{
    return (int) $_SESSION['user_id'];
}


function currentUsername(): string
{
    return htmlspecialchars($_SESSION['username'] ?? 'Wanderer', ENT_QUOTES, 'UTF-8');
}


function destroySession(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
