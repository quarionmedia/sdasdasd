<?php require_once __DIR__ . '/partials/header.php'; ?>
<style>
    .search-results-container { padding: 100px 5%; }
    .search-header h1 { font-size: 28px; margin-bottom: 10px; }
    .search-header p { color: #aaa; margin-top: 0; }
    .results-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
    .movie-poster a { display: block; }
    .movie-poster img { width: 100%; height: 140px; object-fit: cover; border-radius: 8px; transition: transform 0.2s, border-color 0.2s; border: 2px solid transparent; }
    .movie-poster a:hover img { transform: scale(1.05); border-color: #42ca1a; }
    .movie-poster h3 { font-size: 16px; margin: 10px 0 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>
<div class="search-results-container">
    <div class="search-header">
        <h1>Search Results for: "<?php echo htmlspecialchars($query); ?>"</h1>
        <p><?php echo count($results); ?> results found.</p>
    </div>
    <div class="results-grid">
    <?php if (!empty($results)): ?>
        <?php foreach ($results as $item): ?>
            <?php
                $isTvShow = isset($item['first_air_date']) || isset($item['name']);
                $urlType = $isTvShow ? 'tv-show' : 'movie';
                $detailUrl = "/{$urlType}/" . ($item['slug'] ?? '');
            ?>
            <div class="movie-poster">
                <a href="<?php echo $detailUrl; ?>">
                    <img src="https://image.tmdb.org/t/p/w500<?php echo $item['backdrop_path'] ?? $item['poster_path']; ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?>">
                    <h3><?php echo htmlspecialchars($item['title'] ?? $item['name']); ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No content found matching your search term.</p>
    <?php endif; ?>
</div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>