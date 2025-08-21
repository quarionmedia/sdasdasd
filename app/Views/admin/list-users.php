<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .action-bar { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    .action-bar .button, .modal-content .button { background-color: #42ca1a; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; border:none; cursor: pointer; font-size:14px; }
    .table-controls { display: flex; gap: 10px; align-items: center; font-size: 14px; }
    .table-controls select, .table-controls input { background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px; padding: 5px; }
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

    .role-admin { background-color: #e67e22; color: #fff; padding: 3px 7px; border-radius: 4px; font-size: 12px; }
    .role-user { background-color: #3498db; color: #fff; padding: 3px 7px; border-radius: 4px; font-size: 12px; }
    
    /* Modal Stilleri */
    .modal-overlay { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); }
    .modal-content { background-color: #2a2a2a; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 5px; position: relative; }
    .modal-close { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px;}
</style>

<main>
    <h1>User Management</h1>
    
    <div class="action-bar">
        <div class="table-controls">
            <span>Show</span><select><option>10</option><option>25</option><option>50</option></select><span>entries</span>
            <span style="margin-left: 20px;">Search:</span><input type="search">
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Options</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Subscription</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td class="content-actions">
                            <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Options ▼</button>
                            <div class="options-dropdown">
                                <a href="#">Add Subscription</a>
                                <a href="#">Send Notification</a>
                                <button type="button" class="edit-user-btn" data-user-info='<?php echo htmlspecialchars(json_encode($user)); ?>'>Edit User</button>
                                <?php if ($_SESSION['user_id'] != $user['id']): ?>
                                    <a href="/admin/users/delete/<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #ff4d4d;">Delete User</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>N/A</td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><span class="role-<?php echo $user['is_admin'] ? 'admin' : 'user'; ?>"><?php echo $user['is_admin'] ? 'Admin' : 'User'; ?></span></td>
                        <td>Free</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="editUserModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" id="closeEditModalBtn">&times;</span>
        <h2>Edit User</h2>
        <hr>
        <form id="editUserForm" action="" method="POST">
            <div class="form-group">
                <label for="edit_email">Email</label>
                <input type="email" id="edit_email" name="email" required>
            </div>
            <div class="form-group">
                <label for="edit_is_admin">Role</label>
                <select id="edit_is_admin" name="is_admin">
                    <option value="0">User</option>
                    <option value="1">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit_password">New Password</label>
                <input type="password" id="edit_password" name="password" placeholder="Leave blank to keep current password">
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
            document.querySelectorAll('.options-dropdown').forEach(function(d) { d.style.display = 'none'; });
        }
    });
    // Edit User Modal JS
    var editModal = document.getElementById("editUserModal");
    var closeEditBtn = document.getElementById("closeEditModalBtn");
    var editForm = document.getElementById("editUserForm");

    document.querySelectorAll('.edit-user-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var userData = JSON.parse(this.getAttribute('data-user-info'));
            editForm.action = '/admin/users/edit/' + userData.id;
            document.getElementById('edit_email').value = userData.email;
            document.getElementById('edit_is_admin').value = userData.is_admin;
            document.getElementById('edit_password').value = '';
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