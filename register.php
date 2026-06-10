<?php


declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/User.php';

redirectIfLoggedIn();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userModel = new User();
    $result = $userModel->register(
        $_POST['username'] ?? '',
        $_POST['password'] ?? '',
        $_POST['confirm_password'] ?? ''
    );

    if ($result['success']) {
        $_SESSION['flash_success'] = $result['message'];
        header('Location: login.php');
        exit;
    }

    $error = $result['message'];
}

$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <h1 class="doom-hero-title">Forge Your Name</h1>
            <p class="text-doom-muted">Inscribe your identity into the grimoire.</p>
        </div>

        <div class="doom-card">
            <div class="doom-card-header">Register</div>
            <div class="doom-card-body">
                <?php if ($error): ?>
                    <div class="alert alert-doom-error mb-3"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="register.php" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               required autofocus minlength="3" maxlength="50"
                               pattern="[a-zA-Z0-9_]+"
                               autocomplete="username">
                        <div class="form-text">Letters, numbers, and underscores only.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               required minlength="6" autocomplete="new-password">
                        <div class="form-text">Minimum 6 characters.</div>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password"
                               name="confirm_password" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-doom w-100">Inscribe Account</button>
                </form>

                <p class="text-center text-doom-muted mt-4 mb-0">
                    Already bound? <a href="login.php" class="text-decoration-none" style="color: var(--doom-ember);">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
