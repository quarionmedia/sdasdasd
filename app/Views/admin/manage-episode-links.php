<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .action-bar { margin: 20px 0; }
    .action-bar .button, .modal-content .button {
        background-color: #00aaff;
        color: #fff;
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 4px;
        cursor: pointer;
        border: none;
        font-family: sans-serif;
        font-size: 14px;
    }
    .admin-table {
        border-collapse: collapse;
        width: 100%;
    }
    .admin-table th, .admin-table td {
        border: 1px solid #555;
        padding: 10px;
        text-align: left;
        vertical-align: middle;
    }
    .admin-table th {
        background-color: #333;
    }
    /* Options Dropdown Stilleri (JS ile pozisyonlanacak) */
    .options-btn { background: #444; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    .options-dropdown {
        display: none;
        position: absolute; 
        background: #333;
        border: 1px solid #555;
        border-radius: 4px;
        z-index: 10;
        min-width: 160px;
        padding: 5px 0;
    }
    .options-dropdown a, .options-dropdown button { display: block; padding: 8px 12px; text-decoration: none; color: #fff; background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: sans-serif; font-size: 14px; }
    .options-dropdown a:hover, .options-dropdown button:hover { background: #555; }

    /* Modal (Popup) Stilleri */
    .modal-overlay { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); }
    .modal-content { background-color: #2a2a2a; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 700px; border-radius: 5px; position: relative; }
    .modal-close { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px;}
    .form-group.full-width { grid-column: 1 / -1; }
</style>

<main>
    <h1>Manage Links for: <em style="color: #ccc;"><?php echo htmlspecialchars($episode['name']); ?></em></h1>
    <p><a href="/admin/manage-episodes/<?php echo $season['id']; ?>">&larr; Back to Episodes List</a></p>
    <hr>

    <div class="action-bar">
        <button type="button" class="button" id="addLinkBtn">Add Stream Link</button>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Label</th>
                <th>Quality</th>
                <th>Size</th>
                <th>Source</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($links)): ?>
                <?php foreach ($links as $link): ?>
                    <tr>
                        <td><?php echo $link['id']; ?></td>
                        <td><?php echo htmlspecialchars($link['label'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($link['quality'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($link['size'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($link['source']); ?></td>
                        <td><?php echo htmlspecialchars($link['link_type']); ?></td>
                        <td class="content-actions">
                             <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Options ▼</button>
                             <div class="options-dropdown">
                                <button type="button" class="edit-link-btn" data-link-info='<?php echo htmlspecialchars(json_encode($link)); ?>'>Edit Link</button>
                                <a href="/admin/manage_subtitles/<?php echo $link['id']; ?>">Manage Subtitle</a>
                                <a href="/admin/manage-episode-links/delete/<?php echo $link['id']; ?>/<?php echo $episode['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #ff4d4d;">Delete</a>
                             </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No video links found for this episode.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="addLinkModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeAddModalBtn">&times;</span>
        <h2>Add Stream Link (<?php echo htmlspecialchars($episode['name']); ?>)</h2>
        <hr>
        <form action="/admin/manage-episode-links/<?php echo $episode['id']; ?>" method="POST">
            <div class="form-grid">
                <div class="form-group"><label for="label">Label</label><input type="text" id="label" name="label" placeholder="e.g., Server #1, Google Drive"></div>
                <div class="form-group"><label for="quality">Quality</label><input type="text" id="quality" name="quality" placeholder="e.g., 1080p, 720p"></div>
                <div class="form-group"><label for="size">Size</label><input type="text" id="size" name="size" placeholder="e.g., 1.0GB"></div>
                <div class="form-group">
                    <label for="source">Source</label>
                    <select id="source" name="source">
                        <option value="Youtube">Youtube</option><option value="Vimeo">Vimeo</option><option value="Dailymotion">Dailymotion</option><option value="Mp4_From_Url">Mp4 From Url</option><option value="Mkv_From_Url">Mkv From Url</option><option value="M3u8_From_Url">M3u8 From Url</option><option value="Dash_From_Url">Dash From Url</option><option value="Embed_Url">Embed Url</option><option value="DoodStream">DoodStream</option><option value="Dropbox">Dropbox</option><option value="Facebook">Facebook</option><option value="Fembed">Fembed</option><option value="GogoAnime">GogoAnime</option><option value="GoogleDrive">GoogleDrive</option><option value="MixDrop">MixDrop</option><option value="OK.ru">OK.ru</option><option value="Onedrive">Onedrive</option><option value="Streamtape">Streamtape</option><option value="Streamwish">Streamwish</option><option value="Torrent">Torrent</option><option value="Twitter">Twitter</option><option value="VK">VK</option><option value="Yandex">Yandex</option>
                    </select>
                </div>
                <div class="form-group full-width"><label for="url">URL</label><input type="text" id="url" name="url" required></div>
                <div class="form-group"><label for="link_type">Type</label><select id="link_type" name="link_type"><option value="stream">Stream</option><option value="download">Download</option></select></div>
                <div class="form-group"><label for="status">Status</label><select id="status" name="status"><option value="publish">Publish</option><option value="draft">Draft</option></select></div>
            </div>
            <hr>
            <button type="submit" class="button">Add Link</button>
        </form>
    </div>
</div>

<div id="editLinkModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeEditModalBtn">&times;</span>
        <h2>Edit Stream Link</h2>
        <hr>
        <form id="editLinkForm" action="" method="POST">
            <div class="form-grid">
                 <div class="form-group"><label for="edit_label">Label</label><input type="text" id="edit_label" name="label"></div>
                <div class="form-group"><label for="edit_quality">Quality</label><input type="text" id="edit_quality" name="quality"></div>
                <div class="form-group"><label for="edit_size">Size</label><input type="text" id="edit_size" name="size"></div>
                <div class="form-group">
                    <label for="edit_source">Source</label>
                    <select id="edit_source" name="source">
                         <option value="Youtube">Youtube</option><option value="Vimeo">Vimeo</option><option value="Dailymotion">Dailymotion</option><option value="Mp4_From_Url">Mp4 From Url</option><option value="Mkv_From_Url">Mkv From Url</option><option value="M3u8_From_Url">M3u8 From Url</option><option value="Dash_From_Url">Dash From Url</option><option value="Embed_Url">Embed Url</option><option value="DoodStream">DoodStream</option><option value="Dropbox">Dropbox</option><option value="Facebook">Facebook</option><option value="Fembed">Fembed</option><option value="GogoAnime">GogoAnime</option><option value="GoogleDrive">GoogleDrive</option><option value="MixDrop">MixDrop</option><option value="OK.ru">OK.ru</option><option value="Onedrive">Onedrive</option><option value="Streamtape">Streamtape</option><option value="Streamwish">Streamwish</option><option value="Torrent">Torrent</option><option value="Twitter">Twitter</option><option value="VK">VK</option><option value="Yandex">Yandex</option>
                    </select>
                </div>
                <div class="form-group full-width"><label for="edit_url">URL</label><input type="text" id="edit_url" name="url" required></div>
                <div class="form-group"><label for="edit_link_type">Type</label><select id="edit_link_type" name="link_type"><option value="stream">Stream</option><option value="download">Download</option></select></div>
                <div class="form-group"><label for="edit_status">Status</label><select id="edit_status" name="status"><option value="publish">Publish</option><option value="draft">Draft</option></select></div>
            </div>
            <hr>
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
    var addModal = document.getElementById("addLinkModal");
    var addBtn = document.getElementById("addLinkBtn");
    var closeAddBtn = document.getElementById("closeModalBtn");
    if(addBtn) { addBtn.onclick = function() { addModal.style.display = "block"; } }
    if(closeAddBtn) { closeAddBtn.onclick = function() { addModal.style.display = "none"; } }

    // Edit Modal JS
    var editModal = document.getElementById("editLinkModal");
    var closeEditBtn = document.getElementById("closeEditModalBtn");
    document.querySelectorAll('.edit-link-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var linkData = JSON.parse(this.getAttribute('data-link-info'));
            
            var editForm = document.getElementById('editLinkForm');
            editForm.action = '/admin/manage-episode-links/edit/' + linkData.id;
            
            document.getElementById('edit_label').value = linkData.label;
            document.getElementById('edit_quality').value = linkData.quality;
            document.getElementById('edit_size').value = linkData.size;
            document.getElementById('edit_source').value = linkData.source;
            document.getElementById('edit_url').value = linkData.url;
            document.getElementById('edit_link_type').value = linkData.link_type;
            document.getElementById('edit_status').value = linkData.status;

            editModal.style.display = "block";
        });
    });
    if(closeEditBtn) { closeEditBtn.onclick = function() { editModal.style.display = "none"; } }
    
    // Close modals if clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    });
</script>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>