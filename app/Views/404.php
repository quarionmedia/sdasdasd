<style>
    * {
        box-sizing: border-box;
    }

    /* 404 Page - Modern Dark Theme */
    .error-page-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
        background: #000000;
        background-image: 
            radial-gradient(circle at 25% 25%, #1a1a1a 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, #2a2a2a 0%, transparent 50%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    /* Animated background elements */
    .bg-decoration {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .floating-element {
        position: absolute;
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, rgba(46, 204, 113, 0.1), rgba(118, 118, 118, 0.1));
        border-radius: 50%;
        animation: float-around 20s infinite ease-in-out;
    }

    .floating-element:nth-child(1) {
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
        top: 70%;
        right: 10%;
        animation-delay: 7s;
        width: 150px;
        height: 150px;
    }

    .floating-element:nth-child(3) {
        bottom: 20%;
        left: 60%;
        animation-delay: 14s;
        width: 80px;
        height: 80px;
    }

    @keyframes float-around {
        0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
        25% { transform: translateY(-20px) translateX(10px) rotate(90deg); }
        50% { transform: translateY(20px) translateX(-10px) rotate(180deg); }
        75% { transform: translateY(-10px) translateX(20px) rotate(270deg); }
    }

    /* Main content */
    .error-content {
        text-align: center;
        z-index: 2;
        position: relative;
        max-width: 800px;
        width: 100%;
    }

    /* 404 Text with modern glow effect */
    .error-number {
        font-size: clamp(80px, 15vw, 200px);
        font-weight: 900;
        background: linear-gradient(135deg, #2ecc71, #ffffff, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        text-shadow: 0 0 50px rgba(46, 204, 113, 0.3);
        animation: glow-pulse 3s ease-in-out infinite alternate;
    }

    @keyframes glow-pulse {
        from { filter: brightness(1) drop-shadow(0 0 20px rgba(46, 204, 113, 0.4)); }
        to { filter: brightness(1.2) drop-shadow(0 0 30px rgba(46, 204, 113, 0.6)); }
    }

    /* Error message card */
    .error-card {
        background: rgba(26, 26, 26, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(118, 118, 118, 0.3);
        border-radius: 24px;
        padding: 40px;
        margin: 40px 0;
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.5),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    }

    .error-title {
        font-size: clamp(24px, 4vw, 36px);
        font-weight: 700;
        color: #f8fafc;
        margin: 0 0 16px 0;
        line-height: 1.2;
    }

    .error-subtitle {
        font-size: clamp(16px, 3vw, 20px);
        color: #a0a0a0;
        margin: 0 0 32px 0;
        line-height: 1.5;
        font-weight: 300;
    }

    /* Search section */
    .search-section {
        margin: 32px 0;
    }

    .search-form {
        position: relative;
        max-width: 500px;
        margin: 0 auto;
    }

    .search-input {
        width: 100%;
        padding: 16px 60px 16px 24px;
        background: rgba(40, 40, 40, 0.8);
        border: 2px solid rgba(118, 118, 118, 0.3);
        border-radius: 16px;
        color: #ffffff;
        font-size: 16px;
        font-weight: 400;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: #2ecc71;
        background: rgba(40, 40, 40, 1);
        box-shadow: 0 0 0 4px rgba(46, 204, 113, 0.2);
    }

    .search-input::placeholder {
        color: #888888;
    }

    .search-button {
        position: absolute;
        right: 4px;
        top: 4px;
        bottom: 4px;
        width: 48px;
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-button:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
    }

    /* Navigation buttons */
    .nav-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    .nav-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 16px 24px;
        background: rgba(40, 40, 40, 0.8);
        border: 1px solid rgba(118, 118, 118, 0.3);
        border-radius: 16px;
        color: #e0e0e0;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .nav-btn:hover {
        transform: translateY(-2px);
        background: rgba(60, 60, 60, 0.9);
        border-color: #2ecc71;
        color: #ffffff;
        text-decoration: none;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
    }

    .nav-btn:hover::before {
        left: 100%;
    }

    .nav-btn.primary {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        border-color: transparent;
        color: #ffffff;
    }

    .nav-btn.primary:hover {
        background: linear-gradient(135deg, #27ae60, #229954);
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(46, 204, 113, 0.4);
    }

    /* Icon styles */
    .nav-icon {
        width: 18px;
        height: 18px;
        opacity: 0.8;
    }

    .nav-btn:hover .nav-icon {
        opacity: 1;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .error-page-container {
            padding: 20px 15px;
        }
        
        .error-card {
            padding: 30px 20px;
            margin: 30px 0;
            border-radius: 20px;
        }
        
        .nav-buttons {
            grid-template-columns: 1fr;
            max-width: 300px;
        }
        
        .search-input {
            padding: 14px 55px 14px 20px;
            font-size: 15px;
        }
        
        .search-button {
            width: 44px;
        }
    }

    @media (max-width: 480px) {
        .error-card {
            padding: 25px 15px;
            margin: 20px 0;
        }
        
        .nav-btn {
            padding: 14px 20px;
            font-size: 13px;
        }
    }

    /* Hide any default margins/paddings that might cause spacing issues */
    body {
        margin: 0;
        padding: 0;
    }
</style>

<div class="error-page-container">
    <div class="bg-decoration">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <div class="error-content">
        <div class="error-number">404</div>
        
        <div class="error-card">
            <h1 class="error-title">Content Not Found</h1>
            <p class="error-subtitle">
                The movie or TV show you're looking for seems to have vanished into the digital void. 
                Let's help you find something amazing to watch.
            </p>

            <div class="search-section">
                <form action="/search" method="GET" class="search-form">
                    <input 
                        type="search" 
                        name="q" 
                        class="search-input"
                        placeholder="Search for movies, TV shows..." 
                        required
                    >
                    <button type="submit" class="search-button">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>

            <div class="nav-buttons">
                <a href="/" class="nav-btn primary">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                    </svg>
                    Home
                </a>
                
                <a href="/movies" class="nav-btn">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 00-1 1v1a1 1 0 001 1h16a1 1 0 001-1V4a1 1 0 00-1-1H2z" />
                        <path fill-rule="evenodd" d="M2 7.5h16l-.811 7.71a2 2 0 01-1.99 1.79H4.802a2 2 0 01-1.99-1.79L2 7.5zM7 11a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    Movies
                </a>
                
                <a href="/tv-shows" class="nav-btn">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                    TV Shows
                </a>
            </div>
        </div>
    </div>
</div>