<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        // --- Page Title Setup ---
        $title = 'Watching';
        $contentTitle = '';
        if ($type === 'movie') {
            $contentTitle = $content['title'];
        } else if ($type === 'episode') {
            $contentTitle = $content['tv_show']['title'] . ' | ' . $content['name'];
        }
        $title .= ': ' . htmlspecialchars($contentTitle);
    ?>
    <title><?php echo $title; ?></title>

    <link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- NETFLIX BASE STYLES --- */
        * { box-sizing: border-box; }
        html, body { 
            margin: 0; padding: 0; width: 100%; height: 100%; 
            background-color: #000; overflow: hidden; 
            font-family: Netflix Sans, Helvetica Neue, Segoe UI, Roboto, Ubuntu, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* --- VIDEO.JS NETFLIX OVERRIDES --- */
        #video-player { width: 100%; height: 100%; background-color: #000; }
        
        .back-button { 
            position: absolute; top: 25px; left: 30px; z-index: 9999; 
            background: rgba(42, 42, 42, 0.6); color: #fff; border-radius: 50%; 
            text-decoration: none; display: flex; align-items: center; justify-content: center; 
            width: 48px; height: 48px; font-size: 18px; 
            transition: all 0.2s ease; opacity: 0; backdrop-filter: blur(8px);
        }
        
        body:hover .back-button { opacity: 1; }
        .back-button:hover { background: rgba(42, 42, 42, 0.8); transform: scale(1.1); }

        /* Hide default big play button */
        .vjs-big-play-button { display: none !important; }

        /* --- NETFLIX PAUSE OVERLAY --- */
        .netflix-pause-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: none;
            z-index: 999;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .netflix-pause-overlay.show {
            opacity: 1;
        }

        .netflix-pause-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            text-align: center;
            max-width: 600px;
            padding: 0 40px;
        }

        .netflix-pause-title {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            line-height: 1.1;
        }

        .netflix-pause-season {
            font-size: 24px;
            font-weight: 600;
            color: #e50914;
            margin-bottom: 8px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .netflix-pause-episode {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .netflix-pause-description {
            font-size: 16px;
            line-height: 1.4;
            color: #ccc;
            margin-bottom: 40px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .netflix-pause-status {
            position: absolute;
            bottom: 40px;
            right: 40px;
            font-size: 34px;
            color: #999;
            background: rgba(0, 0, 0, 0);
            padding: 8px 16px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            text-shadow: none;
        }

        /* --- NETFLIX CONTROL BAR STRUCTURE --- */
        .video-js .vjs-control-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.1) 20%, rgba(0, 0, 0, 0.7) 60%, rgba(0, 0, 0, 0.9) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .video-js:hover .vjs-control-bar,
        .video-js.vjs-user-active .vjs-control-bar { 
            opacity: 1; 
        }

        /* --- NETFLIX PROGRESS BAR (FULL WIDTH TOP) --- */
        .vjs-progress-control {
            position: absolute !important;
            top: 0;
            left: 20px;
            right: 20px;
            width: 98% !important;
            height: 10px !important;
            margin: 0 !important;
            padding: 0 !important;
            order: 1;
        }
        
        .vjs-progress-holder {
            height: 5px !important;
            background: rgba(255, 255, 255, 0.3) !important;
            border-radius: 0 !important;
            margin: 10px 0 !important;
        }
        
        .vjs-play-progress {
            background: #e50914 !important;
            border-radius: 0 !important;
            height: 5px !important;
        }
        
        .vjs-play-progress:before {
            content: '';
            position: absolute;
            top: 50%;
            right: -6px;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            background: #e50914;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 10;
        }

        .vjs-play-progress::before,
        .vjs-play-progress::after {
            display: none !important;
        }
        
        .vjs-progress-control:hover .vjs-play-progress:before { 
            opacity: 1; 
        }
        
        .vjs-load-progress { 
            background: rgba(255, 0, 0, 0.4) !important; 
            height: 4px !important;
        }

        /* --- TIME DISPLAY (TOP RIGHT OVER PROGRESS) --- */
        .netflix-time-display {
            position: absolute;
            top: -30px;
            right: 0;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            background: rgba(0, 0, 0, 0.7);
            padding: 4px 8px;
            border-radius: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .vjs-progress-control:hover .netflix-time-display {
            opacity: 1;
        }

        /* --- MAIN CONTROLS ROW --- */
        .netflix-controls-row {
            display: flex;
            align-items: center;
            padding: 70px 15px;
            height: 120px;
            gap: 1px;
        }

        /* --- NETFLIX BUTTON STYLING --- */
        .vjs-control-bar .vjs-button {
            color: #fff !important;
            font-size: 38px !important;
            padding: 60px !important;
            transition: all 0.2s ease !important;
            border-radius: 4px !important;
            background: none !important;
            border: none !important;
            width: auto !important;
            height: auto !important;
            margin: 0 !important;
        }
        
        .vjs-control-bar .vjs-button:hover { 
            color: #e50914 !important;
            transform: scale(1.1) !important;
        }

        .vjs-play-control .vjs-icon-play:before,
        .vjs-play-control .vjs-icon-pause:before { 
            font-size: 50px !important; 
        }

        /* --- CUSTOM NETFLIX BUTTONS --- */
        .vjs-custom-button { 
            cursor: pointer !important;
            font-size: 38px !important;
            width: auto !important;
            padding: 30px !important;
            text-align: center !important;
            color: #fff !important;
            transition: all 0.2s ease !important;
            border-radius: 4px !important;
            background: none !important;
            border: none !important;
            margin: 0 !important;
        }
        
        .vjs-custom-button:hover { 
            color: #e50914 !important;
            transform: scale(1.1) !important;
        }

        .vjs-custom-button svg { 
            width: 50px !important;
            height: 50px !important;
            fill: currentColor !important;
        }

        /* --- NETFLIX TITLE STYLING --- */
        .vjs-custom-title { 
            flex-grow: 1;
            text-align: center;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            padding: 0 24px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* --- VOLUME PANEL NETFLIX STYLE --- */
        .vjs-volume-panel { 
            display: flex !important;
            align-items: center !important;
        }

        .vjs-volume-control { 
            width: 190px !important;
            height: 30px !important;
            margin-left: 1px !important;
            opacity: 0 !important;
            transition: opacity 0.3s ease !important;
            background: rgba(0, 0, 0, 0.8) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .vjs-volume-panel:hover .vjs-volume-control { 
            opacity: 1 !important;
        }

        .vjs-volume-bar { 
            background: rgba(255, 255, 255, 0.3) !important;
            width: 190px !important;
            height: 18px !important;
            border-radius: 3px !important;
            margin: 0 !important;
            position: relative !important;
        }

        .vjs-volume-level { 
            background: #e50914 !important;
            border-radius: 3px !important;
            height: 18px !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
        }

        .vjs-volume-level:before,
        .vjs-volume-level:after {
            display: none !important;
        }

        .vjs-volume-bar:before,
        .vjs-volume-bar:after {
            display: none !important;
        }

        /* --- HIDE DEFAULT TIME DISPLAYS --- */
        .vjs-current-time,
        .vjs-duration,
        .vjs-time-divider,
        .vjs-remaining-time {
            display: none !important;
        }

        /* --- NETFLIX EPISODES POPUP --- */
        .episodes-popup-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background-color: rgba(0,0,0,0.8); z-index: 10000; display: none; 
            justify-content: flex-end; backdrop-filter: blur(8px);
        }
        
        .episodes-popup-content { 
            width: 100%; max-width: 420px; height: 100%; 
            background: #181818; color: #fff; overflow-y: auto; 
            padding: 24px; animation: slideInFromRight 0.3s ease-out;
            border-left: 1px solid #333;
        }
        
        @keyframes slideInFromRight { 
            from { transform: translateX(100%); } 
            to { transform: translateX(0); } 
        }
        
        .episodes-popup-header { 
            display: flex; justify-content: space-between; 
            align-items: center; margin-bottom: 24px;
        }
        
        .episodes-popup-header h3 { margin: 0; font-size: 24px; font-weight: 700; }
        
        .popup-close-btn { 
            background: none; border: none; color: #fff; 
            font-size: 24px; cursor: pointer; padding: 8px; 
            border-radius: 4px; transition: background-color 0.2s ease;
        }
        
        .popup-close-btn:hover { background-color: rgba(255, 255, 255, 0.1); }
        
        .season-list select { 
            width: 100%; padding: 12px 16px; background: #333; 
            color: #fff; border: 1px solid #555; border-radius: 4px; 
            margin-bottom: 24px; font-size: 16px; font-family: inherit;
        }
        
        .episode-item a { 
            display: flex; align-items: center; gap: 16px; padding: 12px; 
            text-decoration: none; color: #ccc; border-radius: 4px; 
            margin-bottom: 8px; transition: background-color 0.2s ease;
        }
        
        .episode-item a:hover { background-color: #333; }
        .episode-item.active a { color: #e50914; background-color: rgba(229, 9, 20, 0.1); }
        
        .episode-item-thumbnail { 
            width: 120px; height: 68px; object-fit: cover; 
            border-radius: 4px; flex-shrink: 0; background-color: #333;
        }
        
        .episode-item-info .title { font-weight: 600; color: #fff; line-height: 1.3; }
        .episode-item-info .number { font-size: 12px; color: #999; margin-bottom: 4px; }

        /* --- NETFLIX SETTINGS MENU --- */
        .vjs-settings-menu {
            display: none; position: absolute; bottom: 140px; right: 48px;
            width: 320px; background: rgba(42, 42, 42, 0.95); 
            backdrop-filter: blur(16px); border-radius: 8px;
            padding: 16px; z-index: 1001; border: 1px solid #555;
        }
        
        .vjs-settings-menu > div { margin-bottom: 20px; }
        .vjs-settings-menu > div:last-child { margin-bottom: 0; }
        
        .vjs-settings-menu h4 { 
            margin: 0 0 12px 0; color: #fff; font-size: 14px; 
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .vjs-settings-menu ul { list-style: none; padding: 0; margin: 0; }
        
        .vjs-settings-menu li { 
            padding: 8px 12px; cursor: pointer; border-radius: 4px; 
            color: #ccc; font-size: 14px; transition: all 0.2s ease;
        }
        
        .vjs-settings-menu li:hover { 
            background-color: rgba(255, 255, 255, 0.1); color: #fff; 
        }
        
        .vjs-settings-menu li.active { 
            color: #e50914; background-color: rgba(229, 9, 20, 0.1); 
        }
        
        .vjs-settings-menu li.disabled { color: #666; cursor: default; }
        .vjs-settings-menu li.disabled:hover { background-color: transparent; color: #666; }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 768px) {
            .netflix-controls-row { padding: 16px 24px; }
            .vjs-custom-title { font-size: 14px; padding: 0 16px; }
            .episodes-popup-content { max-width: 100%; padding: 20px; }
            .vjs-settings-menu { right: 24px; width: 280px; }
            
            .netflix-pause-title { font-size: 32px; }
            .netflix-pause-season { font-size: 18px; }
            .netflix-pause-episode { font-size: 24px; }
            .netflix-pause-description { font-size: 14px; }
            .netflix-pause-status { bottom: 20px; right: 20px; font-size: 12px; }
        }
    </style>
</head>
<body>
    <?php
        $backLink = '/';
        if ($type === 'movie') {
            $backLink = '/movie/' . ($content['slug'] ?? '');
        } else if ($type === 'episode') {
            $backLink = '/tv-show/' . ($content['tv_show']['slug'] ?? '');
        }
    ?>
    <a href="<?php echo $backLink; ?>" class="back-button" title="Back to details"><i class="fas fa-arrow-left"></i></a>
    
    <!-- Netflix Pause Overlay -->
    <div id="netflix-pause-overlay" class="netflix-pause-overlay">
        <div class="netflix-pause-content">
            <?php if ($type === 'episode'): ?>
                <div class="netflix-pause-season"><?php echo htmlspecialchars($content['tv_show']['title']); ?></div>
                <div class="netflix-pause-episode"><?php echo htmlspecialchars($content['name']); ?></div>
                <div class="netflix-pause-title">
                    Season <?php echo isset($content['season_number']) ? $content['season_number'] : '1'; ?> : Episode <?php echo isset($content['episode_number']) ? $content['episode_number'] : '1'; ?>
                </div>
            <?php else: ?>
                <div class="netflix-pause-title"><?php echo htmlspecialchars($content['title']); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($content['overview'])): ?>
                <div class="netflix-pause-description">
                    <?php echo htmlspecialchars(mb_substr($content['overview'], 0, 200) . (mb_strlen($content['overview']) > 200 ? '...' : '')); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="netflix-pause-status">Paused</div>
    </div>
    
    <video-js id="video-player" class="vjs-big-play-centered" controls preload="auto" poster="<?php echo 'https://image.tmdb.org/t/p/original' . ($content['backdrop_path'] ?? ($content['tv_show']['backdrop_path'] ?? '')); ?>">
    </video-js>

    <?php if ($type === 'episode' && isset($allSeasons) && !empty($allSeasons)): ?>
    <div id="episodesPopup" class="episodes-popup-overlay">
        <div class="episodes-popup-content">
            <div class="episodes-popup-header">
                <h3>Seasons & Episodes</h3>
                <button id="popup-close-btn" class="popup-close-btn">&times;</button>
            </div>
            <div class="season-list">
                <select id="season-selector">
                    <?php foreach ($allSeasons as $season): ?>
                        <option value="season-list-<?php echo $season['id']; ?>" <?php echo $season['id'] == $content['season_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($season['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php foreach ($allSeasons as $season): ?>
            <div id="season-list-<?php echo $season['id']; ?>" class="episode-list-wrapper" style="<?php echo $season['id'] != $content['season_id'] ? 'display:none;' : ''; ?>">
                <?php foreach ($season['episodes'] as $ep): ?>
                <div class="episode-item <?php echo $ep['id'] == $content['id'] ? 'active' : ''; ?>">
                    <a href="/tv-show/<?php echo htmlspecialchars($content['tv_show']['slug']); ?>/<?php echo htmlspecialchars($season['season_number'] . 'x' . $ep['episode_number']); ?>">
                        <img class="episode-item-thumbnail" src="https://image.tmdb.org/t/p/w300<?php echo !empty($ep['still_path']) ? $ep['still_path'] : '/placeholder.jpg'; ?>" alt="Episode Thumbnail">
                        <div class="episode-item-info">
                            <div class="number">Episode <?php echo $ep['episode_number']; ?></div>
                            <div class="title"><?php echo htmlspecialchars($ep['name']); ?></div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div id="vjs-settings-menu" class="vjs-settings-menu">
        <div id="settings-audio-section">
            <h4>Audio</h4>
            <ul id="settings-audio-list"></ul>
        </div>
        <div id="settings-subtitle-section">
            <h4>Subtitles</h4>
            <ul id="settings-subtitle-list"></ul>
        </div>
    </div>

    <script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
    <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- PHP VARIABLES ---
            const links = <?php echo json_encode($content['links'] ?? []); ?>;
            const contentTitle = "<?php echo addslashes($contentTitle); ?>";
            const nextEpisodeUrl = "<?php echo $nextEpisodeUrl ?? ''; ?>";
            const isTvShow = <?php echo $type === 'episode' ? 'true' : 'false'; ?>;

            // --- PLAYER OPTIONS ---
            const playerOptions = {
                controls: true,
                autoplay: false,
                preload: 'auto',
                controlBar: {
                    children: [
                        'playToggle',
                        'volumePanel',
                        'progressControl',
                        'fullscreenToggle'
                    ]
                }
            };
            const player = videojs('video-player', playerOptions);

            // --- IMPROVED USER ACTIVITY DETECTION ---
            let userActivityTimeout;
            let pauseOverlayTimeout;
            let lastMousePosition = { x: 0, y: 0 };
            let isUserActive = true;
            
            const pauseOverlay = document.getElementById('netflix-pause-overlay');

            // Function to detect real user activity (mouse movement or keyboard input)
            function detectUserActivity(e) {
                // For mouse events, check if mouse actually moved
                if (e.type === 'mousemove') {
                    if (e.clientX === lastMousePosition.x && e.clientY === lastMousePosition.y) {
                        return; // Mouse didn't actually move
                    }
                    lastMousePosition.x = e.clientX;
                    lastMousePosition.y = e.clientY;
                }
                
                isUserActive = true;
                clearTimeout(userActivityTimeout);
                clearTimeout(pauseOverlayTimeout);
                hidePauseOverlay();
                
                // Set user as inactive after 3 seconds of no activity
                userActivityTimeout = setTimeout(() => {
                    isUserActive = false;
                    // If video is paused and user is inactive, show overlay after 3 more seconds
                    if (player.paused()) {
                        pauseOverlayTimeout = setTimeout(() => {
                            if (player.paused() && !isUserActive) {
                                showPauseOverlay();
                            }
                        }, 3000);
                    }
                }, 3000);
            }

            // Listen to all user input events
            const userInputEvents = [
                'mousedown', 'mousemove', 'mouseup', 'click', 
                'keydown', 'keyup', 'keypress',
                'touchstart', 'touchmove', 'touchend',
                'scroll', 'wheel'
            ];

            userInputEvents.forEach(eventType => {
                document.addEventListener(eventType, detectUserActivity, true);
            });

            function showPauseOverlay() {
                clearTimeout(pauseOverlayTimeout);
                pauseOverlay.style.display = 'block';
                setTimeout(() => {
                    pauseOverlay.classList.add('show');
                }, 50);
            }

            function hidePauseOverlay() {
                pauseOverlay.classList.remove('show');
                setTimeout(() => {
                    pauseOverlay.style.display = 'none';
                }, 400);
            }

            // Enhanced pause detection
            player.on('pause', function() {
                clearTimeout(pauseOverlayTimeout);
                
                // Only show overlay if user is inactive
                pauseOverlayTimeout = setTimeout(() => {
                    if (player.paused() && !isUserActive) {
                        showPauseOverlay();
                    }
                }, 3000);
            });

            player.on('play', function() {
                clearTimeout(pauseOverlayTimeout);
                hidePauseOverlay();
                isUserActive = true; // Reset user activity when playing
            });

            // Hide overlay when user becomes active again
            player.on('useractive', function() {
                if (player.paused()) {
                    hidePauseOverlay();
                }
            });

            // Initialize user activity detection
            detectUserActivity({ type: 'init', clientX: 0, clientY: 0 });

            // --- LOAD SOURCES AND SUBTITLES ---
            if (links && links.length > 0) {
                const playerSources = links.map(link => ({ 
                    src: link.url, 
                    type: link.source.toLowerCase().includes('m3u8') ? 'application/x-mpegURL' : 'video/mp4', 
                    label: link.quality || 'Auto' 
                }));
                player.src(playerSources);
                
                links.forEach(link => {
                    if (link.subtitles && link.subtitles.length > 0) {
                        link.subtitles.forEach(sub => {
                            player.addRemoteTextTrack({
                                kind: 'captions',
                                src: sub.url,
                                srclang: sub.language.substring(0, 2).toLowerCase(),
                                label: sub.language
                            }, false);
                        });
                    }
                });
            }

            // --- RESTRUCTURE CONTROLS FOR NETFLIX LAYOUT ---
            player.ready(function() {
                const controlBar = this.controlBar;
                
                // Create time display for progress bar
                const progressControl = controlBar.getChild('progressControl');
                const timeDisplay = document.createElement('div');
                timeDisplay.className = 'netflix-time-display';
                progressControl.el().appendChild(timeDisplay);

                // Update time display
                const updateTimeDisplay = () => {
                    const current = Math.floor(player.currentTime());
                    const duration = Math.floor(player.duration()) || 0;
                    const currentMin = Math.floor(current / 60);
                    const currentSec = current % 60;
                    const durationMin = Math.floor(duration / 60);
                    const durationSec = duration % 60;
                    
                    timeDisplay.textContent = `${currentMin}:${currentSec.toString().padStart(2, '0')} / ${durationMin}:${durationSec.toString().padStart(2, '0')}`;
                };

                player.on('timeupdate', updateTimeDisplay);
                player.on('durationchange', updateTimeDisplay);

                // Create main controls row container
                const controlsRow = document.createElement('div');
                controlsRow.className = 'netflix-controls-row';
                controlBar.el().appendChild(controlsRow);

                // Move existing controls to the row
                const playToggle = controlBar.getChild('playToggle');
                const volumePanel = controlBar.getChild('volumePanel');
                const fullscreenToggle = controlBar.getChild('fullscreenToggle');

                controlsRow.appendChild(playToggle.el());

                // Add Netflix Rewind Button (10s Back)
                const rewindButton = document.createElement('button');
                rewindButton.className = 'vjs-custom-button';
                rewindButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0198 2.04817C13.3222 1.8214 15.6321 2.39998 17.5557 3.68532C19.4794 4.97066 20.8978 6.88324 21.5694 9.09717C22.241 11.3111 22.1242 13.6894 21.2388 15.8269C20.3534 17.9643 18.7543 19.7286 16.714 20.8192C14.6736 21.9098 12.3182 22.2592 10.0491 21.8079C7.77999 21.3565 5.73759 20.1323 4.26989 18.3439C2.80219 16.5555 2 14.3136 2 12L0 12C-2.74181e-06 14.7763 0.962627 17.4666 2.72387 19.6127C4.48511 21.7588 6.93599 23.2278 9.65891 23.7694C12.3818 24.3111 15.2083 23.8918 17.6568 22.5831C20.1052 21.2744 22.0241 19.1572 23.0866 16.5922C24.149 14.0273 24.2892 11.1733 23.4833 8.51661C22.6774 5.85989 20.9752 3.56479 18.6668 2.02238C16.3585 0.479973 13.5867 -0.214321 10.8238 0.0578004C8.71195 0.265799 6.70517 1.02858 5 2.2532V1H3V5C3 5.55228 3.44772 6 4 6H8V4H5.99999C7.45608 2.90793 9.19066 2.22833 11.0198 2.04817ZM2 4V7H5V9H1C0.447715 9 0 8.55228 0 8V4H2ZM14.125 16C13.5466 16 13.0389 15.8586 12.6018 15.5758C12.1713 15.2865 11.8385 14.8815 11.6031 14.3609C11.3677 13.8338 11.25 13.2135 11.25 12.5C11.25 11.7929 11.3677 11.1758 11.6031 10.6488C11.8385 10.1217 12.1713 9.71671 12.6018 9.43388C13.0389 9.14463 13.5466 9 14.125 9C14.7034 9 15.2077 9.14463 15.6382 9.43388C16.0753 9.71671 16.4116 10.1217 16.6469 10.6488C16.8823 11.1758 17 11.7929 17 12.5C17 13.2135 16.8823 13.8338 16.6469 14.3609C16.4116 14.8815 16.0753 15.2865 15.6382 15.5758C15.2077 15.8586 14.7034 16 14.125 16ZM14.125 14.6501C14.5151 14.6501 14.8211 14.4637 15.043 14.0909C15.2649 13.7117 15.3759 13.1814 15.3759 12.5C15.3759 11.8186 15.2649 11.2916 15.043 10.9187C14.8211 10.5395 14.5151 10.3499 14.125 10.3499C13.7349 10.3499 13.4289 10.5395 13.207 10.9187C12.9851 11.2916 12.8741 11.8186 12.8741 12.5C12.8741 13.1814 12.9851 13.7117 13.207 14.0909C13.4289 14.4637 13.7349 14.6501 14.125 14.6501ZM8.60395 15.8554V10.7163L7 11.1405V9.81956L10.1978 9.01928V15.8554H8.60395Z" fill="currentColor"></path>
                    </svg>
                `;
                rewindButton.addEventListener('click', () => { 
                    player.currentTime(player.currentTime() - 10);
                    hidePauseOverlay();
                });
                controlsRow.appendChild(rewindButton);

                // Add Netflix Forward Button (10s Forward)
                const forwardButton = document.createElement('button');
                forwardButton.className = 'vjs-custom-button';
                forwardButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.68532C8.36795 2.39998 10.6778 1.8214 12.9802 2.04817C14.8093 2.22833 16.5439 2.90793 18 4H16V6H20C20.5523 6 21 5.55229 21 5V1H19V2.2532C17.2948 1.02859 15.2881 0.2658 13.1762 0.057802C10.4133 -0.214319 7.64154 0.479975 5.33316 2.02238C3.02478 3.56479 1.32262 5.85989 0.516718 8.51661C-0.289188 11.1733 -0.148981 14.0273 0.913451 16.5922C1.97588 19.1572 3.8948 21.2744 6.34325 22.5831C8.79169 23.8918 11.6182 24.3111 14.3411 23.7694C17.064 23.2278 19.5149 21.7588 21.2761 19.6127C23.0374 17.4666 24 14.7763 24 12L22 12C22 14.3136 21.1978 16.5555 19.7301 18.3439C18.2624 20.1323 16.22 21.3565 13.9509 21.8079C11.6818 22.2592 9.32641 21.9098 7.28604 20.8192C5.24567 19.7286 3.64657 17.9643 2.76121 15.8269C1.87585 13.6894 1.75901 11.3111 2.4306 9.09718C3.10219 6.88324 4.52065 4.97067 6.4443 3.68532ZM22 4V7H19V9H23C23.5523 9 24 8.55229 24 8V4H22ZM12.6018 15.5758C13.0389 15.8586 13.5466 16 14.125 16C14.7034 16 15.2078 15.8586 15.6382 15.5758C16.0753 15.2865 16.4116 14.8815 16.6469 14.3609C16.8823 13.8338 17 13.2135 17 12.5C17 11.7929 16.8823 11.1759 16.6469 10.6488C16.4116 10.1217 16.0753 9.71671 15.6382 9.43389C15.2078 9.14463 14.7034 9 14.125 9C13.5466 9 13.0389 9.14463 12.6018 9.43389C12.1713 9.71671 11.8385 10.1217 11.6031 10.6488C11.3677 11.1759 11.25 11.7929 11.25 12.5C11.25 13.2135 11.3677 13.8338 11.6031 14.3609C11.8385 14.8815 12.1713 15.2865 12.6018 15.5758ZM15.043 14.0909C14.8211 14.4637 14.5151 14.6501 14.125 14.6501C13.7349 14.6501 13.429 14.4637 13.207 14.0909C12.9851 13.7117 12.8741 13.1814 12.8741 12.5C12.8741 11.8186 12.9851 11.2916 13.207 10.9187C13.429 10.5395 13.7349 10.3499 14.125 10.3499C14.5151 10.3499 14.8211 10.5395 15.043 10.9187C15.2649 11.2916 15.3759 11.8186 15.3759 12.5C15.3759 13.1814 15.2649 13.7117 15.043 14.0909ZM8.60395 10.7163V15.8554H10.1978V9.01929L7 9.81956V11.1405L8.60395 10.7163Z" fill="currentColor"></path>
                    </svg>
                `;
                forwardButton.addEventListener('click', () => { 
                    player.currentTime(player.currentTime() + 10);
                    hidePauseOverlay();
                });
                controlsRow.appendChild(forwardButton);

                controlsRow.appendChild(volumePanel.el());

                // Add title in center
                const titleElement = document.createElement('div');
                titleElement.className = 'vjs-custom-title';
                titleElement.textContent = contentTitle;
                controlsRow.appendChild(titleElement);

                // Netflix TV Show Buttons
                if (isTvShow) {
                    // Episodes List Button
                    const epListButton = document.createElement('button');
                    epListButton.className = 'vjs-custom-button';
                    epListButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5H22V13H24V5C24 3.89543 23.1046 3 22 3H8V5ZM18 9H4V7H18C19.1046 7 20 7.89543 20 9V17H18V9ZM0 13C0 11.8954 0.895431 11 2 11H14C15.1046 11 16 11.8954 16 13V19C16 20.1046 15.1046 21 14 21H2C0.895431 21 0 20.1046 0 19V13ZM14 19V13H2V19H14Z" fill="currentColor"></path>
                        </svg>
                    `;
                    epListButton.addEventListener('click', () => { 
                        document.getElementById('episodesPopup').style.display = 'flex'; 
                        player.pause(); 
                    });
                    controlsRow.appendChild(epListButton);

                    // Next Episode Button
                    if (nextEpisodeUrl) {
                        const nextEpButton = document.createElement('button');
                        nextEpButton.className = 'vjs-custom-button';
                        nextEpButton.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M22 3H20V21H22V3ZM4.28615 3.61729C3.28674 3.00228 2 3.7213 2 4.89478V19.1052C2 20.2787 3.28674 20.9977 4.28615 20.3827L15.8321 13.2775C16.7839 12.6918 16.7839 11.3082 15.8321 10.7225L4.28615 3.61729ZM4 18.2104V5.78956L14.092 12L4 18.2104Z" fill="currentColor"></path>
                            </svg>
                        `;
                        nextEpButton.addEventListener('click', () => { window.location.href = nextEpisodeUrl; });
                        controlsRow.appendChild(nextEpButton);
                    }
                }

                // Netflix Settings Button
                const settingsButton = document.createElement('button');
                settingsButton.className = 'vjs-custom-button';
                settingsButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1 3C1 2.44772 1.44772 2 2 2H22C22.5523 2 23 2.44772 23 3V17C23 17.5523 22.5523 18 22 18H19V21C19 21.3688 18.797 21.7077 18.4719 21.8817C18.1467 22.0557 17.7522 22.0366 17.4453 21.8321L11.6972 18H2C1.44772 18 1 17.5523 1 17V3ZM3 4V16H12H12.3028L12.5547 16.1679L17 19.1315V17V16H18H21V4H3ZM10 9L5 9V7L10 7V9ZM19 11H14V13H19V11ZM12 13L5 13V11L12 11V13ZM19 7H12V9H19V7Z" fill="currentColor"></path>
                    </svg>
                `;
                settingsButton.addEventListener('click', (e) => {
                    const menu = document.getElementById('vjs-settings-menu');
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                    e.stopPropagation();
                });
                controlsRow.appendChild(settingsButton);

                controlsRow.appendChild(fullscreenToggle.el());
            });

            // --- SETTINGS MENU MANAGEMENT ---
            const settingsMenu = document.getElementById('vjs-settings-menu');
            
            function updateSettingsMenu() {
                const audioList = document.getElementById('settings-audio-list');
                const subtitleList = document.getElementById('settings-subtitle-list');
                const audioTracks = player.audioTracks();
                const textTracks = player.textTracks();

                // AUDIO TRACKS
                audioList.innerHTML = '';
                if (audioTracks.length > 1) {
                    for (let i = 0; i < audioTracks.length; i++) {
                        const track = audioTracks[i];
                        const li = document.createElement('li');
                        li.textContent = track.label || track.language || `Audio ${i + 1}`;
                        if (track.enabled) li.classList.add('active');
                        li.onclick = () => {
                            for (let j = 0; j < audioTracks.length; j++) audioTracks[j].enabled = false;
                            track.enabled = true;
                            updateSettingsMenu();
                        };
                        audioList.appendChild(li);
                    }
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'Original';
                    li.classList.add('active', 'disabled');
                    audioList.appendChild(li);
                }

                // SUBTITLE TRACKS
                subtitleList.innerHTML = '';
                let hasSubtitles = false;
                const offLi = document.createElement('li');
                offLi.textContent = 'Off';
                offLi.onclick = () => { 
                    for (let i = 0; i < textTracks.length; i++) textTracks[i].mode = 'disabled'; 
                    updateSettingsMenu(); 
                };
                subtitleList.appendChild(offLi);
                
                for (let i = 0; i < textTracks.length; i++) {
                    const track = textTracks[i];
                    if (track.kind === 'captions' || track.kind === 'subtitles') {
                        hasSubtitles = true;
                        const li = document.createElement('li');
                        li.textContent = track.label || track.language;
                        if (track.mode === 'showing') li.classList.add('active');
                        li.onclick = () => {
                            for (let j = 0; j < textTracks.length; j++) textTracks[j].mode = 'disabled';
                            track.mode = 'showing';
                            updateSettingsMenu();
                        };
                        subtitleList.appendChild(li);
                    }
                }
                
                if (!hasSubtitles) {
                    const li = document.createElement('li');
                    li.textContent = 'None';
                    li.classList.add('disabled');
                    subtitleList.appendChild(li);
                }

                const anySubActive = Array.from(textTracks).some(t => t.mode === 'showing');
                if (!anySubActive) offLi.classList.add('active');
            }
            
            player.on('loadedmetadata', updateSettingsMenu);
            player.textTracks().on('change', updateSettingsMenu);
            
            document.addEventListener('click', (e) => {
                if (!settingsMenu.contains(e.target) && !e.target.closest('.vjs-custom-button')) {
                    settingsMenu.style.display = 'none';
                }
            });

            // --- EPISODE LIST POPUP MANAGEMENT ---
            const popup = document.getElementById('episodesPopup');
            if (popup) {
                const seasonSelector = document.getElementById('season-selector');
                const closeBtn = document.getElementById('popup-close-btn');

                const closeEpisodesPopup = () => { 
                    popup.style.display = 'none'; 
                    if (!player.paused()) player.play(); 
                };
                
                popup.addEventListener('click', (e) => { 
                    if (e.target === popup) closeEpisodesPopup(); 
                });
                
                if(closeBtn) {
                    closeBtn.addEventListener('click', closeEpisodesPopup);
                }

                if(seasonSelector){
                    seasonSelector.addEventListener('change', function() {
                        document.querySelectorAll('.episode-list-wrapper').forEach(list => list.style.display = 'none');
                        document.getElementById(this.value).style.display = 'block';
                    });
                }
            }

            // --- ENHANCED NETFLIX KEYBOARD SHORTCUTS ---
            document.addEventListener('keydown', (e) => {
                // Detect user activity on any keydown
                detectUserActivity(e);
                
                switch(e.code) {
                    case 'Space':
                        e.preventDefault();
                        if (player.paused()) {
                            player.play();
                            hidePauseOverlay();
                        } else {
                            player.pause();
                        }
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        player.currentTime(player.currentTime() - 10);
                        hidePauseOverlay();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        player.currentTime(player.currentTime() + 10);
                        hidePauseOverlay();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        player.volume(Math.min(1, player.volume() + 0.1));
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        player.volume(Math.max(0, player.volume() - 0.1));
                        break;
                    case 'KeyM':
                        e.preventDefault();
                        player.muted(!player.muted());
                        break;
                    case 'KeyF':
                        e.preventDefault();
                        if (player.isFullscreen()) {
                            player.exitFullscreen();
                        } else {
                            player.requestFullscreen();
                        }
                        break;
                    case 'Escape':
                        e.preventDefault();
                        hidePauseOverlay();
                        break;
                }
            });

            // Hide overlay when clicking anywhere on it
            pauseOverlay.addEventListener('click', () => {
                hidePauseOverlay();
                detectUserActivity({ type: 'click', clientX: 0, clientY: 0 });
            });

            // Also detect activity on video interactions
            player.el().addEventListener('click', () => {
                detectUserActivity({ type: 'click', clientX: 0, clientY: 0 });
            });

            // Detect activity when controls are used
            player.on('volumechange', () => {
                detectUserActivity({ type: 'volumechange', clientX: 0, clientY: 0 });
            });

            player.on('seeked', () => {
                detectUserActivity({ type: 'seeked', clientX: 0, clientY: 0 });
            });

            player.on('fullscreenchange', () => {
                detectUserActivity({ type: 'fullscreenchange', clientX: 0, clientY: 0 });
            });
        });
    </script>
</body>
</html>