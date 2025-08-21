<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .action-bar { margin-bottom: 20px; display: flex; justify-content: flex-end; }
    .action-bar .button, .modal-content .button { background-color: #42ca1a; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; border:none; cursor: pointer; font-size:14px; }
    .admin-table { border-collapse: collapse; width: 100%; }
    .admin-table th, .admin-table td { border-bottom: 1px solid #444; padding: 12px 10px; text-align: left; vertical-align: middle; }
    .admin-table th { background-color: #333; font-size: 14px; text-transform: uppercase; color: #aaa; }
    .admin-table tbody tr:hover { background-color: #2a2a2a; }
    
    .options-btn { background: #444; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    .options-dropdown {
        display: none;
        position: absolute; /* JS pozisyonu belirleyecek */
        background: #333;
        border: 1px solid #555;
        border-radius: 4px;
        z-index: 10;
        min-width: 180px;
        padding: 5px 0;
    }
    .options-dropdown a, .options-dropdown button { 
        display: block; 
        padding: 8px 12px; 
        text-decoration: none; 
        color: #fff; 
        background:none; 
        border:none; 
        width:100%; 
        text-align:left; 
        cursor:pointer; 
        font-size:14px; 
        font-family:sans-serif;
        box-sizing: border-box;
    }
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
    <h1>Genre Management</h1>
    <p>Manage all genres. Genres are also added automatically when importing content from TMDb.</p>
    
    <div class="action-bar">
        <button type="button" class="button" id="addGenreBtn">+ Add New Genre</button>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($genres)): ?>
                <?php foreach ($genres as $genre): ?>
                    <tr>
                        <td><?php echo $genre['id']; ?></td>
                        <td><?php echo htmlspecialchars($genre['name']); ?></td>
                        <td class="content-actions">
                            <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Options ▼</button>
                            <div class="options-dropdown">
                                <button type="button" class="edit-genre-btn" data-genre-info='<?php echo htmlspecialchars(json_encode($genre)); ?>'>Edit</button>
                                <a href="/admin/genres/delete/<?php echo $genre['id']; ?>" onclick="return confirm('Are you sure? Deleting a genre might affect existing content.');" style="color: #ff4d4d;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No genres found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="addGenreModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeAddModalBtn">&times;</span>
        <h2>Add New Genre</h2>
        <hr>
        <form action="/admin/genres/add" method="POST">
            <div class="form-group">
                <label for="name">Genre Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <button type="submit" class="button">Create Genre</button>
        </form>
    </div>
</div>

<div id="editGenreModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeEditModalBtn">&times;</span>
        <h2>Edit Genre</h2>
        <hr>
        <form id="editGenreForm" action="" method="POST">
            <div class="form-group">
                <label for="edit_name">Genre Name</label>
                <input type="text" id="edit_name" name="name" required>
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
    var addModal = document.getElementById("addGenreModal");
    var addBtn = document.getElementById("addGenreBtn");
    var closeAddBtn = document.getElementById("closeAddModalBtn");
    addBtn.onclick = function() { addModal.style.display = "block"; }
    closeAddBtn.onclick = function() { addModal.style.display = "none"; }

    // Edit Modal JS
    var editModal = document.getElementById("editGenreModal");
    var closeEditBtn = document.getElementById("closeEditModalBtn");
    var editForm = document.getElementById("editGenreForm");

    document.querySelectorAll('.edit-genre-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var genreData = JSON.parse(this.getAttribute('data-genre-info'));
            editForm.action = '/admin/genres/edit/' + genreData.id;
            document.getElementById('edit_name').value = genreData.name;
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