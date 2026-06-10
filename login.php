<?php


declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/User.php';

redirectIfLoggedIn();

$error   = '';
$success = '';


if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userModel = new User();
    $result = $userModel->login(
        $_POST['username'] ?? '',
        $_POST['password'] ?? ''
    );

    if ($result['success']) {
        header('Location: index.php');
        exit;
    }

    $error = $result['message'];
}

$pageTitle = 'Login';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <h1 class="doom-hero-title">Enter the Grimoire</h1>
            <p class="text-doom-muted">Summon your characters from the void.</p>
        </div>

        <div class="doom-card">
            <div class="doom-card-header">Login</div>
            <div class="doom-card-body">
                <?php if ($success): ?>
                    <div class="alert alert-doom-success mb-3"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-doom-error mb-3"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               required autofocus autocomplete="username">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               required autocomplete="current-password">
                    </div>

                    <button type="submit" class="btn btn-doom w-100">Descend</button>
                </form>

                <p class="text-center text-doom-muted mt-4 mb-0">
                    No account? <a href="register.php" class="text-decoration-none" style="color: var(--doom-ember);">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
