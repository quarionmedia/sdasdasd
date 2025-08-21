<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .action-bar { margin: 20px 0; display: flex; justify-content: flex-end; gap: 10px; }
    .action-bar .button, .modal-content .button { background-color: #00aaff; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; font-size: 14px; }
    .admin-table { border-collapse: collapse; width: 100%; }
    .admin-table th, .admin-table td { border: 1px solid #555; padding: 10px; text-align: left; vertical-align: middle; }
    .admin-table th { background-color: #333; }
    .episode-still { width: 120px; height: 68px; object-fit: cover; border-radius: 4px; }
    .badge { padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: capitalize; }
    .badge-published { background-color: #27ae60; color: #fff; }
    .badge-free { background-color: #3498db; color: #fff; }
    .badge-premium { background-color: #f39c12; color: #fff; }
    .toggle-switch { width: 40px; height: 20px; background: #c0392b; border-radius: 10px; position: relative; cursor: pointer; }
    .toggle-switch.toggled { background: #27ae60; }
    .toggle-switch::after { content: ''; position: absolute; width: 16px; height: 16px; background: #fff; border-radius: 50%; top: 2px; left: 2px; transition: left 0.2s; }
    .toggle-switch.toggled::after { left: 22px; }
    .options-btn { background: #444; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    .options-dropdown { display: none; position: absolute; background: #333; border: 1px solid #555; border-radius: 4px; z-index: 10; min-width: 160px; padding: 5px 0; }
    .options-dropdown a, .options-dropdown button { display: block; padding: 8px 12px; text-decoration: none; color: #fff; background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-size: 14px; }
    .options-dropdown a:hover, .options-dropdown button:hover { background: #555; }
    /* Modal Stilleri */
    .modal-overlay { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); }
    .modal-content { background-color: #2a2a2a; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 5px; position: relative; }
    .modal-close { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px;}
</style>

<main>
    <h1>Manage Episodes: <em style="color: #ccc;"><?php echo htmlspecialchars($tvShow['title']); ?> - <?php echo htmlspecialchars($season['name']); ?></em></h1>
    <p><a href="/admin/manage-seasons/<?php echo $tvShow['id']; ?>">&larr; Back to Seasons List</a></p>
    
    <div class="action-bar">
        <button type="button" class="button" id="addEpisodeBtn">Add Episode</button>
        <a href="/admin/manage-episodes/fetch/<?php echo $season['id']; ?>" class="button" onclick="return confirm('Are you sure? This will fetch all episode data for this season from TMDb.');">Fetch All Episodes</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Thumbnail</th>
                <th>Episode Name</th>
                <th>Downloadable</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($episodes)): ?>
                <?php foreach ($episodes as $episode): ?>
                    <tr>
                        <td><?php echo $episode['episode_number']; ?></td>
                        <td><img class="episode-still" src="<?php echo !empty($episode['still_path']) ? 'https://image.tmdb.org/t/p/w300'.$episode['still_path'] : 'https://via.placeholder.com/120x68.png?text=N/A'; ?>" alt=""></td>
                        <td><?php echo htmlspecialchars($episode['name']); ?></td>
                        <td><div class="toggle-switch <?php echo $episode['is_downloadable'] ? 'toggled' : ''; ?>"></div></td>
                        <td><span class="badge <?php echo $episode['type'] == 'premium' ? 'badge-premium' : 'badge-free'; ?>"><?php echo htmlspecialchars($episode['type']); ?></span></td>
                        <td><span class="badge badge-published"><?php echo htmlspecialchars($episode['status']); ?></span></td>
                        <td class="content-actions">
                            <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Options ▼</button>
                            <div class="options-dropdown">
                                <button type="button" class="edit-episode-btn" data-episode-info='<?php echo htmlspecialchars(json_encode($episode)); ?>'>Edit Episode</button>
                                <a href="/admin/manage-episode-links/<?php echo $episode['id']; ?>">Manage Links</a>
                                <a href="/admin/manage-episodes/delete/<?php echo $episode['id']; ?>" onclick="return confirm('Are you sure? This will delete the episode and all its associated data!');" style="color: #ff4d4d;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No episodes found for this season in the database.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="addEpisodeModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeAddModalBtn">&times;</span>
        <h2>Add New Episode</h2>
        <hr>
        <form action="/admin/manage-episodes/add/<?php echo $season['id']; ?>" method="POST">
            <div class="form-group">
                <label for="episode_number">Episode Number</label>
                <input type="number" id="episode_number" name="episode_number" placeholder="e.g., 1" required>
            </div>
            <div class="form-group">
                <label for="name">Episode Title</label>
                <input type="text" id="name" name="name" placeholder="e.g., Pilot" required>
            </div>
            <div class="form-group">
                <label for="overview">Overview</label>
                <textarea id="overview" name="overview" rows="4"></textarea>
            </div>
            <button type="submit" class="button">Create Episode</button>
        </form>
    </div>
</div>

<div id="editEpisodeModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeEditModalBtn">&times;</span>
        <h2>Edit Episode</h2>
        <hr>
        <form id="editEpisodeForm" action="" method="POST">
            <div class="form-group">
                <label for="edit_episode_number">Episode Number</label>
                <input type="number" id="edit_episode_number" name="episode_number" required>
            </div>
            <div class="form-group">
                <label for="edit_name">Episode Title</label>
                <input type="text" id="edit_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit_overview">Overview</label>
                <textarea id="edit_overview" name="overview" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="edit_air_date">Air Date</label>
                <input type="date" id="edit_air_date" name="air_date">
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
    var addModal = document.getElementById("addEpisodeModal");
    var addBtn = document.getElementById("addEpisodeBtn");
    var closeAddBtn = document.getElementById("closeAddModalBtn");
    addBtn.onclick = function() { addModal.style.display = "block"; }
    closeAddBtn.onclick = function() { addModal.style.display = "none"; }

    // Edit Modal JS
    var editModal = document.getElementById("editEpisodeModal");
    var closeEditBtn = document.getElementById("closeEditModalBtn");
    var editForm = document.getElementById("editEpisodeForm");

    document.querySelectorAll('.edit-episode-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var episodeData = JSON.parse(this.getAttribute('data-episode-info'));
            
            editForm.action = '/admin/manage-episodes/edit/' + episodeData.id;
            
            document.getElementById('edit_episode_number').value = episodeData.episode_number;
            document.getElementById('edit_name').value = episodeData.name;
            document.getElementById('edit_overview').value = episodeData.overview;
            document.getElementById('edit_air_date').value = episodeData.air_date;

            editModal.style.display = "block";
        });
    });
    
    closeEditBtn.onclick = function() { editModal.style.display = "none"; }

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