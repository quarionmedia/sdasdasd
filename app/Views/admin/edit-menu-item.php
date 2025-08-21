<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    /* Diğer formlarla aynı stili kullanıyoruz */
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input, .form-group select { width: 100%; max-width: 400px; padding: 8px; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px; }
    .form-group button { padding: 10px 20px; background-color: #00aaff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
</style>

<main>
    <h1>Edit Menu Item</h1>
    <p><a href="/admin/menu">&larr; Back to Menu Manager</a></p>
    <hr>

    <form action="/admin/menu/edit/<?php echo $menuItem['id']; ?>" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($menuItem['title']); ?>">
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" id="url" name="url" required placeholder="e.g., /movies" value="<?php echo htmlspecialchars($menuItem['url']); ?>">
        </div>
        <div class="form-group">
            <label for="menu_order">Order</label>
            <input type="number" id="menu_order" name="menu_order" value="<?php echo $menuItem['menu_order']; ?>">
        </div>
        <div class="form-group">
            <label for="is_active">Status</label>
            <select name="is_active" id="is_active">
                <option value="1" <?php echo $menuItem['is_active'] ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo !$menuItem['is_active'] ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <button type="submit">Save Changes</button>
    </form>
</main>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>