<?php

declare(strict_types=1);

/**
 * 
 *
 * @return array{name: string, class: string, strength: int, agility: int, presence: int, abilities: array}
 */
function parseCharacterPost(): array
{
    $abilities = [];
    $names = $_POST['ability_name'] ?? [];
    $ranks = $_POST['ability_rank'] ?? [];

    if (is_array($names)) {
        foreach ($names as $index => $name) {
            $abilities[] = [
                'name' => (string) $name,
                'rank' => $ranks[$index] ?? 0,
            ];
        }
    }

    return [
        'name'      => trim($_POST['character_name'] ?? ''),
        'class'     => trim($_POST['class'] ?? ''),
        'strength'  => (int) ($_POST['strength'] ?? 10),
        'agility'   => (int) ($_POST['agility'] ?? 10),
        'presence'  => (int) ($_POST['presence'] ?? 10),
        'abilities' => $abilities,
    ];
}

/**
 * 
 *
 * @param array<string, mixed>|null $char 
 */
function renderCharacterForm(?array $char = null): void
{
    $name     = htmlspecialchars($char['character_name'] ?? '', ENT_QUOTES, 'UTF-8');
    $class    = htmlspecialchars($char['class'] ?? '', ENT_QUOTES, 'UTF-8');
    $strength = (int) ($char['strength'] ?? 10);
    $agility  = (int) ($char['agility'] ?? 10);
    $presence = (int) ($char['presence'] ?? 10);
    $abilities = $char['abilities'] ?? [['name' => '', 'rank' => 0]];
    ?>
    <div class="row g-4">
        
        <div class="col-md-6">
            <label for="character_name" class="form-label">Character Name</label>
            <input type="text" class="form-control" id="character_name" name="character_name"
                   value="<?= $name ?>" placeholder="Aldric the Damned" required maxlength="100">
        </div>
        <div class="col-md-6">
            <label for="class" class="form-label">Class / Archetype</label>
            <input type="text" class="form-control" id="class" name="class"
                   value="<?= $class ?>" placeholder="Death Knight, Warlock..." required maxlength="80">
        </div>
    </div>

    <hr class="doom-divider">

   
    <h5 class="mb-3" style="color: var(--doom-gold); font-family: 'Cinzel', serif;">Core Attributes</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="strength" class="form-label">Strength</label>
            <input type="number" class="form-control" id="strength" name="strength"
                   value="<?= $strength ?>" min="1" max="20" required>
            <div class="form-text">Physical might and raw power.</div>
        </div>
        <div class="col-md-4">
            <label for="agility" class="form-label">Agility</label>
            <input type="number" class="form-control" id="agility" name="agility"
                   value="<?= $agility ?>" min="1" max="20" required>
            <div class="form-text">Speed, reflexes, and finesse.</div>
        </div>
        <div class="col-md-4">
            <label for="presence" class="form-label">Presence</label>
            <input type="number" class="form-control" id="presence" name="presence"
                   value="<?= $presence ?>" min="1" max="20" required>
            <div class="form-text">Charisma, willpower, and aura.</div>
        </div>
    </div>

    <hr class="doom-divider">

    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0" style="color: var(--doom-gold); font-family: 'Cinzel', serif;">Abilities &amp; Skills</h5>
        <button type="button" class="btn btn-sm btn-doom-outline" id="add-ability-btn">+ Add Skill</button>
    </div>

    <div id="abilities-container">
        <?php foreach ($abilities as $ability): ?>
            <div class="ability-row row g-2 align-items-end mb-2">
                <div class="col-md-7">
                    <label class="form-label small">Ability / Skill</label>
                    <input type="text" class="form-control ability-name" name="ability_name[]"
                           placeholder="e.g. Swordplay, Arcane Lore"
                           value="<?= htmlspecialchars($ability['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Rank (0–10)</label>
                    <input type="number" class="form-control ability-rank" name="ability_rank[]"
                           min="0" max="10"
                           value="<?= (int) ($ability['rank'] ?? 0) ?>" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-doom-danger w-100 remove-ability-btn"
                            title="Remove ability">&times;</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
