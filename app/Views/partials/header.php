<?php
// Session başlatma (eğer zaten başlatılmadıysa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// helpers.php'deki view() fonksiyonu menüyü otomatik olarak çekecek
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title . ' | ' . htmlspecialchars(setting('site_name', 'MuvixTV')) : htmlspecialchars(setting('site_name', 'MuvixTV')); ?></title>
    <link rel="icon" type="image/png" href="/assets/images/<?php echo htmlspecialchars(setting('favicon_path', 'favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Genel Resetleme ve Temel Stiller */
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #070707; color: #d1d5db; }
        * { box-sizing: border-box; }
        a { color: #42ca1a; text-decoration: none; }
        a:hover { color: #86efac; }

        /* GÜNCELLENMİŞ: Ön Yüz Header Stilleri */
        .site-header {
            position: absolute; /* Yapışkan değil, sayfanın üstünde duracak */
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0,0,0,0) 100%); /* Üstte hafif bir gölge */
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 150%;
            max-width: 1700px;
            margin: 0 auto;
        }
        .header-left, .header-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .site-header .logo {
            font-size: 35px;
            font-weight: bold;
            text-decoration: none;
        }
        .main-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 25px;
        }
        .main-nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        
        /* YENİ: Tıklanabilir Arama Kutusu Stili */
        .search-box-link {
            display: flex;
            align-items: center;
            background: rgba(50, 50, 50, 0);
            border: 1px solid #555;
            border-radius: 20px;
            padding: 8px 15px;
            color: #aaa;
            text-decoration: none;
            transition: border-color 0.2s;
        }
        .search-box-link:hover {
            border-color: #42ca1a;
        }
        .search-box-link .fa-search {
            margin-right: 10px;
        }

        .header-actions .user-profile img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }
        .sign-in-btn {
    background: linear-gradient(135deg, #42ca1a 0%, #36a715 100%);
    color: #fff;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(66, 202, 26, 0.3);
    position: relative;
    overflow: hidden;
        }
        .sign-in-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
        }
        .sign-in-btn:hover::before {
    left: 100%;
        }
        .sign-in-btn:hover {
    background: linear-gradient(135deg, #36a715 0%, #2d8f12 100%);
    box-shadow: 0 6px 20px rgba(66, 202, 26, 0.4);
    transform: translateY(-2px);
    color: #fff;
        }
        .header-actions > a[href="/register"] {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    padding: 12px 20px;
    border-radius: 25px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.1);
        }
        .header-actions > a[href="/register"]:hover {
    color: #42ca1a;
    border-color: #42ca1a;
    background: rgba(66, 202, 26, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(66, 202, 26, 0.2);
        }
    </style>
</head>
<body>

<header class="site-header" id="site-header">
    <div class="header-content">
        <div class="header-left">
            <a href="/" class="logo">
                <?php if (!empty(setting('logo_path'))): ?>
                    <img src="/assets/images/<?php echo htmlspecialchars(setting('logo_path')); ?>" alt="<?php echo htmlspecialchars(setting('site_name')); ?>" style="max-height: 40px;">
                <?php else: ?>
                    <?php echo htmlspecialchars(setting('site_name', 'MuvixTV')); ?>
                <?php endif; ?>
            </a>
            <nav class="main-nav">
                <ul>
                    <?php if (!empty($menuItems)): ?>
                        <?php foreach ($menuItems as $item): ?>
                            <li><a href="<?php echo htmlspecialchars($item['url']); ?>"><?php echo htmlspecialchars($item['title']); ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a href="/">Home</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <div class="header-right">
            <div class="header-actions">
                <a href="/search" class="search-box-link">
                    <i class="fas fa-search"></i>
                    <span>Search Movies, Series...</span>
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/profile" class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_email'] ?? 'A'); ?>&background=random" alt="User Avatar">
                    </a>
                <?php else: ?>
                    <a href="/login" class="sign-in-btn">Sign In</a>
                    <a href="/register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>