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
    .form-group input {
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
    <h1>API Settings</h1>
    <p>Manage API keys for external services like TMDb.</p>
    <hr>

    <form action="/admin/settings/api" method="POST">
        <div class="form-group">
            <label for="tmdb_api_key">TMDb API Key</label>
            <input type="text" id="tmdb_api_key" name="tmdb_api_key" value="<?php echo htmlspecialchars($settings['tmdb_api_key'] ?? ''); ?>">
        </div>
        
        <button type="submit" class="save-button">Save API Settings</button>
    </form>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>