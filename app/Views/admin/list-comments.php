<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .admin-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
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
    .status-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        text-transform: capitalize;
    }
    .status-approved {
        background-color: #27ae60;
        color: #fff;
    }
    .status-pending {
        background-color: #e67e22;
        color: #fff;
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
    .options-dropdown a {
        display: block;
        padding: 8px 12px;
        text-decoration: none;
        color: #fff;
        font-size: 14px;
        font-family: sans-serif;
    }
    .options-dropdown a:hover { background: #555; }
</style>

<main>
    <h1>Comment Management</h1>
    <p>Approve, unapprove, or delete user comments.</p>
    <hr>

    <table class="admin-table">
        <thead>
            <tr>
                <th>User</th>
                <th style="width: 40%;">Comment</th>
                <th>Posted On</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comment['email']); ?></td>
                        <td><p style="max-height: 60px; overflow-y: auto; margin: 0;"><?php echo htmlspecialchars($comment['comment_text']); ?></p></td>
                        <td>
                            <?php if ($comment['movie_id']): ?>
                                Movie ID: <?php echo $comment['movie_id']; ?>
                            <?php elseif ($comment['episode_id']): ?>
                                Episode ID: <?php echo $comment['episode_id']; ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('Y-m-d H:i', strtotime($comment['created_at'])); ?></td>
                        <td>
                            <?php if ($comment['status'] == 'approved'): ?>
                                <span class="status-badge status-approved">Approved</span>
                            <?php else: ?>
                                <span class="status-badge status-pending">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="content-actions">
                            <button type="button" class="options-btn" onclick="toggleDropdown(this, event)">Options ▼</button>
                            <div class="options-dropdown">
                                <?php if ($comment['status'] == 'pending'): ?>
                                    <a href="/admin/comments/approve/<?php echo $comment['id']; ?>">Approve</a>
                                <?php else: ?>
                                    <a href="/admin/comments/unapprove/<?php echo $comment['id']; ?>">Unapprove</a>
                                <?php endif; ?>
                                <a href="/admin/comments/delete/<?php echo $comment['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #ff4d4d;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No comments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
    
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
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>