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
        display: flex;
        align-items: center;
    }
    .content-info p {
        margin: 0;
        font-size: 14px;
        color: #ccc;
    }
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        margin-left: 10px;
        text-transform: capitalize;
    }
    .status-returning-series { background-color: #27ae60; color: #fff; } /* Yeşil */
    .status-ended, .status-canceled { background-color: #c0392b; color: #fff; } /* Kırmızı */
    .status-default { background-color: #7f8c8d; color: #fff; } /* Gri */

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
    <h1>Manage TV Shows</h1>
    <a href="/admin/tv-shows/add">Add New TV Show</a>
    <hr>
    
    <div class="content-list">
        <?php if (!empty($tvShows)): ?>
            <?php foreach ($tvShows as $show): ?>
                <div class="content-list-item">
                    <img src="https://image.tmdb.org/t/p/w200<?php echo $show['poster_path']; ?>" alt="">
                    <div class="content-info">
                        <h3>
                            <?php echo htmlspecialchars($show['title']); ?>
                            
                            <?php 
                            $status = strtolower(str_replace(' ', '-', $show['status'] ?? ''));
                            $statusClass = 'status-default';
                            if ($status == 'returning-series') { $statusClass = 'status-returning-series'; }
                            elseif ($status == 'ended' || $status == 'canceled') { $statusClass = 'status-ended'; }
                            ?>
                            <span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($show['status']); ?></span>
                        </h3>
                        <p><?php echo htmlspecialchars(substr($show['overview'], 0, 150)); ?>...</p>
                    </div>
                    <div class="content-actions">
                        <button type="button" class="options-btn" onclick="toggleDropdown(this)">Options ▼</button>
                        <div class="options-dropdown">
                            <a href="/admin/tv-shows/edit/<?php echo $show['id']; ?>">Edit TV Show</a>
                            <a href="/admin/manage-seasons/<?php echo $show['id']; ?>">Manage Seasons</a>
                            <a href="#">Send Email Notification</a>
                            <a href="/admin/tv-shows/delete/<?php echo $show['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #ff4d4d;">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No TV shows found.</p>
        <?php endif; ?>
    </div>
</main>

<script>
    function toggleDropdown(button) {
        var parent = button.parentElement;
        // Diğer açık dropdown'ları kapat
        document.querySelectorAll('.content-actions.open').forEach(function(openDropdown) {
            if (openDropdown !== parent) {
                openDropdown.classList.remove('open');
            }
        });
        // Tıklananı aç/kapat
        parent.classList.toggle('open');
    }
    // Dışarıya tıklandığında dropdown'ları kapat
    window.onclick = function(event) {
        if (!event.target.matches('.options-btn')) {
            document.querySelectorAll('.content-actions.open').forEach(function(openDropdown) {
                openDropdown.classList.remove('open');
            });
        }
    }
</script>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>