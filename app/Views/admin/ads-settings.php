<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .settings-form .form-section {
        background-color: #2a2a2a;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 30px;
    }
    .settings-form h3 {
        margin-top: 0;
        border-bottom: 1px solid #444;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; color: #ccc; }
    .form-group input, .form-group textarea, .form-group select { 
        width: 100%; 
        max-width: 600px; 
        padding: 8px; 
        box-sizing: border-box; 
        background-color: #333; 
        border: 1px solid #555; 
        color: #fff; 
        border-radius: 4px;
    }
    .form-group textarea {
        min-height: 100px;
        font-family: monospace;
    }
    .save-button {
        padding: 10px 20px;
        background-color: #42ca1a;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }
</style>

<main>
    <h1>ADS Settings</h1>
    <p>Manage advertisement settings for your website and mobile applications.</p>
    <hr style="border-color: #374151; margin: 20px 0;">

    <form action="/admin/ads-settings" method="POST" class="settings-form">
    
        <div class="form-section">
            <h3>Web Advertisement</h3>
            <div class="form-group">
                <label for="web_ad_header">Header Ad Code</label>
                <textarea id="web_ad_header" name="web_ad_header"><?php echo htmlspecialchars($settings['web_ad_header'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="web_ad_footer">Footer Ad Code</label>
                <textarea id="web_ad_footer" name="web_ad_footer"><?php echo htmlspecialchars($settings['web_ad_footer'] ?? ''); ?></textarea>
            </div>
             <div class="form-group">
                <label for="web_ad_movie_player_top">Ad Before Movie Player</label>
                <textarea id="web_ad_movie_player_top" name="web_ad_movie_player_top"><?php echo htmlspecialchars($settings['web_ad_movie_player_top'] ?? ''); ?></textarea>
            </div>
             <div class="form-group">
                <label for="web_ad_movie_player_bottom">Ad After Movie Player</label>
                <textarea id="web_ad_movie_player_bottom" name="web_ad_movie_player_bottom"><?php echo htmlspecialchars($settings['web_ad_movie_player_bottom'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Mobile Advertisement</h3>
            <div class="form-group">
                <label for="admob_app_id">Admob App ID</label>
                <input type="text" id="admob_app_id" name="admob_app_id" value="<?php echo htmlspecialchars($settings['admob_app_id'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="admob_banner_ad_id">Admob Banner Ad ID</label>
                <input type="text" id="admob_banner_ad_id" name="admob_banner_ad_id" value="<?php echo htmlspecialchars($settings['admob_banner_ad_id'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="admob_interstitial_ad_id">Admob Interstitial Ad ID</label>
                <input type="text" id="admob_interstitial_ad_id" name="admob_interstitial_ad_id" value="<?php echo htmlspecialchars($settings['admob_interstitial_ad_id'] ?? ''); ?>">
            </div>
             </div>

        <button type="submit" class="save-button">Save ADS Settings</button>
    </form>
</main>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>