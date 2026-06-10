<?php


declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Character.php';

requireLogin();

$characterModel = new Character();
$characters = $characterModel->getAllByUserId(currentUserId());


$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="doom-hero-title mb-1">Your Grimoire</h1>
        <p class="text-doom-muted mb-0">Greetings, <?= currentUsername() ?>. Manage your doomed champions below.</p>
    </div>
    <a href="character_create.php" class="btn btn-doom">+ Forge New Character</a>
</div>

<?php if ($flashSuccess): ?>
    <div class="alert alert-doom-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>

<?php if ($flashError): ?>
    <div class="alert alert-doom-error"><?= htmlspecialchars($flashError) ?></div>
<?php endif; ?>

<?php if (count($characters) === 0): ?>
    <div class="doom-card">
        <div class="doom-empty">
            <span class="empty-icon">&#9760;</span>
            <h4 style="font-family: 'Cinzel', serif; color: var(--doom-text);">The pages are empty</h4>
            <p>No characters have been forged yet. Begin your saga.</p>
            <a href="character_create.php" class="btn btn-doom mt-2">Forge Your First Character</a>
        </div>
    </div>
<?php else: ?>
    <div class="doom-card">
        <div class="doom-card-header">Character Sheets (<?= count($characters) ?>)</div>
        <div class="doom-card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover doom-table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Class</th>
                            <th>STR</th>
                            <th>AGI</th>
                            <th>PRE</th>
                            <th>Abilities</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($characters as $char): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--doom-gold);">
                                        <?= htmlspecialchars($char['character_name']) ?>
                                    </strong>
                                </td>
                                <td><?= htmlspecialchars($char['class']) ?></td>
                                <td><span class="stat-badge"><?= (int) $char['strength'] ?></span></td>
                                <td><span class="stat-badge"><?= (int) $char['agility'] ?></span></td>
                                <td><span class="stat-badge"><?= (int) $char['presence'] ?></span></td>
                                <td>
                                    <?php foreach ($char['abilities'] as $ability): ?>
                                        <span class="skill-pill">
                                            <?= htmlspecialchars($ability['name']) ?>
                                            <strong><?= (int) $ability['rank'] ?></strong>
                                        </span>
                                    <?php endforeach; ?>
                                </td>
                                <td class="text-doom-muted small">
                                    <?= date('M j, Y', strtotime($char['created_at'])) ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="character_edit.php?id=<?= (int) $char['id'] ?>"
                                       class="btn btn-sm btn-doom-outline me-1">Edit</a>
                                    <a href="character_delete.php?id=<?= (int) $char['id'] ?>"
                                       class="btn btn-sm btn-doom-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
