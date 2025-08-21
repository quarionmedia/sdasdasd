<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input, .form-group select { width: 100%; max-width: 600px; padding: 8px; box-sizing: border-box; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px;}
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; max-width: 1220px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .button { background-color: #00aaff; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; cursor: pointer; border: none; font-size: 14px; }
</style>

<main>
    <h1>Edit Link for: <em style="color: #ccc;"><?php echo htmlspecialchars($movie['title']); ?></em></h1>
    <p><a href="/admin/manage-movie-links/<?php echo $movie['id']; ?>">&larr; Back to Links List</a></p>
    <hr>

    <form action="/admin/manage-movie-links/edit/<?php echo $link['id']; ?>" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="label">Label</label>
                <input type="text" id="label" name="label" placeholder="e.g., Server #1" value="<?php echo htmlspecialchars($link['label'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="quality">Quality</label>
                <input type="text" id="quality" name="quality" placeholder="e.g., 1080p" value="<?php echo htmlspecialchars($link['quality'] ?? ''); ?>">
            </div>
             <div class="form-group">
                <label for="size">Size</label>
                <input type="text" id="size" name="size" placeholder="e.g., 1.0GB" value="<?php echo htmlspecialchars($link['size'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="source">Source</label>
                <select id="source" name="source">
                    <?php
                    $sources = ["Youtube", "Vimeo", "Dailymotion", "Mp4_From_Url", "Mkv_From_Url", "M3u8_From_Url", "Dash_From_Url", "Embed_Url", "DoodStream", "Dropbox", "Facebook", "Fembed", "GogoAnime", "GoogleDrive", "MixDrop", "OK.ru", "Onedrive", "Streamtape", "Streamwish", "Torrent", "Twitter", "VK", "Yandex"];
                    foreach($sources as $source) {
                        $selected = ($link['source'] == $source) ? 'selected' : '';
                        echo "<option value='{$source}' {$selected}>{$source}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group full-width">
                <label for="url">URL</label>
                <input type="text" id="url" name="url" required value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>">
            </div>
             <div class="form-group">
                <label for="link_type">Type</label>
                <select id="link_type" name="link_type">
                    <option value="stream" <?php echo ($link['link_type'] == 'stream') ? 'selected' : ''; ?>>Stream</option>
                    <option value="download" <?php echo ($link['link_type'] == 'download') ? 'selected' : ''; ?>>Download</option>
                </select>
            </div>
             <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="publish" <?php echo ($link['status'] == 'publish') ? 'selected' : ''; ?>>Publish</option>
                    <option value="draft" <?php echo ($link['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>
        </div>
        <hr>
        <button type="submit" class="button">Save Changes</button>
    </form>
</main>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>