<?php
/**
 * User Model
 *
 * Handles registration, authentication, and username lookups.
 * Passwords are hashed with password_hash() and verified with password_verify().
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Register a new user account.
     *
     * @return array{success: bool, message: string}
     */
    public function register(string $username, string $password, string $confirmPassword): array
    {
        $username = trim($username);

        if ($username === '' || strlen($username) < 3) {
            return ['success' => false, 'message' => 'Username must be at least 3 characters.'];
        }

        if (strlen($username) > 50) {
            return ['success' => false, 'message' => 'Username cannot exceed 50 characters.'];
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return ['success' => false, 'message' => 'Username may only contain letters, numbers, and underscores.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        if ($this->usernameExists($username)) {
            return ['success' => false, 'message' => 'That username is already taken.'];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)'
        );
        $stmt->execute([
            ':username'      => $username,
            ':password_hash' => $hash,
        ]);

        return ['success' => true, 'message' => 'Account forged in the abyss. You may now log in.'];
    }

    /**
     * Attempt to authenticate a user and populate the session on success.
     *
     * @return array{success: bool, message: string}
     */
    public function login(string $username, string $password): array
    {
        $username = trim($username);

        $stmt = $this->db->prepare(
            'SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }

        // Store authentication state in PHP session (not cookies)
        $_SESSION['user_id']   = (int) $user['id'];
        $_SESSION['username']  = $user['username'];

        return ['success' => true, 'message' => 'Welcome back, ' . htmlspecialchars($user['username']) . '.'];
    }

    /**
     * Check whether a username is already registered.
     */
    public function usernameExists(string $username): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $stmt->execute([':username' => trim($username)]);

        return (int) $stmt->fetchColumn() > 0;
    }
}
