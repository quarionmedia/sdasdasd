<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .action-bar { margin: 20px 0; }
    .action-bar .button, .modal-content .button { background-color: #00aaff; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; cursor: pointer; border: none; font-family: sans-serif; font-size: 14px; }
    .admin-table { border-collapse: collapse; width: 100%; }
    .admin-table th, .admin-table td { border: 1px solid #555; padding: 10px; text-align: left; vertical-align: middle; }
    .admin-table th { background-color: #333; }
    .modal-overlay { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); }
    .modal-content { background-color: #2a2a2a; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 5px; position: relative; }
    .modal-close { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px;}
    
    /* Options Dropdown Stilleri (JS ile pozisyonlanacak) */
    .options-btn { background: #444; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    .options-dropdown { display: none; position: absolute; background: #333; border: 1px solid #555; border-radius: 4px; z-index: 10; min-width: 160px; padding: 5px 0; }
    .options-dropdown button, .options-dropdown a { display: block; padding: 8px 12px; text-decoration: none; color: #fff; background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: sans-serif; font-size: 14px; }
    .options-dropdown button:hover, .options-dropdown a:hover { background: #555; }
</style>

<main>
    <h1>Subtitle Manager for Link #<?php echo $link['id']; ?> <em style="color: #aaa;">(<?php echo htmlspecialchars($context['parent']['title'] ?? $context['parent']['name']); ?>)</em></h1>
    <p><a href="<?php echo $context['back_link']; ?>">&larr; Back to Links List</a></p>
    <hr>

    <div class="action-bar">
        <button type="button" class="button" id="addSubtitleBtn">Add Subtitle</button>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Language</th>
                <th>URL</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subtitles)): ?>
                <?php foreach ($subtitles as $subtitle): ?>
                    <tr>
                        <td><?php echo $subtitle['id']; ?></td>
                        <td><?php echo htmlspecialchars($subtitle['language']); ?></td>
                        <td><?php echo htmlspecialchars($subtitle['url']); ?></td>
                        <td><?php echo htmlspecialchars($subtitle['status']); ?></td>
                        <td class="content-actions">
                            <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Options ▼</button>
                            <div class="options-dropdown">
                                <button type="button" class="edit-subtitle-btn" data-subtitle-info='<?php echo htmlspecialchars(json_encode($subtitle)); ?>'>Edit</button>
                                <a href="/admin/manage_subtitles/delete/<?php echo $subtitle['id']; ?>/<?php echo $link['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #ff4d4d;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No subtitles found for this link.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="addSubtitleModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeAddModalBtn">&times;</span>
        <h2>Add Subtitle</h2>
        <hr>
        <form action="/admin/manage_subtitles/<?php echo $link['id']; ?>" method="POST">
            <div class="form-group">
                <label for="language">Language</label>
                <input type="text" id="language" name="language" required placeholder="e.g., English, Turkish">
            </div>
            <div class="form-group">
                <label for="type">Subtitle Type</label>
                <select id="type" name="type">
                    <option value="vtt">.vtt</option>
                    <option value="srt">.srt</option>
                </select>
            </div>
            <div class="form-group">
                <label for="url">Subtitle URL</label>
                <input type="text" id="url" name="url" required placeholder="Full URL of the subtitle file">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="publish">Publish</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <button type="submit" class="button">Add</button>
        </form>
    </div>
</div>

<div id="editSubtitleModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeEditModalBtn">&times;</span>
        <h2>Edit Subtitle</h2>
        <hr>
        <form id="editSubtitleForm" action="" method="POST">
             <div class="form-group">
                <label for="edit_language">Language</label>
                <input type="text" id="edit_language" name="language" required>
            </div>
            <div class="form-group">
                <label for="edit_type">Subtitle Type</label>
                <select id="edit_type" name="type">
                    <option value="vtt">.vtt</option>
                    <option value="srt">.srt</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit_url">Subtitle Url</label>
                <input type="text" id="edit_url" name="url" required>
            </div>
            <div class="form-group">
                <label for="edit_status">Status</label>
                <select id="edit_status" name="status">
                    <option value="publish">Publish</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <button type="submit" class="button">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function toggleDropdown(button, event) {
        event.stopPropagation();
        var dropdown = button.nextElementSibling;
        var wasOpen = dropdown.style.display === 'block';

        // Close all other dropdowns
        document.querySelectorAll('.options-dropdown').forEach(function(d) {
            d.style.display = 'none';
        });

        // If it was closed, open it and set its position
        if (!wasOpen) {
            var btnRect = button.getBoundingClientRect();
            dropdown.style.display = 'block';
            dropdown.style.position = 'fixed';
            dropdown.style.top = (btnRect.bottom + 2) + 'px';
            dropdown.style.left = (btnRect.right - dropdown.offsetWidth) + 'px';
        }
    }

    // Close dropdowns when clicking anywhere else
    window.addEventListener('click', function(event) {
        if (!event.target.matches('.options-btn')) {
            document.querySelectorAll('.options-dropdown').forEach(function(d) {
                d.style.display = 'none';
            });
        }
    });

    // Add Modal JS
    var addModal = document.getElementById("addSubtitleModal");
    var addBtn = document.getElementById("addSubtitleBtn");
    var closeAddBtn = document.getElementById("closeAddModalBtn");
    if(addBtn) { addBtn.onclick = function() { addModal.style.display = "block"; } }
    if(closeAddBtn) { closeAddBtn.onclick = function() { addModal.style.display = "none"; } }
    
    // Edit Modal JS
    var editModal = document.getElementById("editSubtitleModal");
    var closeEditBtn = document.getElementById("closeEditModalBtn");
    var editForm = document.getElementById("editSubtitleForm");

    document.querySelectorAll('.edit-subtitle-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var subtitleData = JSON.parse(this.getAttribute('data-subtitle-info'));
            editForm.action = '/admin/manage_subtitles/edit/' + subtitleData.id;
            document.getElementById('edit_language').value = subtitleData.language;
            document.getElementById('edit_type').value = subtitleData.type;
            document.getElementById('edit_url').value = subtitleData.url;
            document.getElementById('edit_status').value = subtitleData.status;
            editModal.style.display = "block";
        });
    });
    if(closeEditBtn) { closeEditBtn.onclick = function() { editModal.style.display = "none"; } }

    window.addEventListener('click', function(event) {
        if (event.target == addModal) { addModal.style.display = "none"; }
        if (event.target == editModal) { editModal.style.display = "none"; }
    });
</script>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>