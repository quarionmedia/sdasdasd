<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .action-buttons { margin: 20px 0; }
    .action-buttons .button, .modal-content .button { background-color: #00aaff; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; margin-right: 10px; cursor: pointer; border: none; font-family: sans-serif; font-size: 14px; }
    .admin-table { border-collapse: collapse; width: 100%; }
    .admin-table th, .admin-table td { border: 1px solid #555; padding: 10px; text-align: left; vertical-align: middle; }
    .admin-table th { background-color: #333; }

    /* Modal (Popup) Stilleri */
    .modal-overlay { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); }
    .modal-content { background-color: #2a2a2a; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 5px; position: relative; }
    .modal-close { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input { width: 100%; padding: 8px; box-sizing: border-box; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px;}
    
    /* Options Dropdown Stilleri (JS ile pozisyonlanacak) */
    .options-btn { background: #444; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    .options-dropdown { display: none; position: absolute; background: #333; border: 1px solid #555; border-radius: 4px; z-index: 10; min-width: 160px; padding: 5px 0; }
    .options-dropdown a, .options-dropdown button { display: block; padding: 8px 12px; text-decoration: none; color: #fff; background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: sans-serif; font-size: 14px; }
    .options-dropdown a:hover, .options-dropdown button:hover { background: #555; }
</style>

<main>
    <h1>Manage Seasons for: <em style="color: #ccc;"><?php echo htmlspecialchars($tvShow['title']); ?></em></h1>
    <p><a href="/admin/tv-shows">&larr; Back to TV Show List</a></p>
    
    <div class="action-buttons">
        <button type="button" class="button" id="addSeasonBtn">Add Season</button>
        <a href="/admin/tv-shows/import-seasons/<?php echo $tvShow['id']; ?>/<?php echo $tvShow['tmdb_id']; ?>" class="button" onclick="return confirm('Are you sure? This will fetch all season and episode data from TMDb. Existing data will be preserved.');">Fetch All Seasons</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Season Name</th>
                <th>Order</th>
                <th>Episodes</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($seasons)): ?>
                <?php foreach ($seasons as $season): ?>
                    <tr>
                        <td><?php echo $season['id']; ?></td>
                        <td><?php echo htmlspecialchars($season['name']); ?></td>
                        <td><?php echo $season['season_number']; ?></td>
                        <td><?php echo $season['episode_count']; ?></td>
                        <td>Published</td>
                        <td class="content-actions">
                            <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Actions ▼</button>
                            <div class="options-dropdown">
                                <a href="/admin/manage-episodes/<?php echo $season['id']; ?>">Manage Episodes</a>
                                <button type="button" class="edit-season-btn" data-season-info='<?php echo htmlspecialchars(json_encode($season)); ?>'>Edit</button>
                                <a href="/admin/manage-seasons/delete/<?php echo $season['id']; ?>" onclick="return confirm('Are you sure? This will delete the season and all its episodes!');" style="color: #ff4d4d;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No seasons found for this TV show in the database.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="addSeasonModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeAddModalBtn">&times;</span>
        <h2>Add New Season</h2>
        <hr>
        <form action="/admin/manage-seasons/add/<?php echo $tvShow['id']; ?>" method="POST">
            <div class="form-group">
                <label for="name">Season Name</label>
                <input type="text" id="name" name="name" placeholder="e.g., Season 1" required>
            </div>
            <div class="form-group">
                <label for="season_number">Order (Season Number)</label>
                <input type="number" id="season_number" name="season_number" placeholder="e.g., 1" required>
            </div>
            <button type="submit" class="button">Create</button>
        </form>
    </div>
</div>

<div id="editSeasonModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeEditModalBtn">&times;</span>
        <h2>Edit Season</h2>
        <hr>
        <form id="editSeasonForm" action="" method="POST">
            <div class="form-group">
                <label for="edit_name">Season Name</label>
                <input type="text" id="edit_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit_season_number">Order (Season Number)</label>
                <input type="number" id="edit_season_number" name="season_number" required>
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

        document.querySelectorAll('.options-dropdown').forEach(function(d) {
            d.style.display = 'none';
        });

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
    var addModal = document.getElementById("addSeasonModal");
    var addBtn = document.getElementById("addSeasonBtn");
    var closeAddBtn = document.getElementById("closeAddModalBtn");
    addBtn.onclick = function() { addModal.style.display = "block"; }
    closeAddBtn.onclick = function() { addModal.style.display = "none"; }

    // Edit Modal JS
    var editModal = document.getElementById("editSeasonModal");
    var closeEditBtn = document.getElementById("closeEditModalBtn");
    var editForm = document.getElementById("editSeasonForm");

    document.querySelectorAll('.edit-season-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var seasonData = JSON.parse(this.getAttribute('data-season-info'));
            
            editForm.action = '/admin/manage-seasons/edit/' + seasonData.id;
            
            document.getElementById('edit_name').value = seasonData.name;
            document.getElementById('edit_season_number').value = seasonData.season_number;

            editModal.style.display = "block";
        });
    });

    closeEditBtn.onclick = function() { editModal.style.display = "none"; }

    // Dışarıya tıklandığında iki modal'ı da kapat
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