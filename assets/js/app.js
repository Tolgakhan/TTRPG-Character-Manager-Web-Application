

(function () {
    'use strict';

    const container = document.getElementById('abilities-container');
    const addBtn = document.getElementById('add-ability-btn');

    if (!container || !addBtn) {
        return;
    }

    
    function createAbilityRow(name = '', rank = 0) {
        const row = document.createElement('div');
        row.className = 'ability-row row g-2 align-items-end mb-2';

        row.innerHTML = `
            <div class="col-md-7">
                <label class="form-label small">Ability / Skill</label>
                <input type="text" class="form-control ability-name"
                       name="ability_name[]" placeholder="e.g. Swordplay, Arcane Lore"
                       value="${escapeHtml(name)}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Rank (0–10)</label>
                <input type="number" class="form-control ability-rank"
                       name="ability_rank[]" min="0" max="10" value="${rank}" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-doom-danger w-100 remove-ability-btn"
                        title="Remove ability">&times;</button>
            </div>
        `;

        row.querySelector('.remove-ability-btn').addEventListener('click', function () {
            const rows = container.querySelectorAll('.ability-row');
            if (rows.length > 1) {
                row.remove();
            }
        });

        return row;
    }

   
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    addBtn.addEventListener('click', function () {
        container.appendChild(createAbilityRow());
    });

   
    container.querySelectorAll('.remove-ability-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const rows = container.querySelectorAll('.ability-row');
            if (rows.length > 1) {
                btn.closest('.ability-row').remove();
            }
        });
    });
})();
