<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .platform-hero-section {
        height: 60vh;
        min-height: 400px;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        text-align: center;
    }
    .platform-hero-section::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    /* Yumuşak geçiş için gradient ayarları */
    background: linear-gradient(to top, 
    rgba(7, 7, 7, 1) 0%,
    rgba(7, 7, 7, 0.71) 40%,
    rgba(7, 7, 7, 0.38) 70%,
    rgba(7, 7, 7, 1) 100%
);
}
    .platform-hero-content {
        z-index: 1;
        padding: 20px;
    }
    .platform-hero-content img {
        max-width: 350px;
        max-height: 180px;
        display: block;
    }
    .content-grid {
        padding: 0 5%;
        margin-top: 40px;
    }
    .filter-bar {
        margin-bottom: 30px;
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }
    .filter-bar select {
        background-color: #333;
        border: 1px solid #555;
        color: #fff;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 14px;
    }
    .filter-bar .button {
        background-color: #42ca1a;
        color: #fff;
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    /* GÜNCELLENMİŞ: Izgara (Grid) Stilleri */
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        padding-bottom: 150px; 
    }

    /* Netflix Hover Animasyon Stilleri */
    .poster-container {
        position: relative;
        z-index: 5;
    }
    .poster-container:hover {
        z-index: 10;
    }
    .poster-container a {
        display: block;
        text-decoration: none;
        color: #fff;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .poster-container .backdrop-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        transition: transform 0.3s ease;
        border-radius: 8px;
    }
    .hover-preview {
        position: absolute;
        top: -30px;
        left: -50px;
        width: 420px;
        background: #141414;
        border-radius: 8px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.8);
        opacity: 0;
        visibility: hidden;
        transform: scale(0.8) translateY(20px);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        z-index: 20;
        pointer-events: none;
        overflow: hidden;
        border: 2px solid #444;
    }
    .poster-container:hover .hover-preview {
        opacity: 1;
        visibility: visible;
        transform: scale(1) translateY(0);
        pointer-events: all;
        transition-delay: 0.4s;
    }
    .hover-preview:hover {
        opacity: 1 !important;
        visibility: visible !important;
        transform: scale(1) translateY(0) !important;
        pointer-events: all !important;
        transition-delay: 0s !important;
    }
    .poster-container:hover {
        transform: scale(1.05);
    }
    .hover-preview .preview-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        position: relative;
    }
    .hover-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        padding: 60px 20px 20px 20px;
        color: #fff;
    }
    .hover-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 12px;
        line-height: 1.2;
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
    }
    .hover-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        font-size: 14px;
    }
    .age-rating, .seasons, .quality {
        background: rgba(255,255,255,0.2);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        color: #fff;
        font-weight: bold;
        border: 1px solid rgba(255,255,255,0.3);
    }
    .hover-genres { margin-bottom: 15px; }
    .genre-tag {
        display: inline-block;
        background: rgba(255,255,255,0.1);
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 12px;
        margin-right: 6px;
        margin-bottom: 4px;
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .hover-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-start;
    }
    .action-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.5);
        background: rgba(42, 42, 42, 0.8);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 18px;
        backdrop-filter: blur(5px);
    }
    .action-btn:hover {
        background: rgba(255, 255, 255, 0.9);
        color: #000;
        transform: scale(1.1);
        border-color: #fff;
    }
    .action-btn.play-btn {
        background: rgba(255, 255, 255, 0.9);
        color: #000;
        border-color: #fff;
        font-weight: bold;
    }
    .action-btn.play-btn:hover {
        background: #fff;
        transform: scale(1.15);
    }
    .action-btn .fas {
        font-size: 16px;
    }
    .action-btn.play-btn .fas {
        font-size: 18px;
        margin-left: 2px;
    }
</style>

<div class="platform-hero-section" style="background-image: url('/assets/images/platforms/<?php echo $platform['background_path']; ?>');">
    <div class="platform-hero-content">
        <img src="/assets/images/platforms/<?php echo $platform['logo_path']; ?>" alt="<?php echo htmlspecialchars($platform['name']); ?>">
    </div>
</div>

<div class="content-grid">
    
    <div class="filter-bar">
        </div>

    <div class="results-grid">
        <?php foreach (array_merge($movies, $tvShows) as $item): ?>
            <div class="poster-container">
                <a href="<?php echo ($item['content_type'] === 'movie') ? '/movie/' : '/tv-show/'; ?><?php echo $item['slug']; ?>">
                    <img class="backdrop-img" src="https://image.tmdb.org/t/p/w500<?php echo $item['logo_backdrop_path'] ?? $item['backdrop_path']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    
                    <div class="hover-preview">
                        <img class="preview-image" src="https://image.tmdb.org/t/p/w500<?php echo $item['logo_backdrop_path'] ?? $item['backdrop_path']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <div class="hover-info">
                            <div class="hover-title"><?php echo htmlspecialchars($item['title']); ?></div>
                            <div class="hover-meta">
    <span class="age-rating"><?php echo !empty($item['certification']) ? htmlspecialchars($item['certification']) : '13+'; ?></span>
    <?php if (isset($item['runtime']) && $item['runtime']): // Film ise süresini göster ?>
        <span><?php echo htmlspecialchars($item['runtime']); ?>m</span>
    <?php elseif (isset($item['number_of_seasons'])): // Dizi ise sezon sayısını göster ?>
        <span class="seasons"><?php echo htmlspecialchars($item['number_of_seasons']); ?> Sezon</span>
    <?php endif; ?>
</div>
<div class="hover-genres">
    <?php if (!empty($item['genres'])): ?>
        <?php foreach (array_slice($item['genres'], 0, 3) as $genre): ?>
            <span class="genre-tag"><?php echo htmlspecialchars($genre['name']); ?></span>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
                            <div class="hover-actions">
    <?php
        $watchUrl = '#'; // Default fallback
        if ($item['content_type'] === 'tv_show') {
            // Use the pre-built URL from the controller
            $watchUrl = $item['watch_url'] ?? '#';
        } else {
            // Create the URL for movies
            $watchUrl = '/movie/' . ($item['slug'] ?? '') . '/watch';
        }
    ?>
    <a href="<?php echo $watchUrl; ?>" class="action-btn play-btn"><i class="fas fa-play"></i></a>
    <a href="#" class="action-btn"><i class="fas fa-plus"></i></a>
    <a href="#" class="action-btn"><i class="fas fa-thumbs-up"></i></a>
    <a href="#" class="action-btn"><i class="fas fa-chevron-down"></i></a>
</div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?php require_once __DIR__ . '/partials/footer.php'; ?>