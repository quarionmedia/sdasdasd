<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    /* Diğer ayar sayfalarıyla aynı stiller */
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-group select {
        width: 100%;
        max-width: 500px;
        padding: 8px;
        background-color: #333;
        border: 1px solid #555;
        color: #fff;
        border-radius: 4px;
    }
    .save-button {
        padding: 10px 20px;
        background-color: #00aaff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }
</style>

<main>
    <h1>Security Settings</h1>
    <p>Manage security and access settings for your site.</p>
    <hr>

    <form action="/admin/settings/security" method="POST">
        <div class="form-group">
            <label for="login_required">Login Required to View Content?</label>
            <select name="login_required" id="login_required">
                <option value="1" <?php echo ($settings['login_required'] ?? 0) == 1 ? 'selected' : ''; ?>>Yes (Site is private)</option>
                <option value="0" <?php echo ($settings['login_required'] ?? 0) == 0 ? 'selected' : ''; ?>>No (Site is public)</option>
            </select>
        </div>
        
        <button type="submit" class="save-button">Save Security Settings</button>
    </form>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>