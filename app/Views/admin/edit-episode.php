<?php require_once __DIR__ . '/partials/header.php'; ?>

<main>
    <h1>
        Edit Episode: 
        <small style="color: #ccc;">
            <?php echo htmlspecialchars($episode['name']); ?>
        </small>
    </h1>

    <p><a href="/admin/tv-shows/edit/<?php echo $tvShowId; ?>">&larr; Back to TV Show</a></p>

    <form action="/admin/episodes/edit/<?php echo $episode['id']; ?>" method="POST">
        
        <div>
            <label for="name">Episode Title:</label><br>
            <input type="text" id="name" name="name" required style="width: 400px;" value="<?php echo htmlspecialchars($episode['name']); ?>"><br><br>
            
            <label for="overview">Overview:</label><br>
            <textarea id="overview" name="overview" rows="5" style="width: 400px;"><?php echo htmlspecialchars($episode['overview']); ?></textarea><br><br>

            <label for="video_url">Video URL (.m3u8, .mpd, .mp4, etc.):</label><br>
            <input type="text" id="video_url" name="video_url" style="width: 400px;" value="<?php echo htmlspecialchars($episode['video_url'] ?? ''); ?>"><br><br>

            <button type="submit">Save Episode Changes</button>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>