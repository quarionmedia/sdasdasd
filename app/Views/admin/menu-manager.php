<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    /* Diğer admin tablolarıyla aynı stili kullanıyoruz */
    .admin-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }
    .admin-table th, .admin-table td {
        border: 1px solid #555;
        padding: 8px;
        text-align: left;
    }
    .admin-table th {
        background-color: #333;
    }
</style>

<main>
    <h1>Menu Manager</h1>
    <p>Manage the main navigation menu for your site.</p>
    <a href="/admin/menu/add">Add New Menu Item</a>
    <hr>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Title</th>
                <th>URL</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($menuItems)): ?>
                <?php foreach ($menuItems as $item): ?>
                    <tr>
                        <td><?php echo $item['menu_order']; ?></td>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars($item['url']); ?></td>
                        <td><?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <a href="/admin/menu/edit/<?php echo $item['id']; ?>">Edit</a> | 
                            <a href="/admin/menu/delete/<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete this menu item?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No menu items found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>