<?php
// Tarayıcının admin sayfalarını cache'lemesini (önbelleğe almasını) engelle
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Session başlatma (eğer zaten başlatılmadıysa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title . ' | ' . htmlspecialchars(setting('site_name', 'MuvixTV')) . ' Admin' : htmlspecialchars(setting('site_name', 'MuvixTV')) . ' Admin'; ?></title>
    <link rel="icon" type="image/png" href="/assets/images/<?php echo htmlspecialchars(setting('favicon_path', 'favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/admin-style.css">
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="/admin"><?php echo htmlspecialchars(setting('site_name', 'MuvixTV')); ?></a>
        </div>
        <nav class="sidebar-nav">
            <p>Management</p>
            <ul>
                <li><a href="/admin">Dashboard</a></li>
                <li class="menu-item-has-children">
                    <a href="#">Movies</a>
                    <ul class="sub-menu">
                        <li><a href="/admin/movies">All Movies</a></li>
                        <li><a href="/admin/movies/add">Add Movie</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children">
                    <a href="#">TV Shows</a>
                    <ul class="sub-menu">
                        <li><a href="/admin/tv-shows">All TV Shows</a></li>
                        <li><a href="/admin/tv-shows/add">Add Tv Show</a></li>
                    </ul>
                </li>
                 <li><a href="/admin/genres">Genres</a></li>
                <li><a href="/admin/platforms">Content Platforms</a></li>
            </ul>
            <p>Community</p>
            <ul>
                <li><a href="/admin/users">Users</a></li>
                <li><a href="/admin/comments">Comments</a></li>
                <li><a href="/admin/reports">Reports</a></li>
                <li><a href="/admin/requests">Requests</a></li>
            </ul>
            <p>System</p>
            <ul>
                <li class="menu-item-has-children">
                    <a href="#">General Settings</a>
                    <ul class="sub-menu">
                        <li><a href="/admin/settings/general">Site Settings</a></li>
                        <li><a href="/admin/settings/api">API Settings</a></li>
                        <li><a href="/admin/settings/security">Security</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children">
                    <a href="#">Email Settings</a>
                    <ul class="sub-menu">
                        <li><a href="/admin/smtp-settings">Smtp Settings</a></li>
                        <li><a href="/admin/settings/email-templates">Email Templates</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children">
                    <a href="#">Ads Settings</a>
                    <ul class="sub-menu">
                        <li><a href="/admin/ads-settings">Main Ads</a></li>
                        <li><a href="/admin/video-ads">Video Ads</a></li>
                    </ul>
                </li>
                <li><a href="/admin/menu">Menu Manager</a></li>
                <li><a href="/admin/content-networks">Content Networks</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <div class="content-wrapper">
        <header class="top-header">
            <div class="header-left">
                <a href="/" target="_blank" class="header-dropdown-button">Back to site</a>
                <div class="header-dropdown" id="create-dropdown">
                    <button type="button" class="header-dropdown-button">Create ▼</button>
                    <div class="header-dropdown-content">
                        <a href="/admin/movies/add">Add Movie</a>
                        <a href="/admin/tv-shows/add">Add Web Series</a>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <a href="/search" class="search-icon">
                    <i class="fas fa-search"></i>
                </a>
                <i class="fa-solid fa-expand header-icon" title="Fullscreen"></i>
                <div class="header-dropdown" id="user-dropdown">
                    <button type="button" class="header-dropdown-button">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_email'] ?? 'A'); ?>&background=random" alt="User Avatar">
                    </button>
                    <div class="header-dropdown-content">
                        <a href="#">Profile</a>
                        <a href="/logout" style="color: #ff4d4d;">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">