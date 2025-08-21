<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .search-page-container {
        padding: 100px 5%;
        max-width: 1200px;
        margin: 0 auto;
    }
    .search-box {
        position: relative;
        margin-bottom: 40px;
    }
    .search-box input {
        width: 100%;
        padding: 15px 25px;
        font-size: 18px;
        border-radius: 30px;
        border: 1px solid #555;
        background-color: #333;
        color: #fff;
    }
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Genişlik güncellendi */
        gap: 20px;
    }
    .content-poster a {
        display: block;
    }
    .content-poster img {
        width: 100%;
        height: 160px; /* Yatay posterler için yükseklik */
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.2s, border-color 0.2s;
        border: 2px solid transparent;
    }
    .content-poster a:hover img {
        transform: scale(1.05);
        border-color: #42ca1a;
    }
    .explore-title {
        grid-column: 1 / -1;
        font-size: 22px;
        color: #ccc;
        margin-bottom: 10px;
    }
</style>

<div class="search-page-container">
    <div class="search-box">
        <input type="search" id="searchInput" placeholder="Search for movies, TV shows..." autofocus>
    </div>

    <div id="resultsGrid" class="results-grid">
    <?php if (!empty($initialResults)): ?>
        <h2 class="explore-title">Explore</h2>
        <?php foreach ($initialResults as $item): ?>
            <?php
                $isTvShow = isset($item['first_air_date']) || isset($item['name']);
                $urlType = $isTvShow ? 'tv-show' : 'movie';
                $detailUrl = "/{$urlType}/" . ($item['slug'] ?? '');
            ?>
            <div class="content-poster">
                <a href="<?php echo $detailUrl; ?>">
                    <img src="https://image.tmdb.org/t/p/w500<?php echo $item['logo_backdrop_path'] ?? $item['backdrop_path']; ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?>">
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const resultsGrid = document.getElementById('resultsGrid');
    const initialContent = resultsGrid.innerHTML; // Sayfa ilk yüklendiğindeki içeriği sakla
    let searchTimeout;

    searchInput.addEventListener('keyup', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);
        
        if (query.length > 2) {
            searchTimeout = setTimeout(function() {
                fetch('/api/search?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
    resultsGrid.innerHTML = '';

    if (data.length > 0) {
        data.forEach(item => {
            const isTvShow = item.first_air_date || item.name;
            const urlType = isTvShow ? 'tv-show' : 'movie';
            const detailUrl = `/${urlType}/${item.slug || ''}`;

            const posterDiv = document.createElement('div');
            posterDiv.className = 'content-poster';

            const title = item.title || item.name;
            const imagePath = item.logo_backdrop_path || item.backdrop_path;

            posterDiv.innerHTML = `
                <a href="${detailUrl}">
                    <img src="https://image.tmdb.org/t/p/w500${imagePath}" alt="${escapeHTML(title)}">
                </a>
            `;
            resultsGrid.appendChild(posterDiv);
        });
    } else {
        resultsGrid.innerHTML = '<p>No content found matching your search.</p>';
    }
});
            }, 300);
        } else {
            // Arama kutusu boşsa, başlangıçtaki "Explore" içeriğini geri yükle
            resultsGrid.innerHTML = initialContent;
        }
    });

    function escapeHTML(str) {
        var p = document.createElement('p');
        p.appendChild(document.createTextNode(str));
        return p.innerHTML;
    }
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>