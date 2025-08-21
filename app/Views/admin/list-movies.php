<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .content-list-item {
        display: flex;
        align-items: center;
        background: #2a2a2a;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
        gap: 15px;
    }
    .content-list-item img {
        width: 60px;
        height: 90px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .content-info {
        flex-grow: 1;
    }
    .content-info h3 {
        margin: 0 0 5px 0;
    }
    .content-info p {
        margin: 0;
        font-size: 14px;
        color: #ccc;
    }
    .content-actions {
        position: relative;
    }
    .options-btn {
        background: #444;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
    }
    .options-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
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
    }
    .options-dropdown a:hover {
        background: #555;
    }
    .content-actions.open .options-dropdown {
        display: block;
    }
</style>

<main>
    <h1>Manage Movies</h1>
    <a href="/admin/movies/add">Add New Movie</a>
    <hr>
    
    <div class="content-list">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="content-list-item">
                    <img src="https://image.tmdb.org/t/p/w200<?php echo $movie['poster_path']; ?>" alt="">
                    <div class="content-info">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($movie['overview'], 0, 150)); ?>...</p>
                    </div>
                    <div class="content-actions">
                        <button type="button" class="options-btn" onclick="toggleDropdown(this)">Options ▼</button>
                        <div class="options-dropdown">
                            <a href="/admin/movies/edit/<?php echo $movie['id']; ?>">Edit Movie</a>
                            <a href="/admin/manage-movie-links/<?php echo $movie['id']; ?>">Manage Links</a>
                            <a href="#">Send Email Notification</a>
                            <a href="/admin/movies/delete/<?php echo $movie['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #ff4d4d;">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No movies found.</p>
        <?php endif; ?>
    </div>
</main>

<script>
    function toggleDropdown(button) {
        var parent = button.parentElement;
        // Close other open dropdowns
        document.querySelectorAll('.content-actions.open').forEach(function(openDropdown) {
            if (openDropdown !== parent) {
                openDropdown.classList.remove('open');
            }
        });
        // Toggle the clicked one
        parent.classList.toggle('open');
    }
    // Close dropdowns if clicking outside
    window.onclick = function(event) {
        if (!event.target.matches('.options-btn')) {
            document.querySelectorAll('.content-actions.open').forEach(function(openDropdown) {
                openDropdown.classList.remove('open');
            });
        }
    }
</script>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>