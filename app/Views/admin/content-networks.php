<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .section-list {
        list-style: none;
        padding: 0;
        max-width: 800px;
    }
    .section-item {
        background-color: #2a2a2a;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #444;
    }
    .drag-handle {
        cursor: move;
        color: #888;
    }
    .section-title {
        flex-grow: 1;
        font-weight: bold;
    }
    .toggle-switch {
        width: 40px;
        height: 20px;
        background: #c0392b;
        border-radius: 10px;
        position: relative;
        cursor: pointer;
    }
    .toggle-switch.toggled {
        background: #27ae60;
    }
    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        background: #fff;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        transition: left 0.2s;
    }
    .toggle-switch.toggled::after {
        left: 22px;
    }
    .save-button {
        padding: 10px 20px;
        background-color: #42ca1a;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }
</style>

<main>
    <h1>Content Networks</h1>
    <p>Manage the sections that appear on your homepage. Drag and drop to reorder.</p>
    <hr style="border-color: #374151; margin: 20px 0;">

    <form action="/admin/content-networks" method="POST" id="sectionsForm">
        <ul id="sortable-sections" class="section-list">
            <?php foreach ($sections as $index => $section): ?>
                <li class="section-item" data-id="<?php echo $section['id']; ?>">
                    <span class="drag-handle">☰</span>
                    <span class="section-title"><?php echo htmlspecialchars($section['section_title']); ?></span>
                    <div class="toggle-switch <?php echo $section['is_active'] ? 'toggled' : ''; ?>"></div>
                    
                    <input type="hidden" name="sections[<?php echo $section['id']; ?>][is_active]" value="<?php echo $section['is_active']; ?>">
                    <input type="hidden" name="sections[<?php echo $section['id']; ?>][display_order]" value="<?php echo $section['display_order']; ?>">
                </li>
            <?php endforeach; ?>
        </ul>

        <button type="submit" class="save-button">Save Changes</button>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sortableList = document.getElementById('sortable-sections');
    new Sortable(sortableList, {
        animation: 150,
        handle: '.drag-handle'
    });

    // Toggle switch'lere tıklama olayı ekle
    document.querySelectorAll('.toggle-switch').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            this.classList.toggle('toggled');
            var isActiveInput = this.parentElement.querySelector('input[name*="[is_active]"]');
            isActiveInput.value = this.classList.contains('toggled') ? '1' : '0';
        });
    });

    // Form gönderilmeden önce sıralamayı güncelle
    var form = document.getElementById('sectionsForm');
    form.addEventListener('submit', function() {
        var items = sortableList.querySelectorAll('.section-item');
        items.forEach(function(item, index) {
            var orderInput = item.querySelector('input[name*="[display_order]"]');
            orderInput.value = index + 1;
        });
    });
});
</script>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>