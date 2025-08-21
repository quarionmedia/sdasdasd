<?php require_once __DIR__ . '/partials/header.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<style>
    /* STREAM X - Netflix Tarzı Tam Genişlikli Tasarım */
    body {
    overflow-x: hidden;
    position: relative; /* Eklendi */
}
    .hero-section {
      height: 100vh;
      min-height: 600px;
      position: relative; /* Eklendi */
      z-index: 1; /* Düşük z-index veriyoruz */
     }
    .hero-slide {
     width: 100%;
     height: 100%; /* 110% yerine 100% */
     background-size: cover;
     background-position: center 20%;
     display: flex;
     align-items: center;
     position: relative;
    }
    .hero-slide::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, rgb(7, 7, 7) 10%, rgba(18, 18, 18, 0) 50%, rgb(7, 7, 7) 100%), 
                    linear-gradient(to top, rgb(7, 7, 7) 10%, rgba(18, 18, 18, 0) 50%);
    }
    .hero-content {
        z-index: 1;
        max-width: 50%;
        padding-left: 5%;
    }
    .hero-content .logo-image {
        max-width: 80%;
        height: auto;
        max-height: 200px;
        margin-bottom: 20px;
    }
    .hero-content h1 {
        font-size: 48px;
        margin: 0 0 10px 0;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
    }
    .hero-content p {
        font-size: 16px;
        line-height: 1.6;
        max-width: 500px;
    }
    .hero-buttons {
        margin-top: 20px;
    }
    .hero-buttons .btn {
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 20px;
        font-weight: bold;
        margin-right: 15px;
        font-size: 16px;
        display: inline-block;
    }
    .btn-watch {
        background-color: #42ca1a;
        color: #fff;
    }
    .btn-info {
        background-color: rgba(100, 100, 100, 0.7);
        color: #fff;
    }

    .content-section {
        padding: 0 5%; /* Yan boşluklar */
        margin-bottom: 20px; /* Hover kartları için daha fazla boşluk */
    }
    .section-header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-header h2 {
        margin: 0;
        font-size: 24px;
    }
    .section-header .view-all-link {
        color: #aaa;
        text-decoration: none;
        font-size: 14px;
    }
    .section-header .view-all-link:hover {
        color: #42ca1a;
    }

    /* Platformlar Bölümü Stilleri */
    .platforms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    position: relative; /* Eklendi */
    z-index: 3; /* Daha yüksek z-index */
    }
    .platform-card {
    display: block;
    background-color: #1a1a1a;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.2s, border-color 0.2s;
    border: 2px solid #333;
    position: relative; /* Eklendi */
    z-index: 4; /* En yüksek z-index */
    }

    .platform-card:hover {
        transform: scale(1.05);
        border-color: #42ca1a;
    }
    .platform-card img {
        width: 100%;
        height: 120px;
        object-fit: contain;
        padding: 5px 2px;
    }

    /* GÜNCELLENMİŞ: Carousel Stilleri + Netflix Hover - DÜZELTİLMİŞ */
    .carousel-container {
    position: relative;
    overflow: visible;
    padding: 5px 0;
    z-index: 5; /* Sabit z-index */
    }
    /* Bu kuralı ekleyin */
    .carousel-container:hover {
      z-index: 10;
    }
    .swiper-container {
        width: 100%;
        overflow: visible; /* Hover kartlarının görünmesi için */
        padding: 0; /* Alt kısımda hover kartı için boşluk */
    }
    
    .swiper-slide {
        width: 320px; /* Yatay poster genişliği */
        transition: transform 0.3s ease;
        position: relative;
    }
    
    /* Netflix Style Movie Card */
    .swiper-slide a {
        display: block;
        text-decoration: none;
        color: #fff;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .swiper-slide .backdrop-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        transition: transform 0.3s ease;
        border-radius: 8px;
    }
    
    /* Netflix Hover Preview Card - TAMAMEN DÜZELTİLMİŞ */
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
    z-index: 2000;
    pointer-events: none;
    overflow: hidden;
    border: 2px solid #444;
}
    
    /* Hover efekti - 1000ms gecikme ile daha stabil */
    .swiper-slide:hover .hover-preview {
        opacity: 1;
        visibility: visible;
        transform: scale(1) translateY(0);
        pointer-events: all;
        transition-delay: 0.4s; /* 1 saniye gecikme - refleks göstermez */
    }
    
    /* Hover preview üzerindeyken açık kalsın */
    .hover-preview:hover {
        opacity: 1 !important;
        visibility: visible !important;
        transform: scale(1) translateY(0) !important;
        pointer-events: all !important;
        transition-delay: 0s !important;
    }
    
    /* Ana slide hover */
    .swiper-slide:hover {
    z-index: 6; /* Daha düşük değer */
    transform: scale(1.05);
    }
    
    /* Hover preview kapatma gecikmesi */
    .swiper-slide:not(:hover) .hover-preview {
        transition-delay: 0.1s; /* Çıkışta kısa gecikme */
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
    
    .age-rating {
    background: rgba(255,255,255,0.2);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    color: #fff;
    font-weight: bold;
    border: 1px solid rgba(255,255,255,0.3);
}
    
    .seasons {
    background: rgba(255,255,255,0.2);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    color: #fff;
    font-weight: bold;
    border: 1px solid rgba(255,255,255,0.3);
}
    
    .quality {
    background: rgba(255,255,255,0.2);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    color: #fff;
    font-weight: bold;
    border: 1px solid rgba(255,255,255,0.3);
}
    
    .hover-genres {
    margin-bottom: 15px;
}
    
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
    
    .swiper-slide .logo-overlay {
        position: absolute;
        bottom: 15px;
        left: 15px;
        max-width: 60%;
        max-height: 60px;
    }

    .action-btn .fas {
    font-size: 16px;
}

    .action-btn.play-btn .fas {
    font-size: 18px;
    margin-left: 2px;
}

    
    /* GÜNCELLENMİŞ: Trendler Bölümü Stilleri */
    .trending-list {
        display: flex;
        gap: 20px;
        align-items: flex-end;
        overflow-x: auto;
        padding-bottom: 15px;
    }
    .trending-item {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    .trending-item .rank {
        font-size: 200px;
        font-weight: bold;
        line-height: 1;
        margin-right: -30px;
        z-index: 1;
        -webkit-text-stroke: 5px #555;
        color: #121212;
    }
    .trending-item img {
        width: 150px;
        height: 225px; /* Aspect ratio for posters */
        object-fit: cover;
        border-radius: 5px;
        transition: transform 0.2s;
    }
    .trending-item a:hover img {
        transform: scale(1.05);
    }

    /* YENİ: Swiper Navigasyon Okları */
    .swiper-button-next, .swiper-button-prev {
    color: #fff;
    background-color: rgba(0,0,0,0.5);
    width: 40px;
    height: 100%;
    top: 0;
    margin-top: 0;
    z-index: 8; /* Kontrollü z-index */
    border-radius: 5px;
    transform: translateY(0);
    opacity: 0;
    transition: opacity 0.2s;
}
    .carousel-container:hover .swiper-button-next,
    .carousel-container:hover .swiper-button-prev {
        opacity: 1;
    }
    .swiper-button-next:after, .swiper-button-prev:after {
        font-size: 20px;
        font-weight: bold;
    }
    .swiper-button-prev { left: 0; }
    .swiper-button-next { right: 0; }
    
    /* En Çok Arananlar Bölümü Stilleri */
    .top-searches-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .top-searches-list a {
        background-color: #374151;
        padding: 8px 15px;
        border-radius: 20px;
        text-decoration: none;
        color: #d1d5db;
        font-size: 14px;
        transition: background-color 0.2s;
    }
    .top-searches-list a:hover {
        background-color: #42ca1a;
        color: #fff;
    }

    /* Responsive düzeltmeler */
    @media (max-width: 768px) {
        .hover-preview {
            left: -20px;
            width: 320px;
        }
        
        .swiper-slide {
            width: 280px;
        }
        
        .content-section {
            padding: 0 3%;
        }
    }
</style>

<?php
// Slider bölümünü bul ve diğerlerinden ayır
$sliderSection = null;
$otherSections = [];
foreach ($sections as $section) {
    if ($section['type'] === 'slider' && !empty($section['content'])) {
        $sliderSection = $section;
    } else {
        $otherSections[] = $section;
    }
}

// Önce Hero/Slider bölümünü gösterelim (varsa)
if ($sliderSection):
?>
<div class="swiper-container hero-carousel hero-section">
    <div class="swiper-wrapper">
        <?php foreach ($sliderSection['content'] as $heroContent): ?>
            <div class="swiper-slide hero-slide" style="background-image: url('https://image.tmdb.org/t/p/original<?php echo $heroContent['backdrop_path']; ?>');">
                <div class="hero-content">
                    <?php if (!empty($heroContent['logo_path'])): ?>
                        <img class="logo-image" src="https://image.tmdb.org/t/p/w500<?php echo $heroContent['logo_path']; ?>" alt="<?php echo htmlspecialchars($heroContent['title'] ?? $heroContent['name']); ?>">
                    <?php else: ?>
                        <h1><?php echo htmlspecialchars($heroContent['title'] ?? $heroContent['name']); ?></h1>
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars(substr($heroContent['overview'], 0, 150)); ?>...</p>
                    <div class="hero-buttons">
    <?php
        $isSliderTvShow = isset($heroContent['first_air_date']);
        $watchUrl = '#'; // Default fallback

        if ($isSliderTvShow) {
            // Use the pre-built URL from the controller
            $watchUrl = $heroContent['watch_url'] ?? '#';
        } else {
            // Create the URL for movies
            $watchUrl = '/movie/' . ($heroContent['slug'] ?? '') . '/watch';
        }
        $detailUrl = ($isSliderTvShow ? '/tv-show/' : '/movie/') . ($heroContent['slug'] ?? '');
    ?>
    <a href="<?php echo $watchUrl; ?>" class="btn btn-watch"><i class="fas fa-play"></i> Watch Now</a>
    <a href="<?php echo $detailUrl; ?>" class="btn btn-info"><i class="fas fa-info"></i> More Info</a>
</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php foreach ($otherSections as $section): ?>
    <?php if ($section['type'] !== 'all_genres'): ?>
    <div class="content-section">
        
        <div class="section-header">
            <h2><?php echo htmlspecialchars($section['title']); ?></h2>
            <?php if ($section['type'] === 'latest_movies'): ?>
                <a href="/movies" class="view-all-link">View All</a>
            <?php elseif ($section['type'] === 'latest_tv_shows'): ?>
                 <a href="/tv-shows" class="view-all-link">View All</a>
            <?php endif; ?>
        </div>

        <?php if ($section['type'] === 'platforms_section' && !empty($section['content'])): ?>
            <div class="platforms-grid">
                <?php foreach($section['content'] as $platform): ?>
                    <a href="/platforms/<?php echo $platform['slug']; ?>" class="platform-card">
                        <img src="/assets/images/platforms/<?php echo $platform['logo_path']; ?>" alt="<?php echo htmlspecialchars($platform['name']); ?>">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php elseif ($section['type'] === 'trending' && !empty($section['content'])): ?>
            <div class="carousel-container">
                <div class="swiper-container trending-carousel">
                    <div class="swiper-wrapper">
                        <?php foreach ($section['content'] as $index => $item): ?>
                            <div class="swiper-slide">
                                <div class="trending-item">
    <span class="rank"><?php echo $index + 1; ?></span>
    
    <?php
        // Trend içeriğinin türünü ve linkini belirleyelim
        $isTrendingTvShow = isset($item['first_air_date']) || isset($item['name']);
        $trendingUrlType = $isTrendingTvShow ? 'tv-show' : 'movie';
        $trendingDetailUrl = "/{$trendingUrlType}/" . ($item['slug'] ?? '');
    ?>

    <a href="<?php echo $trendingDetailUrl; ?>">
        <img src="https://image.tmdb.org/t/p/w300<?php echo $item['poster_path']; ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?>">
    </a>
</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <?php elseif (($section['type'] === 'latest_movies' || $section['type'] === 'latest_tv_shows') && !empty($section['content'])): ?>
            <div class="carousel-container">
                <div class="swiper-container content-carousel">
                    <div class="swiper-wrapper">
                        <?php foreach($section['content'] as $item): ?>
                            <div class="swiper-slide">
                                <?php
        // İçeriğin Dizi mi Film mi olduğunu kontrol edip doğru linki oluşturalım
        $isTvShow = isset($item['first_air_date']) || isset($item['name']);
        $urlType = $isTvShow ? 'tv-show' : 'movie';
        $detailUrl = "/{$urlType}/" . ($item['slug'] ?? '');
    ?>
    <a href="<?php echo $detailUrl; ?>">
                                    <img class="backdrop-img" src="https://image.tmdb.org/t/p/w500<?php echo $item['logo_backdrop_path'] ?? $item['backdrop_path']; ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?>">
                                    
                                    <!-- Netflix Style Hover Preview -->
                                    <div class="hover-preview">
                                        <img class="preview-image" src="https://image.tmdb.org/t/p/w500<?php echo $item['logo_backdrop_path'] ?? $item['backdrop_path']; ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?>">
                                        <div class="hover-info">
                                            <div class="hover-title"><?php echo htmlspecialchars($item['title'] ?? $item['name']); ?></div>
                                            <div class="hover-meta">
                                                <span class="age-rating"><?php echo isset($item['adult']) && $item['adult'] ? '18+' : '13+'; ?></span>
                                                     <?php if (isset($item['first_air_date']) || isset($item['name'])): // Bu bir dizi mi kontrolü ?>
                                                     <?php if (isset($item['season_count']) && $item['season_count'] > 0): ?>
                                                       <span class="seasons"><?php echo $item['season_count']; ?> Season</span>
                                                     <?php else: ?>
                                                       <span class="seasons">TV Show</span>
                                                     <?php endif; ?>
                                                     <?php else: // Bu bir film ?>
                                                       <span class="seasons"><?php echo isset($item['runtime']) ? $item['runtime'] . 'min' : 'Movie'; ?></span>
                                                     <?php endif; ?>
                                            </div>
                                            <div class="hover-genres">
    <?php if (!empty($item['genres']) && is_array($item['genres'])): ?>
        <?php 
            // Gelen dizinin en fazla ilk 3 elemanını alalım
            $genres_to_show = array_slice($item['genres'], 0, 3);
        ?>
        <?php foreach ($genres_to_show as $genre): ?>
            <?php if (isset($genre['name'])): ?>
                <span class="genre-tag"><?php echo htmlspecialchars($genre['name']); ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
                                            <div class="hover-actions">
    <?php
        $isHoverTvShow = isset($item['first_air_date']) || isset($item['name']);
        $hoverWatchUrl = '#'; // Default fallback

        if ($isHoverTvShow) {
            // Use the pre-built URL from the controller
            $hoverWatchUrl = $item['watch_url'] ?? '#';
        } else {
            // Create the URL for movies
            $hoverWatchUrl = '/movie/' . ($item['slug'] ?? '') . '/watch';
        }
    ?>
    <a href="<?php echo $hoverWatchUrl; ?>" class="action-btn play-btn"><i class="fas fa-play"></i></a>
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
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php
$allGenresSection = array_filter($sections, fn($s) => $s['type'] === 'all_genres');
if (!empty($allGenresSection)):
    $genreContents = reset($allGenresSection)['content'];
    if (!empty($genreContents)):
?>
    <?php foreach ($genreContents as $genreData): ?>
        <div class="content-section">
            <div class="section-header">
                <h2><?php echo htmlspecialchars($genreData['genre_info']['name']); ?></h2>
                <a href="/genres/<?php echo $genreData['genre_info']['id']; ?>" class="view-all-link">View All</a>
            </div>
            <div class="carousel-container">
                <div class="swiper-container content-carousel">
                    <div class="swiper-wrapper">
                        <?php foreach($genreData['content'] as $item): ?>
                            <div class="swiper-slide">
                                 <?php
        // İçeriğin türünü ve linkini belirleyelim
        $isTvShow = isset($item['first_air_date']) || isset($item['name']);
        $urlType = $isTvShow ? 'tv-show' : 'movie';
        $detailUrl = "/{$urlType}/" . ($item['slug'] ?? '');
    ?>
    <a href="<?php echo $detailUrl; ?>">
                                    <img class="backdrop-img" src="https://image.tmdb.org/t/p/w500<?php echo $item['logo_backdrop_path'] ?? $item['backdrop_path']; ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?>">
                                    
                                    <!-- Netflix Style Hover Preview -->
                                    <div class="hover-preview">
                                        <img class="preview-image" src="https://image.tmdb.org/t/p/w500<?php echo $item['logo_backdrop_path'] ?? $item['backdrop_path']; ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?>">
                                        <div class="hover-info">
                                            <div class="hover-title"><?php echo htmlspecialchars($item['title'] ?? $item['name']); ?></div>
                                            <div class="hover-meta">
                                                <span class="age-rating"><?php echo isset($item['adult']) && $item['adult'] ? '18+' : '13+'; ?></span>
                                                     <?php if (isset($item['first_air_date']) || isset($item['name'])): // Bu bir dizi mi kontrolü ?>
                                                     <?php if (isset($item['season_count']) && $item['season_count'] > 0): ?>
                                                       <span class="seasons"><?php echo $item['season_count']; ?> Season</span>
                                                     <?php else: ?>
                                                       <span class="seasons">TV Show</span>
                                                     <?php endif; ?>
                                                     <?php else: // Bu bir film ?>
                                                       <span class="seasons"><?php echo isset($item['runtime']) ? $item['runtime'] . 'min' : 'Movie'; ?></span>
                                                     <?php endif; ?>
                                            </div>
                                            <div class="hover-genres">
    <?php
        // Türleri tutacak diziyi önceden hazırlayalım
        $genres_array = [];

        // Gelen veri bir METİN ise ("Aksiyon, Macera"), onu diziye çevirelim
        if (!empty($item['genres']) && is_string($item['genres'])) {
            $genres_array = explode(', ', $item['genres']);
        }

        // Artık elimizde ['Aksiyon', 'Macera'] gibi basit bir dizi var.
        if (!empty($genres_array)) {
            // En fazla 3 tür gösterelim
            $genres_to_show = array_slice($genres_array, 0, 3);

            foreach ($genres_to_show as $genre) {
                // DİKKAT: $genre['name'] DEĞİL! Doğrudan $genre kullanıyoruz.
                echo '<span class="genre-tag">' . htmlspecialchars(trim($genre)) . '</span>';
            }
        }
    ?>
</div>
                                            <div class="hover-actions">
    <?php
        $isHoverTvShow = isset($item['first_air_date']) || isset($item['name']);
        $hoverWatchUrl = '#'; // Default fallback

        if ($isHoverTvShow) {
            // Use the pre-built URL from the controller
            $hoverWatchUrl = $item['watch_url'] ?? '#';
        } else {
            // Create the URL for movies
            $hoverWatchUrl = '/movie/' . ($item['slug'] ?? '') . '/watch';
        }
    ?>
    <a href="<?php echo $hoverWatchUrl; ?>" class="action-btn play-btn"><i class="fas fa-play"></i></a>
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
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php 
    endif;
endif; 
?>
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // YENİ: Hero Carousel'i Başlat
        var heroSwiper = new Swiper('.hero-carousel', {
            loop: true,
            effect: 'fade', // Yumuşak geçiş efekti
            autoplay: {
                delay: 5000, // 5 saniyede bir değişir
                disableOnInteraction: false, // Kullanıcı dokunduktan sonra da devam etsin
            },
        });
        
        var carousels = document.querySelectorAll('.carousel-container');
        carousels.forEach(function(container) {
            var swiperContainer = container.querySelector('.swiper-container');
            var nextBtn = container.querySelector('.swiper-button-next');
            var prevBtn = container.querySelector('.swiper-button-prev');

            new Swiper(swiperContainer, {
                slidesPerView: 'auto',
                spaceBetween: 20,
                // freeMode: true, // Akıcı kaydırma için, istersen açabilirsin
                grabCursor: true,
                navigation: {
                    nextEl: nextBtn,
                    prevEl: prevBtn,
                },
            });
        });
    });
</script>
<div id="global-hover-preview"></div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>