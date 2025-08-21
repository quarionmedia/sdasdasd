<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .action-bar { margin-bottom: 20px; display: flex; justify-content: flex-end; }
    .action-bar .button, .modal-content .button { background-color: #42ca1a; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; border:none; cursor: pointer; font-size:14px; }
    .admin-table { border-collapse: collapse; width: 100%; }
    .admin-table th, .admin-table td { border-bottom: 1px solid #444; padding: 12px 10px; text-align: left; vertical-align: middle; }
    .admin-table th { background-color: #333; font-size: 14px; text-transform: uppercase; color: #aaa; }
    .admin-table tbody tr:hover { background-color: #2a2a2a; }
    .platform-logo { max-width: 100px; max-height: 40px; background-color: rgba(255,255,255,0.1); padding: 5px; border-radius: 4px; }
    
    .options-btn { background: #444; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    .options-dropdown {
        display: none;
        position: absolute;
        background: #333;
        border: 1px solid #555;
        border-radius: 4px;
        z-index: 10;
        min-width: 120px;
        padding: 5px 0;
    }
    .options-dropdown button, .options-dropdown a { display: block; padding: 8px 12px; text-decoration: none; color: #fff; background:none; border:none; width:100%; text-align:left; cursor:pointer; font-size:14px; font-family:sans-serif; box-sizing: border-box; }
    .options-dropdown a:hover, .options-dropdown button:hover { background: #555; }

    /* Modal Stilleri */
    .modal-overlay { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); }
    .modal-content { background-color: #2a2a2a; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 5px; position: relative; }
    .modal-close { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input { width: 100%; padding: 8px; box-sizing: border-box; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px;}
</style>

<main>
    <h1>Content Platforms</h1>
    <p>Manage content platforms like Netflix, Disney+, etc.</p>
    
    <div class="action-bar">
        <button type="button" class="button" id="addPlatformBtn">+ Add New Platform</button>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($platforms)): ?>
                <?php foreach ($platforms as $platform): ?>
                    <tr>
                        <td><?php echo $platform['id']; ?></td>
                        <td>
                            <?php if (!empty($platform['logo_path'])): ?>
                                <img class="platform-logo" src="/assets/images/platforms/<?php echo $platform['logo_path']; ?>" alt="<?php echo htmlspecialchars($platform['name']); ?>">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($platform['name']); ?></td>
                        <td><?php echo htmlspecialchars($platform['slug']); ?></td>
                        <td class="content-actions">
                            <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Options ▼</button>
                            <div class="options-dropdown">
                                <button type="button" class="edit-platform-btn" data-platform-info='<?php echo htmlspecialchars(json_encode($platform)); ?>'>Edit</button>
                                <a href="/admin/platforms/delete/<?php echo $platform['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #ff4d4d;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No platforms found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="addPlatformModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeAddModalBtn">&times;</span>
        <h2>Add New Platform</h2>
        <hr>
        <form action="/admin/platforms" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Platform Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" required placeholder="e.g., netflix, disney-plus">
            </div>
            <div class="form-group">
                <label for="logo_path">Logo Image</label>
                <input type="file" id="logo_path" name="logo_path">
            </div>
            <div class="form-group">
                <label for="background_path">Background Image</label>
                <input type="file" id="background_path" name="background_path">
            </div>
            <button type="submit" class="button">Create Platform</button>
        </form>
    </div>
</div>

<div id="editPlatformModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeEditModalBtn">&times;</span>
        <h2>Edit Platform</h2>
        <hr>
        <form id="editPlatformForm" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="edit_name">Platform Name</label>
                <input type="text" id="edit_name" name="name" required>
            </div>
             <div class="form-group">
                <label for="edit_slug">Slug</label>
                <input type="text" id="edit_slug" name="slug" required>
            </div>
            <div class="form-group">
                <label for="edit_logo_path">New Logo (Optional)</label>
                <input type="file" id="edit_logo_path" name="logo_path">
            </div>
            <div class="form-group">
                <label for="edit_background_path">New Background (Optional)</label>
                <input type="file" id="edit_background_path" name="background_path">
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
        document.querySelectorAll('.options-dropdown').forEach(function(d) { d.style.display = 'none'; });
        if (!wasOpen) {
            var btnRect = button.getBoundingClientRect();
            dropdown.style.display = 'block';
            dropdown.style.position = 'fixed';
            dropdown.style.top = (btnRect.bottom + 2) + 'px';
            dropdown.style.left = (btnRect.right - dropdown.offsetWidth) + 'px';
        }
    }
    window.addEventListener('click', function(event) {
        if (!event.target.matches('.options-btn')) {
            document.querySelectorAll('.options-dropdown').forEach(function(d) {
                d.style.display = 'none';
            });
        }
    });

    // Add Modal JS
    var addModal = document.getElementById("addPlatformModal");
    var addBtn = document.getElementById("addPlatformBtn");
    var closeAddBtn = document.getElementById("closeAddModalBtn");
    addBtn.onclick = function() { addModal.style.display = "block"; }
    closeAddBtn.onclick = function() { addModal.style.display = "none"; }

    // Edit Modal JS
    var editModal = document.getElementById("editPlatformModal");
    var closeEditBtn = document.getElementById("closeEditModalBtn");
    var editForm = document.getElementById("editPlatformForm");
    document.querySelectorAll('.edit-platform-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var platformData = JSON.parse(this.getAttribute('data-platform-info'));
            editForm.action = '/admin/platforms/edit/' + platformData.id;
            document.getElementById('edit_name').value = platformData.name;
            document.getElementById('edit_slug').value = platformData.slug;
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