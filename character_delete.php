<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Character.php';

requireLogin();

$characterId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($characterId <= 0) {
    $_SESSION['flash_error'] = 'Invalid character ID.';
    header('Location: index.php');
    exit;
}

$characterModel = new Character();
$char = $characterModel->getByIdAndUserId($characterId, currentUserId());

if ($char === null) {
    $_SESSION['flash_error'] = 'Character not found or access denied.';
    header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $result = $characterModel->delete($characterId, currentUserId());

    if ($result['success']) {
        $_SESSION['flash_success'] = $result['message'];
    } else {
        $_SESSION['flash_error'] = $result['message'];
    }

    header('Location: index.php');
    exit;
}

$pageTitle = 'Delete Character';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <h1 class="doom-hero-title" style="color: var(--doom-blood-bright);">Consign to Oblivion</h1>
            <p class="text-doom-muted">This action cannot be undone.</p>
        </div>

        <div class="doom-card">
            <div class="doom-card-header" style="background: linear-gradient(90deg, #4a1010 0%, #2a1018 100%);">
                Confirm Deletion
            </div>
            <div class="doom-card-body text-center">
                <p class="mb-2">You are about to permanently erase:</p>
                <h4 style="color: var(--doom-gold); font-family: 'Cinzel', serif;">
                    <?= htmlspecialchars($char['character_name']) ?>
                </h4>
                <p class="text-doom-muted mb-1"><?= htmlspecialchars($char['class']) ?></p>

                <div class="my-3">
                    <span class="stat-badge me-2">STR <?= (int) $char['strength'] ?></span>
                    <span class="stat-badge me-2">AGI <?= (int) $char['agility'] ?></span>
                    <span class="stat-badge">PRE <?= (int) $char['presence'] ?></span>
                </div>

                <form method="POST" action="character_delete.php?id=<?= $characterId ?>">
                    <input type="hidden" name="id" value="<?= $characterId ?>">
                    <input type="hidden" name="confirm_delete" value="1">

                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <a href="index.php" class="btn btn-doom-outline">Spare This Soul</a>
                        <button type="submit" class="btn btn-doom">Destroy Forever</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
