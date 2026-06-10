<?php


declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Character.php';
require_once __DIR__ . '/includes/character_helpers.php';

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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = parseCharacterPost();
    $result = $characterModel->update($characterId, currentUserId(), $data);

    if ($result['success']) {
        $_SESSION['flash_success'] = $result['message'];
        header('Location: index.php');
        exit;
    }

    $error = $result['message'];
    
    $char = array_merge($char, [
        'character_name' => $data['name'],
        'class'          => $data['class'],
        'strength'       => $data['strength'],
        'agility'        => $data['agility'],
        'presence'       => $data['presence'],
        'abilities'      => $data['abilities'],
    ]);
}

$pageTitle = 'Edit Character';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <h1 class="doom-hero-title">Rewrite Fate</h1>
            <p class="text-doom-muted">
                Editing: <strong style="color: var(--doom-gold);"><?= htmlspecialchars($char['character_name']) ?></strong>
            </p>
        </div>

        <div class="doom-card">
            <div class="doom-card-header">Edit Character Sheet</div>
            <div class="doom-card-body">
                <?php if ($error): ?>
                    <div class="alert alert-doom-error mb-3"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="character_edit.php?id=<?= $characterId ?>" novalidate>
                    <input type="hidden" name="id" value="<?= $characterId ?>">
                    <?php renderCharacterForm($char); ?>

                    <hr class="doom-divider">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="index.php" class="btn btn-doom-outline">Cancel</a>
                        <button type="submit" class="btn btn-doom">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
