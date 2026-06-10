<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Character.php';
require_once __DIR__ . '/includes/character_helpers.php';

requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $characterModel = new Character();
    $data = parseCharacterPost();
    $result = $characterModel->create(currentUserId(), $data);

    if ($result['success']) {
        $_SESSION['flash_success'] = $result['message'];
        header('Location: index.php');
        exit;
    }

    $error = $result['message'];
 
    $char = [
        'character_name' => $data['name'],
        'class'          => $data['class'],
        'strength'       => $data['strength'],
        'agility'        => $data['agility'],
        'presence'       => $data['presence'],
        'abilities'      => $data['abilities'],
    ];
}

$pageTitle = 'Forge Character';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <h1 class="doom-hero-title">Forge a Champion</h1>
            <p class="text-doom-muted">Inscribe a new soul into the grimoire.</p>
        </div>

        <div class="doom-card">
            <div class="doom-card-header">New Character Sheet</div>
            <div class="doom-card-body">
                <?php if ($error): ?>
                    <div class="alert alert-doom-error mb-3"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="character_create.php" novalidate>
                    <?php renderCharacterForm($char ?? null); ?>

                    <hr class="doom-divider">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="index.php" class="btn btn-doom-outline">Cancel</a>
                        <button type="submit" class="btn btn-doom">Inscribe Character</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
