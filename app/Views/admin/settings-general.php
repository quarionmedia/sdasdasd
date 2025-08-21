<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .settings-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }
    .settings-form-main {
        flex: 2;
        min-width: 400px;
    }
    .settings-form-side {
        flex: 1;
        min-width: 250px;
        background-color: #2a2a2a;
        padding: 20px;
        border-radius: 5px;
        height: fit-content;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 8px;
        background-color: #333;
        border: 1px solid #555;
        color: #fff;
        border-radius: 4px;
    }
    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }
    .form-group .image-preview {
        max-height: 50px;
        background: #fff;
        padding: 5px;
        margin-bottom: 10px;
        border-radius: 4px;
        display: inline-block;
    }
    .form-group .favicon-preview {
        max-height: 32px;
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
    <h1>General Settings</h1>
    <p>Manage your site's general settings like name, description, and branding.</p>
    <hr>

    <form action="/admin/settings/general" method="POST" enctype="multipart/form-data">
        <div class="settings-container">
            <div class="settings-form-main">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="site_description">Site Description</label>
                    <textarea id="site_description" name="site_description"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="settings-form-side">
                <h3>Branding</h3>
                <div class="form-group">
                    <label for="logo">Site Logo</label>
                    <?php if (!empty($settings['logo_path'])): ?>
                        <img class="image-preview" src="/assets/images/<?php echo htmlspecialchars($settings['logo_path']); ?>" alt="Current Logo">
                    <?php endif; ?>
                    <input type="file" id="logo" name="logo">
                </div>
                <div class="form-group">
                    <label for="favicon">Site Favicon</label>
                    <?php if (!empty($settings['favicon_path'])): ?>
                        <img class="image-preview favicon-preview" src="/assets/images/<?php echo htmlspecialchars($settings['favicon_path']); ?>" alt="Current Favicon">
                    <?php endif; ?>
                    <input type="file" id="favicon" name="favicon">
                </div>
            </div>
        </div>
        
        <hr>
        <button type="submit" class="save-button">Save General Settings</button>
    </form>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>