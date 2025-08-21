<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
/* CYBERPUNK COMMENTS SECTION */
    .comments-section {
        background: linear-gradient(135deg, #000 0%, #1a1a1a 25%, #0f0f0f 50%, #111 75%, #000 100%);
        padding: 80px 60px 100px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .comments-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #42ca1a, transparent);
        animation: scan 4s linear infinite;
    }

    @keyframes scan {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .comments-section .section-title {
        font-size: 32px;
        font-weight: 900;
        margin-bottom: 50px;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 3px;
        position: relative;
        text-align: center;
    }

    .comments-section .section-title::before {
        content: '◄';
        position: absolute;
        left: -50px;
        top: 50%;
        transform: translateY(-50%);
        color: #42ca1a;
        font-size: 28px;
        animation: blink 1.5s infinite;
    }

    .comments-section .section-title::after {
        content: '►';
        position: absolute;
        right: -50px;
        top: 50%;
        transform: translateY(-50%);
        color: #42ca1a;
        font-size: 28px;
        animation: blink 1.5s infinite reverse;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.3; }
    }

    .comment-form {
        background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
        border: 2px solid #333;
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.7);
    }

    .comment-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #42ca1a, transparent, #42ca1a);
        animation: loadingBar 3s ease-in-out infinite;
    }

    @keyframes loadingBar {
        0%, 100% { transform: scaleX(0); opacity: 0; }
        50% { transform: scaleX(1); opacity: 1; }
    }

    .comment-form h3 {
        margin-top: 0;
        margin-bottom: 30px;
        font-size: 24px;
        font-weight: 800;
        color: #42ca1a;
        position: relative;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .comment-form h3::before {
        content: '> ';
        color: #42ca1a;
        font-weight: normal;
        animation: cursor 1s infinite;
    }

    @keyframes cursor {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }

    .star-rating {
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        font-weight: 700;
        font-size: 16px;
        color: #fff;
    }

    .star-rating select {
        background: linear-gradient(145deg, #333, #1a1a1a);
        color: #42ca1a;
        border: 2px solid #42ca1a;
        padding: 15px 20px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        transition: all 0.3s ease;
        cursor: pointer;
        text-transform: uppercase;
        min-width: 120px;
    }

    .star-rating select:hover, 
    .star-rating select:focus {
        background: linear-gradient(145deg, #42ca1a, #36a615);
        color: #000;
        box-shadow: 0 0 20px rgba(66, 202, 26, 0.5);
        outline: none;
        transform: scale(1.05);
    }

    .comment-form textarea {
        width: 100%;
        background: linear-gradient(145deg, #222, #111);
        color: #fff;
        border: 2px solid #444;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 25px;
        resize: vertical;
        min-height: 150px;
        font-family: inherit;
        font-size: 16px;
        line-height: 1.7;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-sizing: border-box;
        box-shadow: inset 0 4px 8px rgba(0,0,0,0.3);
    }

    .comment-form textarea::placeholder {
        color: #888;
        font-style: italic;
        opacity: 0.7;
    }

    .comment-form textarea:hover {
        border-color: #42ca1a;
        box-shadow: inset 0 4px 8px rgba(0,0,0,0.3), 0 0 15px rgba(66, 202, 26, 0.3);
    }

    .comment-form textarea:focus {
        border-color: #42ca1a;
        background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
        box-shadow: inset 0 4px 8px rgba(0,0,0,0.2), 0 0 25px rgba(66, 202, 26, 0.4);
        outline: none;
        transform: scale(1.02);
    }

    .comment-form textarea:focus::placeholder {
        color: #aaa;
        transform: translateY(-2px);
    }

    .spoiler-checkbox {
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        font-size: 14px;
        color: #ccc;
    }

    .spoiler-checkbox input[type="checkbox"] {
        appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #444;
        border-radius: 4px;
        background: linear-gradient(145deg, #222, #111);
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
    }

    .spoiler-checkbox input[type="checkbox"]:checked {
        background: linear-gradient(145deg, #42ca1a, #36a615);
        border-color: #42ca1a;
        box-shadow: 0 0 15px rgba(66, 202, 26, 0.4);
    }

    .spoiler-checkbox input[type="checkbox"]:checked::before {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #000;
        font-weight: 900;
        font-size: 14px;
    }

    .spoiler-checkbox label {
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .spoiler-checkbox input[type="checkbox"]:checked + label {
        color: #42ca1a;
    }

    .btn-play {
        background: linear-gradient(135deg, #42ca1a, #36a615);
        color: #000;
        border: none;
        padding: 18px 40px;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-transform: uppercase;
        letter-spacing: 2px;
        box-shadow: 0 8px 20px rgba(66, 202, 26, 0.4);
        position: relative;
        overflow: hidden;
        min-width: 150px;
    }

    .btn-play::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        transition: all 0.5s ease;
        transform: translate(-50%, -50%);
        border-radius: 50%;
    }

    .btn-play:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-play:hover {
        background: linear-gradient(135deg, #36a615, #42ca1a);
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 35px rgba(66, 202, 26, 0.6);
    }

    .btn-play:active {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 20px rgba(66, 202, 26, 0.4);
    }

    .comments-list {
        margin-top: 60px;
    }

    .comment-card {
        background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
        border: 2px solid #333;
        padding: 35px;
        border-radius: 20px;
        margin-bottom: 30px;
        position: relative;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }

    .comment-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #42ca1a, #36a615);
        transition: all 0.4s ease;
    }

    .comment-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(66, 202, 26, 0.1), transparent);
        opacity: 0;
        transition: all 0.6s ease;
        animation: rotate 8s linear infinite;
        z-index: -1;
    }

    .comment-card:hover {
        transform: translateY(-8px) translateX(15px);
        box-shadow: 0 20px 40px rgba(66, 202, 26, 0.2);
        border-color: #42ca1a;
    }

    .comment-card:hover::before {
        width: 8px;
        box-shadow: 0 0 20px rgba(66, 202, 26, 0.6);
    }

    .comment-card:hover::after {
        opacity: 0.3;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #333;
        position: relative;
    }

    .comment-author {
        font-weight: 800;
        font-size: 18px;
        color: #42ca1a;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        background: linear-gradient(135deg, #42ca1a, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .comment-author::before {
        content: '@';
        position: absolute;
        left: -20px;
        top: 0;
        color: #888;
        font-weight: normal;
        -webkit-text-fill-color: #888;
        animation: pulse 2s infinite;
    }

    .comment-rating {
        background: linear-gradient(135deg, #42ca1a, #36a615);
        color: #000;
        padding: 12px 20px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 2px solid #42ca1a;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(66, 202, 26, 0.3);
        position: relative;
        overflow: hidden;
    }

    .comment-rating::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: all 0.6s ease;
    }

    .comment-card:hover .comment-rating::before {
        left: 100%;
    }

    .comment-body {
        margin: 0;
        color: #e5e5e5;
        line-height: 1.8;
        font-size: 16px;
        background: linear-gradient(145deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
        padding: 25px;
        border-radius: 15px;
        border-left: 4px solid #42ca1a;
        font-style: italic;
        position: relative;
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.2);
        backdrop-filter: blur(5px);
    }

    .comment-body::before {
        content: '"';
        position: absolute;
        top: -10px;
        left: 10px;
        font-size: 40px;
        color: #42ca1a;
        opacity: 0.3;
        font-family: serif;
    }

    .comment-body::after {
        content: '"';
        position: absolute;
        bottom: -25px;
        right: 15px;
        font-size: 40px;
        color: #42ca1a;
        opacity: 0.3;
        font-family: serif;
    }

    .comment-date {
        font-size: 13px;
        color: #888;
        margin-top: 20px;
        text-align: right;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
    }

    .comment-date::before {
        content: '⟫ ';
        color: #42ca1a;
        font-weight: 900;
        animation: slideIn 2s ease-in-out infinite;
    }

    @keyframes slideIn {
        0%, 100% { transform: translateX(0); opacity: 1; }
        50% { transform: translateX(5px); opacity: 0.7; }
    }

    .spoiler-warning {
        background: linear-gradient(135deg, #ff6b35, #f7931e);
        border: 2px solid #ff6b35;
        border-radius: 15px;
        padding: 20px;
        cursor: pointer;
        color: #000;
        text-align: center;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    }

    .spoiler-warning::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        transition: all 0.5s ease;
        transform: translate(-50%, -50%);
        border-radius: 50%;
    }

    .spoiler-warning:hover::before {
        width: 300px;
        height: 300px;
    }

    .spoiler-warning:hover {
        background: linear-gradient(135deg, #f7931e, #ff6b35);
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 30px rgba(255, 107, 53, 0.4);
    }

    .spoiler-warning i {
        margin-right: 10px;
        font-size: 18px;
        animation: warning 1.5s infinite;
    }

    @keyframes warning {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .spoiler-content {
        margin-top: 15px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }

    .spoiler-content[style*="block"] {
        opacity: 1;
        transform: translateY(0);
    }

    /* NO COMMENTS MESSAGE */
    .no-comments {
        text-align: center;
        padding: 60px 40px;
        background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
        border: 2px solid #333;
        border-radius: 20px;
        color: #888;
        font-size: 18px;
        font-style: italic;
        position: relative;
        overflow: hidden;
    }

    .no-comments::before {
        content: '💬';
        display: block;
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    body {
        background-color: #000;
        color: #fff;
        font-family: 'Netflix Sans', 'Helvetica Neue', Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    /* HERO SECTION */
    .hero-section {
        position: relative;
        height: 100vh;
        min-height: 600px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: flex-end;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            180deg,
            rgba(0,0,0,0.5) 0%,
            rgba(0,0,0,0.6) 20%,
            rgba(0,0,0,0.7) 40%,
            rgba(0,0,0,0.85) 60%,
            rgba(0,0,0,0.95) 80%,
            rgba(0,0,0,1) 100%
        );
    }

    .hero-content {
        position: relative;
        z-index: 10;
        width: 100%;
        margin: 0;
        margin-left: 60px;
        padding: 0 60px 80px 0;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .hero-logo {
        max-width: 400px;
        height: auto;
        margin-bottom: 30px;
        align-self: flex-start;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 700;
        margin: 0 0 20px 0;
        line-height: 1.1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        text-align: left;
    }

    .hero-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        font-size: 16px;
        justify-content: flex-start;
    }

    .meta-item {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 500;
    }

    .rating-badge {
        background-color: #333;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        border: 1px solid #666;
    }

    .hero-genres {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
    }

    .genre-tag {
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        opacity: 0.8;
    }

    .genre-separator {
        color: #666;
    }

    .hero-description {
        max-width: 600px;
        font-size: 18px;
        line-height: 1.5;
        margin-bottom: 30px;
        color: #e5e5e5;
        text-align: left;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 600px;
    }

    .main-buttons {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        transition: all 0.5s ease;
        transform: translate(-50%, -50%);
        border-radius: 50%;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-watch {
        background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
        color: #000;
        box-shadow: 0 6px 20px rgba(255,255,255,0.2);
    }

    .btn-watch:hover {
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.3);
    }

    .btn-watch:active {
        transform: translateY(0);
        box-shadow: 0 4px 15px rgba(255,255,255,0.2);
    }

    .btn-watchlist {
        background: linear-gradient(135deg, rgba(109, 109, 110, 0.8) 0%, rgba(80, 80, 80, 0.9) 100%);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .btn-watchlist::before {
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }

    .btn-watchlist:hover {
        background: linear-gradient(135deg, rgba(109, 109, 110, 1) 0%, rgba(90, 90, 90, 1) 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(109, 109, 110, 0.4);
        border-color: rgba(255,255,255,0.4);
    }

    .btn-watchlist:active {
        transform: translateY(0);
        box-shadow: 0 4px 15px rgba(109, 109, 110, 0.3);
    }

    .btn i {
        font-size: 18px;
    }

    .icon-buttons {
        display: flex;
        gap: 15px;
    }

    .icon-btn, .action-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(42, 42, 42, 0.8);
        border: 2px solid rgba(255,255,255,0.5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .icon-btn:hover, .action-btn:hover {
        background: rgba(42, 42, 42, 1);
        border-color: #fff;
        transform: scale(1.1);
    }

    /* MAIN CONTENT */
    .main-content {
        background: linear-gradient(180deg, #000 0%, #0a0a0a 50%, #111 100%);
        padding: 60px 60px 40px;
        width: 100%;
        position: relative;
    }

    .main-content::before {
        content: '';
        position: absolute;
        top: -100px;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(
            180deg,
            transparent 0%,
            rgba(0,0,0,0.3) 20%,
            rgba(0,0,0,0.7) 60%,
            rgba(0,0,0,0.9) 80%,
            rgba(0,0,0,1) 100%
        );
        pointer-events: none;
    }

    /* MODERN CAST SECTION - NEW DESIGN */
    .top-cast {
        margin-bottom: 70px;
        position: relative;
        background: radial-gradient(ellipse at center, rgba(66, 202, 26, 0.03) 0%, transparent 70%);
        padding: 40px 0;
        border-radius: 20px;
    }

    .section-title {
        font-size: 32px;
        font-weight: 900;
        margin-bottom: 40px;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        display: inline-block;
        background: linear-gradient(135deg, #fff 0%, #42ca1a 50%, #fff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .section-title::before {
        content: '☆';
        position: absolute;
        left: -30px;
        top: 50%;
        transform: translateY(-50%);
        color: #42ca1a;
        font-size: 24px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.5; transform: translateY(-50%) scale(1); }
        50% { opacity: 1; transform: translateY(-50%) scale(1.1); }
    }

    .cast-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 30px;
        padding: 20px;
    }

    .cast-item {
        text-align: center;
        background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
        border-radius: 20px;
        padding: 25px 15px;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .cast-item::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, #42ca1a, transparent);
        opacity: 0;
        transition: all 0.6s ease;
        animation: rotate 4s linear infinite;
        z-index: -1;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .cast-item:hover::before {
        opacity: 0.3;
    }

    .cast-item:hover {
        transform: translateY(-15px) rotateY(5deg);
        box-shadow: 0 25px 50px rgba(66, 202, 26, 0.3);
        border-color: #42ca1a;
    }

    .cast-avatar {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        object-fit: cover;
        margin: 0 auto 20px;
        display: block;
        border: 3px solid #333;
        transition: all 0.5s ease;
        position: relative;
        z-index: 2;
    }

    .cast-item:hover .cast-avatar {
        transform: scale(1.15) rotateZ(5deg);
        border-color: #42ca1a;
        box-shadow: 0 10px 30px rgba(66, 202, 26, 0.5);
        border-radius: 50%;
    }

    .cast-name {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #fff;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .cast-item:hover .cast-name {
        color: #42ca1a;
        transform: scale(1.05);
    }

    .cast-character {
        font-size: 13px;
        color: #888;
        line-height: 1.4;
        position: relative;
        z-index: 2;
        font-style: italic;
        transition: all 0.3s ease;
    }

    .cast-item:hover .cast-character {
        color: #aaa;
        transform: translateY(-2px);
    }

    /* EPISODES SECTION */
    .episodes-section {
        margin-bottom: 50px;
    }

    .episodes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .episodes-tabs {
        display: flex;
        gap: 0;
        background-color: transparent;
    }

    .tab-button {
        background: none;
        border: none;
        color: #999;
        padding: 12px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: color 0.3s ease;
        border-bottom: 2px solid transparent;
    }

    .tab-button.active {
        color: #fff;
        border-bottom-color: #fff;
    }

    .tab-button:hover {
        color: #ccc;
    }

    /* Season Dropdown */
    .season-dropdown {
        position: relative;
        margin-bottom: 30px;
        width: fit-content;
    }

    .season-selector {
        background: linear-gradient(145deg, #1a1a1a, #2a2a2a);
        border: 1px solid #333;
        color: #fff;
        padding: 12px 40px 12px 15px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        min-width: 200px;
        position: relative;
        transition: all 0.3s ease;
    }

    .season-selector:hover {
        border-color: #555;
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
    }

    .season-selector::after {
        content: '▼';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        transition: transform 0.3s ease;
    }

    .season-selector.open::after {
        transform: translateY(-50%) rotate(180deg);
    }

    .season-options {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: linear-gradient(145deg, #1a1a1a, #2a2a2a);
        border: 1px solid #333;
        border-radius: 8px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
        z-index: 100;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .season-options.open {
        max-height: 300px;
        margin-top: 5px;
    }

    .season-option {
        padding: 12px 15px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #333;
    }

    .season-option:last-child {
        border-bottom: none;
    }

    .season-option:hover {
        background-color: #333;
    }

    .season-option.selected {
        background-color: #444;
        color: #fff;
    }

    .episodes-grid {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: scroll !important;
        overflow-y: visible !important;
        gap: 20px !important;
        padding: 20px 0 50px 0 !important;
        scroll-behavior: smooth !important;
        width: 100% !important;
        min-height: 290px !important;
        align-items: flex-start !important;
        
        /* Scrollbar'ı gizle */
        scrollbar-width: none !important; /* Firefox */
        -ms-overflow-style: none !important; /* IE/Edge */
    }

    .episodes-grid::-webkit-scrollbar {
        display: none !important; /* Chrome, Safari, Opera */
    }

    @media (max-width: 1200px) {
        .episodes-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .episodes-grid {
            grid-template-columns: 1fr;
        }
    }

    .episode-card {
        flex: 0 0 300px; /* Sabit genişlik: 300px */
        width: 300px;
        background: linear-gradient(145deg, #1a1a1a, #2a2a2a);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid transparent;
        position: relative;
    }

    .episode-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        border-color: rgba(255, 255, 255, 0.1);
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
    }

    .episode-thumbnail {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .episode-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .episode-card:hover .episode-thumbnail img {
        transform: scale(1.05);
    }

    .episode-duration {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: linear-gradient(145deg, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.7));
        color: #fff;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        backdrop-filter: blur(5px);
    }

    .episode-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
        padding: 40px 20px 20px;
        color: #fff;
    }

    .episode-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .episode-number {
        font-size: 14px;
        font-weight: 600;
        color: #ccc;
        opacity: 0.8;
    }

    .episode-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .episode-description {
        font-size: 14px;
        line-height: 1.4;
        color: #ccc;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .episodes-list {
        position: relative !important;
        overflow: visible !important;
        min-height: 500px !important;
        padding-bottom: 50px !important;
    }

    .episodes-navigation {
        position: relative;
        width: 100%;
    }

    .nav-arrow-left {
        position: absolute;
        left: -20px;
        top: 40%;
        transform: translateY(-50%);
        z-index: 100;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(40, 40, 40, 0.8));
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0;
        visibility: hidden;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .nav-arrow-right {
        position: absolute;
        right: -20px;
        top: 40%;
        transform: translateY(-50%);
        z-index: 100;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(40, 40, 40, 0.8));
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 1;
        visibility: visible;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* Ok ikonları */
    .nav-arrow-left i,
    .nav-arrow-right i {
        font-size: 18px;
        color: #fff;
        transition: all 0.3s ease;
    }

    /* Hover efektleri */
    .nav-arrow-left:hover,
    .nav-arrow-right:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(60, 60, 60, 0.8));
        border-color: rgba(255, 255, 255, 0.6);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
    }

    .nav-arrow-left:hover i,
    .nav-arrow-right:hover i {
        color: #fff;
        transform: scale(1.2);
    }

    /* Active state */
    .nav-arrow-left:active,
    .nav-arrow-right:active {
        transform: translateY(-50%) scale(0.95);
    }

    /* Episodes container hover durumunda butonları göster */
    .episodes-navigation:hover .nav-arrow-left.show {
        opacity: 1;
        visibility: visible;
    }

    .episodes-navigation:hover .nav-arrow-right {
        opacity: 1;
        visibility: visible;
    }

    /* Navigation butonları gösterildiğinde */
    .nav-arrow-left.show {
        opacity: 1;
        visibility: visible;
    }

    .nav-arrow-right.hide {
        opacity: 0;
        visibility: hidden;
    }

    .episodes-navigation::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 50px;
        width: 60px;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.8), transparent);
        z-index: 50;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .episodes-navigation::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 50px;
        width: 60px;
        background: linear-gradient(to left, rgba(0, 0, 0, 0.8), transparent);
        z-index: 50;
        pointer-events: none;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .episodes-navigation:hover::before {
        opacity: 1;
    }

    .episodes-navigation:hover::after {
        opacity: 1;
    }

    /* FUTURISTIC MEDIA SECTION - UPDATED WITH SMALLER SIZES */
    .extra-details-section {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 25%, #111 50%, #0f0f0f 75%, #000 100%);
        padding: 80px 60px 100px;
        width: 100%;
        position: relative;
        margin-top: 50px;
        overflow: hidden;
    }

    .extra-details-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #42ca1a, transparent);
        animation: slideGlow 3s ease-in-out infinite;
    }

    @keyframes slideGlow {
        0%, 100% { opacity: 0; transform: scaleX(0); }
        50% { opacity: 1; transform: scaleX(1); }
    }

    .tabs-container {
        border: none;
        margin-bottom: 50px;
        background: transparent;
        padding: 0;
        display: flex;
        justify-content: center;
    }

    .tab-link {
        background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
        border: 2px solid #333;
        color: #888;
        padding: 18px 40px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-radius: 50px;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0 10px;
    }

    .tab-link::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, #42ca1a 0%, transparent 70%);
        transition: all 0.6s ease;
        transform: translate(-50%, -50%);
        border-radius: 50%;
    }

    .tab-link:hover::before {
        width: 300px;
        height: 300px;
    }

    .tab-link.active {
        color: #000;
        background: linear-gradient(135deg, #42ca1a, #36a615);
        border-color: #42ca1a;
        box-shadow: 0 8px 25px rgba(66, 202, 26, 0.4);
        transform: scale(1.05);
    }

    .tab-link:hover {
        color: #fff;
        border-color: #42ca1a;
        transform: translateY(-3px);
    }

    .tab-content h3 {
        margin-top: 0;
        margin-bottom: 35px;
        font-size: 26px;
        font-weight: 900;
        color: #fff;
        position: relative;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 3px;
    }

    .tab-content h3::before,
    .tab-content h3::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 50px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #42ca1a);
    }

    .tab-content h3::before {
        left: -70px;
    }

    .tab-content h3::after {
        right: -70px;
        background: linear-gradient(90deg, #42ca1a, transparent);
    }

    .media-grid {
        display: grid;
        gap: 20px;
        margin-bottom: 60px;
        perspective: 1000px;
    }

    /* SMALLER GRID SIZES */
    .trailers-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 300px));
        justify-content: start;
    }

    .backdrops-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .posters-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    .media-grid a {
        display: block;
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        text-decoration: none;
        color: #fff;
        background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid #333;
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .media-grid a::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, #42ca1a, transparent, #42ca1a);
        border-radius: 15px;
        opacity: 0;
        transition: all 0.5s ease;
        z-index: -1;
    }

    .media-grid a:hover::before {
        opacity: 1;
        animation: borderGlow 2s linear infinite;
    }

    @keyframes borderGlow {
        0%, 100% { background: linear-gradient(45deg, #42ca1a, transparent, #42ca1a); }
        25% { background: linear-gradient(135deg, #42ca1a, transparent, #42ca1a); }
        50% { background: linear-gradient(225deg, #42ca1a, transparent, #42ca1a); }
        75% { background: linear-gradient(315deg, #42ca1a, transparent, #42ca1a); }
    }

    .media-grid a:hover {
        transform: translateY(-8px) rotateX(3deg) rotateY(3deg);
        box-shadow: 0 15px 30px rgba(66, 202, 26, 0.3);
        border-color: #42ca1a;
    }

    .media-grid .grid-image {
        width: 100%;
        display: block;
        transition: transform 0.6s ease;
        position: relative;
        z-index: 0;
        border-radius: 13px;
        object-fit: cover;
    }

    .media-grid a:hover .grid-image {
        transform: scale(1.08) rotateZ(1deg);
    }

    .trailer-card {
        position: relative;
    }

    .trailer-card img {
        width: 100%;
        height: auto;
        aspect-ratio: 16/9; /* MAINTAIN 16:9 ASPECT RATIO */
        object-fit: cover;
        display: block;
        transition: transform 0.6s ease;
        border-radius: 13px;
    }

    /* POSTER SPECIFIC STYLING */
    .posters-grid .grid-image {
        height: 220px; /* TALLER FOR POSTER ASPECT RATIO */
        object-fit: cover;
    }

    /* BACKDROP SPECIFIC STYLING */
    .backdrops-grid .grid-image {
        height: 120px; /* LANDSCAPE ASPECT RATIO */
        object-fit: cover;
    }

    .trailer-card a:hover img {
        transform: scale(1.08) rotateZ(1deg);
    }

    .trailer-card .play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 40px; /* REDUCED SIZE */
        color: #000;
        background: linear-gradient(135deg, #42ca1a, #36a615);
        width: 70px; /* REDUCED SIZE */
        height: 70px; /* REDUCED SIZE */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0;
        pointer-events: none;
        box-shadow: 0 8px 20px rgba(66, 202, 26, 0.6);
        z-index: 2;
        border: 3px solid #fff;
    }

    .trailer-card .play-icon::before {
        content: '';
        position: absolute;
        top: -8px;
        left: -8px;
        right: -8px;
        bottom: -8px;
        border: 2px solid #42ca1a;
        border-radius: 50%;
        animation: pulse-border 2s infinite;
    }

    @keyframes pulse-border {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
    }

    .trailer-card a:hover .play-icon {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.1) rotateZ(360deg);
        box-shadow: 0 12px 30px rgba(66, 202, 26, 0.8);
    }

    .trailer-card .media-title {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px 15px 15px;
        margin: 0;
        font-size: 14px; /* REDUCED SIZE */
        font-weight: 700;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.95));
        text-align: center;
        opacity: 0;
        transition: all 0.5s ease;
        z-index: 2;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .trailer-card a:hover .media-title {
        opacity: 1;
        transform: translateY(-3px);
    }

    /* POPUP MODAL STYLES */
    .media-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        padding: 20px;
        box-sizing: border-box;
    }

    .media-popup.active {
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .popup-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
        border: 2px solid #42ca1a;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 20px 50px rgba(66, 202, 26, 0.3);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .popup-close {
        position: absolute;
        top: 15px;
        right: 20px;
        background: linear-gradient(135deg, #42ca1a, #36a615);
        color: #000;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
        transition: all 0.3s ease;
        z-index: 10000;
    }

    .popup-close:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 5px 15px rgba(66, 202, 26, 0.5);
    }

    .popup-image {
        width: 100%;
        height: auto;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 15px;
        display: block;
    }

    .popup-video {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        border-radius: 15px;
        overflow: hidden;
    }

    .popup-video iframe {
        width: 100%;
        height: 450px;
        border: none;
        border-radius: 15px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .hero-content {
            margin-left: 30px;
            padding: 0 30px 60px 0;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-meta {
            flex-wrap: wrap;
            gap: 10px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            min-width: auto;
        }

        .main-buttons {
            width: 100%;
        }

        .icon-buttons {
            align-self: flex-start;
            margin-left: 0;
        }

        .main-content, .extra-details-section, .comments-section {
            padding: 40px 30px;
        }

        .cast-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 20px;
        }

        .trailers-grid {
            grid-template-columns: 1fr;
        }

        .trailers-grid img {
            height: 200px; /* MOBILE TRAILER HEIGHT */
        }

        .backdrops-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .backdrops-grid .grid-image {
            height: 100px; /* MOBILE BACKDROP HEIGHT */
        }

        .posters-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }

        .posters-grid .grid-image {
            height: 180px; /* MOBILE POSTER HEIGHT */
        }

        .comment-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .section-title {
            font-size: 24px;
        }

        .tab-content h3 {
            font-size: 20px;
        }

        .popup-content {
            max-width: 95%;
            max-height: 95%;
            padding: 15px;
        }

        .popup-video {
            height: 40vh;
        }

        .nav-arrow-left,
        .nav-arrow-right {
            width: 40px;
            height: 40px;
            left: -15px;
        }
        
        .nav-arrow-right {
            right: -15px;
        }
        
        .nav-arrow-left i,
        .nav-arrow-right i {
            font-size: 14px;
        }

        .episode-card {
            flex: 0 0 280px;
        }

        .episode-thumbnail {
            height: 157px;
        }

        .episode-title {
            font-size: 16px;
        }
        
        .episodes-header,
        .season-dropdown {
            padding: 0 30px;
        }
        
        .season-dropdown {
            width: 100%;
        }
        
        .season-selector {
            padding: 12px 40px 12px 15px;
            font-size: 16px;
        }
        
        .episodes-list h3 {
            padding: 0 30px !important;
        }
        
        .btn {
            padding: 12px 24px;
            font-size: 14px;
        }

        .comment-form {
            padding: 25px;
        }

        .comment-card {
            padding: 25px;
        }

        .comments-section .section-title::before,
        .comments-section .section-title::after {
            display: none;
        }

        .comment-author::before {
            left: -15px;
        }
    }

    .episodes-list.hidden {
        display: none;
    }

    .episodes-list.active {
        display: block;
    }
    /* Add these styles to the main <style> block at the top of your page */
.like-btn {
    background: #333;
    border: 1px solid #555;
    color: #fff;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.2s, color 0.2s;
}
.like-btn:hover {
    background: #444;
}
.like-btn.liked {
    background-color: #42ca1a;
    color: #fff;
    border-color: #42ca1a;
}
.like-count {
    font-size: 14px;
    color: #aaa;
}
/* Add this style to indent the replies */
.comment-reply {
    margin-left: 50px; /* You can adjust this value for more or less indentation */
}
</style>

<!-- Hero Section -->
<div class="hero-section" style="background-image: url('https://image.tmdb.org/t/p/original<?php echo $tvShow['backdrop_path']; ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <?php if (!empty($tvShow['logo_path'])): ?>
            <img class="hero-logo" src="https://image.tmdb.org/t/p/w500<?php echo $tvShow['logo_path']; ?>" alt="<?php echo htmlspecialchars($tvShow['title']); ?>">
        <?php else: ?>
            <h1 class="hero-title"><?php echo htmlspecialchars($tvShow['title']); ?></h1>
        <?php endif; ?>
        
        <div class="hero-meta">
            <div class="meta-item">
                <span class="rating-badge">CBFC:U/A</span>
            </div>
            <div class="meta-item">
                <span><?php echo date('Y', strtotime($tvShow['first_air_date'])); ?></span>
            </div>
            <div class="meta-item">
                <span><?php echo htmlspecialchars($tvShow['status']); ?></span>
            </div>
            <div class="meta-item">
                <span><?php echo count($tvShow['seasons']); ?> Season<?php echo count($tvShow['seasons']) > 1 ? 's' : ''; ?></span>
            </div>
        </div>
        
        <div class="hero-genres">
            <?php foreach(array_slice($tvShow['genres'], 0, 3) as $index => $genre): ?>
                <?php if ($index > 0): ?><span class="genre-separator">•</span><?php endif; ?>
                <a href="#" class="genre-tag"><?php echo htmlspecialchars($genre['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <p class="hero-description"><?php echo htmlspecialchars($tvShow['overview']); ?></p>

        <div class="action-buttons">
    <div class="main-buttons">
        <?php if (!empty($tvShow['seasons'][0]) && !empty($tvShow['seasons'][0]['episodes'][0])): ?>
            <?php
                // Get the season and episode number for the first episode
                $firstSeasonNum = $tvShow['seasons'][0]['season_number'];
                $firstEpisodeNum = $tvShow['seasons'][0]['episodes'][0]['episode_number'];
            ?>
            <a href="/tv-show/<?php echo htmlspecialchars($tvShow['slug']); ?>/<?php echo $firstSeasonNum . 'x' . $firstEpisodeNum; ?>" class="btn btn-watch">
                <i class="fas fa-play"></i>
                Watch Watch
            </a>
        <?php endif; ?>
        <a href="#" class="btn btn-watchlist"><i class="fas fa-plus"></i> Add Watchlist</a>
    </div>
    </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Cast -->
    <?php if (!empty($tvShow['cast'])): ?>
    <div class="top-cast">
        <h2 class="section-title">Top Cast</h2>
        <div class="cast-grid">
            <?php foreach(array_slice($tvShow['cast'], 0, 8) as $castMember): ?>
                <div class="cast-item">
                    <img src="<?php echo !empty($castMember['profile_path']) ? 'https://image.tmdb.org/t/p/w200'.$castMember['profile_path'] : 'https://via.placeholder.com/100'; ?>" alt="<?php echo htmlspecialchars($castMember['name']); ?>" class="cast-avatar">
                    <div class="cast-name"><?php echo htmlspecialchars($castMember['name']); ?></div>
                    <div class="cast-character"><?php echo htmlspecialchars($castMember['character_name']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Episodes Section -->
    <div class="episodes-section">
        <div class="episodes-header">
            <div class="episodes-tabs">
                <button class="tab-button active">Season and Episodes</button>
            </div>
        </div>
        
        <!-- Season Selection Dropdown -->
        <div class="season-dropdown">
            <div class="season-selector" id="seasonSelector">
                <span id="selectedSeason"><?php echo htmlspecialchars($tvShow['seasons'][0]['name']); ?></span>
            </div>
            <div class="season-options" id="seasonOptions">
                <?php foreach ($tvShow['seasons'] as $index => $season): ?>
                    <div class="season-option <?php echo $index === 0 ? 'selected' : ''; ?>" data-season="season-<?php echo $season['id']; ?>">
                        <?php echo htmlspecialchars($season['name']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Episodes List -->
        <?php foreach ($tvShow['seasons'] as $index => $season): ?>
            <div id="season-<?php echo $season['id']; ?>" class="episodes-list <?php echo $index === 0 ? 'active' : 'hidden'; ?>">
                <h3 style="margin-bottom: 20px; font-size: 18px; padding: 0 60px;">1-<?php echo !empty($season['episode_count']) ? $season['episode_count'] : 8; ?> Episodes</h3>
                <div class="episodes-grid">
                    <?php 
                    // Sample episodes data (since episodes might not be loaded)
                    $sampleEpisodes = [
                        ['name' => 'Wednesday\'s Child Is Full of Woe', 'episode_number' => 1, 'still_path' => '/sample1.jpg', 'overview' => 'Wednesday Addams, a teenager who possesses psychic powers, Wednesday\'s cold, emotionless personality and her defiant nature make it difficult for her to connect with her schoolmates and cause her to run afoul of the school\'s principal Larissa Weems.'],
                        ['name' => 'Friend or Woe', 'episode_number' => 2, 'still_path' => '/sample2.jpg', 'overview' => 'Wednesday continues to investigate the murders while dealing with her new roommate and the school\'s secrets.'],
                        ['name' => 'Friend or Woe', 'episode_number' => 3, 'still_path' => '/sample3.jpg', 'overview' => 'Wednesday discovers more about her family\'s history and the mysterious creature terrorizing the school.'],
                        ['name' => 'Woe What a Night', 'episode_number' => 4, 'still_path' => '/sample4.jpg', 'overview' => 'The annual Poe Cup boat race brings out Wednesday\'s competitive side while she continues her investigation.']
                    ];
                    
                    $episodes = !empty($season['episodes']) ? $season['episodes'] : $sampleEpisodes;
                    ?>
                    <?php foreach ($episodes as $episode): ?>
                        <div class="episode-card">
    <?php
        // Create the new URL format: /tv-show/slug/1x5
        $episodeWatchUrl = "/tv-show/" . htmlspecialchars($tvShow['slug']) . "/" . htmlspecialchars($season['season_number']) . "x" . htmlspecialchars($episode['episode_number']);
    ?>
    <a href="<?php echo $episodeWatchUrl; ?>" style="text-decoration: none; color: inherit;">
        <div class="episode-thumbnail">
            <img src="https://image.tmdb.org/t/p/w300<?php echo !empty($episode['still_path']) ? $episode['still_path'] : '/placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($episode['name']); ?>">
        </div>
        <div class="episode-info">
            <div class="episode-header">
                <div class="episode-number">Episode <?php echo $episode['episode_number']; ?></div>
            </div>
            <div class="episode-title"><?php echo htmlspecialchars($episode['name']); ?></div>
            <div class="episode-description"><?php echo htmlspecialchars(substr($episode['overview'], 0, 100)); ?>...</div>
        </div>
    </a>
</div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="extra-details-section">
    <div class="tabs-container">
        <button class="tab-link active" onclick="openTab(event, 'media')">MEDIA</button>    
    </div>
    
    <div id="media" class="tab-content" style="display: block;">
        <h3>Trailers</h3>
        <div class="media-grid trailers-grid">
            <?php 
                $trailers = $movie['trailers'] ?? $tvShow['trailers'] ?? [];
                if (!empty($trailers)):
            ?>
                <?php foreach ($trailers as $trailer): ?>
                    <div class="trailer-card">
                        <a href="#" onclick="openVideoPopup('<?php echo htmlspecialchars($trailer['key']); ?>', '<?php echo htmlspecialchars($trailer['name']); ?>')">
                            <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($trailer['key']); ?>/hqdefault.jpg" alt="<?php echo htmlspecialchars($trailer['name']); ?>">
                            <div class="play-icon"><i class="fas fa-play"></i></div>
                            <p class="media-title"><?php echo htmlspecialchars($trailer['name']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No trailers found for this content.</p>
            <?php endif; ?>
        </div>

        <h3>Backdrops</h3>
        <div class="media-grid backdrops-grid">
            <?php 
                $media = $movie['media'] ?? $tvShow['media'] ?? [];
                $backdrops = $media['backdrops'] ?? [];
                if (!empty($backdrops)):
            ?>
                <?php foreach ($backdrops as $image): ?>
                    <a href="#" onclick="openImagePopup('https://image.tmdb.org/t/p/original<?php echo $image['file_path']; ?>')">
                        <img src="https://image.tmdb.org/t/p/w500<?php echo $image['file_path']; ?>" class="grid-image">
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No backdrops found.</p>
            <?php endif; ?>
        </div>

        <h3>Posters</h3>
        <div class="media-grid posters-grid">
            <?php 
                $posters = $media['posters'] ?? [];
                if (!empty($posters)):
            ?>
                <?php foreach ($posters as $image): ?>
                    <a href="#" onclick="openImagePopup('https://image.tmdb.org/t/p/original<?php echo $image['file_path']; ?>')">
                        <img src="https://image.tmdb.org/t/p/w342<?php echo $image['file_path']; ?>" class="grid-image">
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posters found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- POPUP MODAL -->
<div id="mediaPopup" class="media-popup">
    <div class="popup-content">
        <button class="popup-close" onclick="closePopup()">&times;</button>
        <div id="popupContent"></div>
    </div>
</div>

<div class="comments-section">
    <h2 class="section-title">User Reviews & Ratings</h2>

    <div class="comment-form">
        <h3>Leave a Review</h3>
        <form action="/comments/store" method="POST">
            <input type="hidden" name="content_id" value="<?php echo htmlspecialchars($movie['id'] ?? $tvShow['id']); ?>">
            <input type="hidden" name="content_type" value="<?php echo isset($movie) ? 'movie' : 'tv_show'; ?>">
            
            <div class="star-rating">
                <span>Your Rating: </span>
                <select name="rating" required>
                    <option value="10">10 ⭐</option>
                    <option value="9">9 ⭐</option>
                    <option value="8">8 ⭐</option>
                    <option value="7">7 ⭐</option>
                    <option value="6">6 ⭐</option>
                    <option value="5">5 ⭐</option>
                    <option value="4">4 ⭐</option>
                    <option value="3">3 ⭐</option>
                    <option value="2">2 ⭐</option>
                    <option value="1">1 ⭐</option>
                </select>
            </div>
            <textarea name="comment" rows="4" required placeholder="Share your thoughts..."></textarea>
            <div class="spoiler-checkbox">
                <input type="checkbox" name="is_spoiler" id="is_spoiler" value="1">
                <label for="is_spoiler">This review contains spoilers</label>
            </div>
            <button type="submit" class="btn-play">Submit Review</button>
        </form>
    </div>

<div class="comments-list">
    <?php 
    $comments = $movie['comments'] ?? $tvShow['comments'] ?? [];

    // This is the updated helper function to render comments with a "View Replies" button
    function render_comment_with_replies($comment, $isReply = false) {
        ?>
        <div class="comment-container <?php echo $isReply ? 'comment-reply' : ''; ?>">
            <div class="comment-card" id="comment-<?php echo $comment['id']; ?>">
                <div class="comment-header">
                    <span class="comment-author"><?php echo htmlspecialchars($comment['author_username'] ?? 'Anonymous'); ?></span>
                    <span class="comment-rating"><?php echo htmlspecialchars($comment['rating']); ?>/10 ★</span>
                </div>
                
                <?php if ($comment['is_spoiler']): ?>
                    <div class="spoiler-warning"><i class="fas fa-exclamation-triangle"></i> This review contains spoilers. Click to reveal.</div>
                    <div class="spoiler-content" style="display:none;"><p class="comment-body"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p></div>
                <?php else: ?>
                    <p class="comment-body"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                <?php endif; ?>
                
                <div class="comment-footer">
                    <div class="comment-actions">
                        <button class="like-btn <?php echo ($comment['is_liked_by_user'] ?? false) ? 'liked' : ''; ?>" data-comment-id="<?php echo $comment['id']; ?>"><i class="fas fa-thumbs-up"></i></button>
                        <span class="like-count"><?php echo $comment['like_count']; ?></span>
                        <button class="reply-btn" data-comment-id="<?php echo $comment['id']; ?>"><i class="fas fa-reply"></i> Reply</button>
                    </div>
                    <div class="comment-date"><?php echo date('d M Y, H:i', strtotime($comment['created_at'])); ?></div>
                </div>
            </div>
            <div class="reply-form-container"></div>
            
            <?php // --- THIS IS THE NEW LOGIC --- ?>
            <?php if (!empty($comment['replies'])): ?>
                <div class="view-replies-container">
                    <button class="view-replies-btn" data-target="replies-for-<?php echo $comment['id']; ?>">
                        <i class="fas fa-comments"></i> View <?php echo count($comment['replies']); ?> Replies
                    </button>
                </div>

                <div id="replies-for-<?php echo $comment['id']; ?>" class="replies-wrapper" style="display: none;">
                    <?php foreach ($comment['replies'] as $reply): ?>
                        <?php render_comment_with_replies($reply, true); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    } // End of helper function

    if (!empty($comments)) {
        foreach ($comments as $comment) {
            render_comment_with_replies($comment);
        }
    } else {
        echo '<div class="no-comments"><p>No reviews yet. Be the first to leave a review!</p></div>';
    }
    ?>
</div>

<style>
    .view-replies-container {
        padding-left: 50px;
        margin-top: -10px;
        margin-bottom: 15px;
    }
    .view-replies-btn {
        background: none;
        border: none;
        color: #42ca1a;
        font-weight: bold;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px;
    }
    .view-replies-btn:hover {
        text-decoration: underline;
    }
</style>
</div>

<div id="reply-form-template" style="display: none;">
    <form action="/comments/store" method="POST" style="margin-top: 15px; border-left: 2px solid #333; padding-left: 20px;">
        <input type="hidden" name="content_id" value="<?php echo htmlspecialchars($movie['id'] ?? $tvShow['id']); ?>">
        <input type="hidden" name="content_type" value="<?php echo isset($movie) ? 'movie' : 'tv_show'; ?>">
        <input type="hidden" class="parent-id-input" name="parent_id" value="">
        <textarea name="comment" rows="3" required placeholder="Write your reply..." style="width: 100%; background: #222; color: #fff; border: 1px solid #555; padding: 10px; border-radius: 5px; margin-bottom: 10px;"></textarea>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="spoiler-checkbox">
                <input type="checkbox" name="is_spoiler" value="1">
                <label for="is_spoiler">Spoiler?</label>
            </div>
            <input type="hidden" name="rating" value="0">
            <div>
                <button type="button" class="cancel-reply-btn btn" style="background: #555;">Cancel</button>
                <button type="submit" class="btn btn-play" style="padding: 10px 20px;">Submit Reply</button>
            </div>
        </div>
    </form>
</div>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // POPUP FUNCTIONS
    function openVideoPopup(videoKey, title) {
        const popup = document.getElementById('mediaPopup');
        const content = document.getElementById('popupContent');
        
        content.innerHTML = `
            <div class="popup-video">
                <iframe src="https://www.youtube.com/embed/${videoKey}?autoplay=1&rel=0" 
                        title="${title}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                </iframe>
            </div>
        `;
        
        popup.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function openImagePopup(imageSrc) {
        const popup = document.getElementById('mediaPopup');
        const content = document.getElementById('popupContent');
        
        content.innerHTML = `<img class="popup-image" src="${imageSrc}" alt="Media Image">`;
        
        popup.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePopup() {
        const popup = document.getElementById('mediaPopup');
        const content = document.getElementById('popupContent');
        
        popup.classList.remove('active');
        content.innerHTML = '';
        document.body.style.overflow = 'auto';
    }

    // Close popup when clicking outside content
    document.getElementById('mediaPopup').addEventListener('click', function(e) {
        if (e.target === this) {
            closePopup();
        }
    });

    // Close popup with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePopup();
        }
    });

document.addEventListener('DOMContentLoaded', function() {
    // Season dropdown functionality
    const seasonSelector = document.getElementById('seasonSelector');
    const seasonOptions = document.getElementById('seasonOptions');
    const selectedSeasonText = document.getElementById('selectedSeason');
    const episodesLists = document.querySelectorAll('.episodes-list');

    // Toggle dropdown
    seasonSelector.addEventListener('click', function() {
        seasonSelector.classList.toggle('open');
        seasonOptions.classList.toggle('open');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!seasonSelector.contains(e.target)) {
            seasonSelector.classList.remove('open');
            seasonOptions.classList.remove('open');
        }
    });

    // Season option selection
    const seasonOptionElements = document.querySelectorAll('.season-option');
    seasonOptionElements.forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Remove selected class from all options
            seasonOptionElements.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Update selected text
            selectedSeasonText.textContent = this.textContent;
            
            // Hide all episodes lists
            episodesLists.forEach(list => {
                list.classList.remove('active');
                list.classList.add('hidden');
            });
            
            // Show selected season episodes
            const seasonId = this.getAttribute('data-season');
            const targetList = document.getElementById(seasonId);
            if (targetList) {
                targetList.classList.add('active');
                targetList.classList.remove('hidden');
            }
            
            // Close dropdown
            seasonSelector.classList.remove('open');
            seasonOptions.classList.remove('open');
        });
    });

    // Action buttons functionality with enhanced animations
    const actionBtns = document.querySelectorAll('.action-btn, .icon-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.height, rect.width);
            const x = rect.width / 2 - size / 2;
            const y = rect.height / 2 - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                left: ${x}px;
                top: ${y}px;
                width: ${size}px;
                height: ${size}px;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Episode cards hover effects
    const episodeCards = document.querySelectorAll('.episode-card');
    episodeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Add ripple animation keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Netflix tarzı episode navigation
function initEpisodeNavigation() {
    const episodesLists = document.querySelectorAll('.episodes-list');
    
    episodesLists.forEach(episodesList => {
        const episodesGrid = episodesList.querySelector('.episodes-grid');
        
        if (!episodesGrid) return;
        
        // Navigation wrapper oluştur
        const navWrapper = document.createElement('div');
        navWrapper.className = 'episodes-navigation';
        
        // Grid'i wrapper'a al
        episodesGrid.parentNode.insertBefore(navWrapper, episodesGrid);
        navWrapper.appendChild(episodesGrid);
        
        // Sol ok butonu
        const leftArrow = document.createElement('div');
        leftArrow.className = 'nav-arrow-left';
        leftArrow.innerHTML = '<i class="fas fa-chevron-left"></i>';
        
        // Sağ ok butonu
        const rightArrow = document.createElement('div');
        rightArrow.className = 'nav-arrow-right';
        rightArrow.innerHTML = '<i class="fas fa-chevron-right"></i>';
        
        // Butonları wrapper'a ekle
        navWrapper.appendChild(leftArrow);
        navWrapper.appendChild(rightArrow);
        
        // Scroll durumunu kontrol et ve butonları güncelle
        function updateArrowVisibility() {
            const scrollLeft = episodesGrid.scrollLeft;
            const scrollWidth = episodesGrid.scrollWidth;
            const clientWidth = episodesGrid.clientWidth;
            
            // Sol ok - scroll başında değilse göster
            if (scrollLeft > 50) {
                leftArrow.classList.add('show');
            } else {
                leftArrow.classList.remove('show');
            }
            
            // Sağ ok - scroll sonunda değilse göster  
            if (scrollLeft < scrollWidth - clientWidth - 50) {
                rightArrow.classList.remove('hide');
            } else {
                rightArrow.classList.add('hide');
            }
        }
        
        // Scroll olaylarını dinle
        episodesGrid.addEventListener('scroll', updateArrowVisibility);
        
        // Sayfa yüklendiğinde kontrol et
        setTimeout(updateArrowVisibility, 100);
        
        // Sol ok tıklama - 2 kart kadar kaydır
        leftArrow.addEventListener('click', () => {
            const cardWidth = 320 + 20; // kart genişliği + gap
            const scrollAmount = cardWidth * 2;
            episodesGrid.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });
        
        // Sağ ok tıklama - 2 kart kadar kaydır
        rightArrow.addEventListener('click', () => {
            const cardWidth = 320 + 20; // kart genişliği + gap
            const scrollAmount = cardWidth * 2;
            episodesGrid.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
        
        // Touch/swipe desteği (mobile için)
        let touchStartX = 0;
        let touchEndX = 0;
        
        episodesGrid.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        episodesGrid.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                const cardWidth = 280 + 20; // mobile kart genişliği + gap
                const scrollAmount = cardWidth;
                
                if (diff > 0) {
                    // Sola kaydır (sonraki)
                    episodesGrid.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                } else {
                    // Sağa kaydır (önceki)
                    episodesGrid.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                }
            }
        }
        
        // Resize olayında buton durumunu güncelle
        window.addEventListener('resize', () => {
            setTimeout(updateArrowVisibility, 100);
        });
    });
}

// Season değiştiğinde navigation'ı yenile
function refreshNavigation() {
    // Mevcut navigation'ları temizle
    document.querySelectorAll('.episodes-navigation').forEach(nav => {
        const grid = nav.querySelector('.episodes-grid');
        if (grid) {
            nav.parentNode.insertBefore(grid, nav);
            nav.remove();
        }
    });
    
    // Yeni navigation'ları ekle
    setTimeout(() => {
        initEpisodeNavigation();
    }, 100);
}

// Sayfa yüklendiğinde navigation'ı başlat
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        initEpisodeNavigation();
    }, 500);
});

// Season değiştiğinde navigation'ı yenile
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('season-option')) {
        setTimeout(() => {
            refreshNavigation();
        }, 200);
    }
});

// Keyboard navigation (opsiyonel)
document.addEventListener('keydown', (e) => {
    const activeGrid = document.querySelector('.episodes-list.active .episodes-grid');
    if (activeGrid && (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA')) {
        const cardWidth = window.innerWidth <= 768 ? 300 : 340;
        
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            activeGrid.scrollBy({
                left: -cardWidth,
                behavior: 'smooth'
            });
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            activeGrid.scrollBy({
                left: cardWidth,
                behavior: 'smooth'
            });
        }
    }
});

// Spoiler warning functionality
document.querySelectorAll('.spoiler-warning').forEach(item => {
    item.addEventListener('click', event => {
        // Find the next element which is the spoiler content and show it
        const spoilerContent = item.nextElementSibling;
        if (spoilerContent) {
            spoilerContent.style.display = 'block';
        }
        // Hide the warning itself
        item.style.display = 'none';
    });
});
// This listener groups all our page interactions together.
document.addEventListener('DOMContentLoaded', function() {
    
    // This is a single, efficient listener for all clicks on dynamic content.
    document.body.addEventListener('click', function(e) {
        
        // --- LIKE BUTTON LOGIC ---
        const likeButton = e.target.closest('.like-btn');
        if (likeButton) {
            e.preventDefault();
            const commentId = likeButton.dataset.commentId;
            const likeCountSpan = likeButton.nextElementSibling;
            const formData = new FormData();
            formData.append('comment_id', commentId);

            fetch('/api/comments/toggle-like', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        likeCountSpan.textContent = data.newLikeCount;
                        likeButton.classList.toggle('liked', data.userLikes);
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // --- SPOILER LOGIC ---
        const spoilerWarning = e.target.closest('.spoiler-warning');
        if (spoilerWarning) {
            const spoilerContent = spoilerWarning.nextElementSibling;
            if (spoilerContent) spoilerContent.style.display = 'block';
            spoilerWarning.style.display = 'none';
        }

        // --- REPLY BUTTON LOGIC ---
        const replyButton = e.target.closest('.reply-btn');
        if (replyButton) {
            e.preventDefault();
            const formContainer = replyButton.closest('.comment-container').querySelector('.reply-form-container');
            if (formContainer.innerHTML !== '') {
                formContainer.innerHTML = '';
                return;
            }
            const template = document.getElementById('reply-form-template');
            if (template) {
                const formClone = template.cloneNode(true);
                formClone.removeAttribute('id');
                formClone.style.display = 'block';
                const commentId = replyButton.dataset.commentId;
                formClone.querySelector('.parent-id-input').value = commentId;
                formContainer.appendChild(formClone);
            }
        }

        // --- CANCEL REPLY BUTTON LOGIC ---
        const cancelButton = e.target.closest('.cancel-reply-btn');
        if (cancelButton) {
            e.preventDefault();
            const formToRemove = cancelButton.closest('form');
            if (formToRemove) formToRemove.remove();
        }

        // This is the new logic to add inside your existing document.body.addEventListener('click', function(e) { ... });

// --- VIEW REPLIES LOGIC ---
const viewRepliesButton = e.target.closest('.view-replies-btn');
if (viewRepliesButton) {
    e.preventDefault();
    const targetId = viewRepliesButton.dataset.target;
    const repliesWrapper = document.getElementById(targetId);
    
    if (repliesWrapper) {
        // Show the replies
        repliesWrapper.style.display = 'block';
        // Hide the "View Replies" button itself
        viewRepliesButton.parentElement.style.display = 'none';
    }
}
    });

    // Your other DOMContentLoaded code for season dropdowns, etc. can remain
    // as it is.
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>