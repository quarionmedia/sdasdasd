<?php require_once __DIR__ . '/partials/header.php'; ?>

<main>
    <h1>Add New Menu Item</h1>
    <p><a href="/admin/menu">&larr; Back to Menu Manager</a></p>
    <hr>

    <form action="/admin/menu/add" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" id="url" name="url" required placeholder="e.g., /movies or https://external.com">
        </div>
        <div class="form-group">
            <label for="menu_order">Order</label>
            <input type="number" id="menu_order" name="menu_order" value="0">
        </div>
        <div class="form-group">
            <label for="is_active">Status</label>
            <select name="is_active" id="is_active">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button type="submit">Save Menu Item</button>
    </form>
</main>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>