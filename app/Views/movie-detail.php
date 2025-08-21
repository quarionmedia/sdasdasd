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

    .movie-detail-container {
        position: relative;
        min-height: 100vh;
    }

    .hero-section {
        position: relative;
        height: 100vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: flex-end;
    }

    .hero-gradient {
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

    .movie-logo {
        max-width: 400px;
        height: auto;
        margin-bottom: 30px;
        align-self: flex-start;
    }

    .movie-title {
        font-size: 4rem;
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        text-align: left;
    }

    .movie-meta {
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

    .movie-description {
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

    .icon-btn {
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

    .icon-btn:hover {
        background: rgba(42, 42, 42, 1);
        border-color: #fff;
        transform: scale(1.1);
    }

    .content-section {
        background: linear-gradient(180deg, #000 0%, #0a0a0a 50%, #111 100%);
        padding: 60px 60px 40px;
        width: 100%;
        position: relative;
    }

    .content-section::before {
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
    .cast-container {
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

    .cast-member {
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

    .cast-member::before {
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

    .cast-member:hover::before {
        opacity: 0.3;
    }

    .cast-member:hover {
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

    .cast-member:hover .cast-avatar {
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

    .cast-member:hover .cast-name {
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

    .cast-member:hover .cast-character {
        color: #aaa;
        transform: translateY(-2px);
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

    /* RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .hero-content {
            margin-left: 30px;
            padding: 0 30px 60px 0;
        }

        .movie-title {
            font-size: 2.5rem;
        }

        .movie-meta {
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

        .content-section, 
        .extra-details-section, 
        .comments-section {
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

<div class="movie-detail-container">
    <div class="hero-section" style="background-image: url('https://image.tmdb.org/t/p/original<?php echo $movie['backdrop_path']; ?>');">
        <div class="hero-gradient"></div>
        <div class="hero-content">
            <?php if (!empty($movie['logo_path'])): ?>
                <img class="movie-logo" src="https://image.tmdb.org/t/p/w500<?php echo $movie['logo_path']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
            <?php else: ?>
                <h1 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h1>
            <?php endif; ?>

            <div class="movie-meta">
                <span class="meta-item"><?php echo !empty($movie['certification']) ? htmlspecialchars($movie['certification']) : 'CBFC:U/A'; ?></span>
                <?php if (!empty($movie['genres'])): ?>
                    <span class="meta-item"><?php echo implode(', ', array_slice(array_map(fn($g) => $g['name'], $movie['genres']), 0, 2)); ?></span>
                <?php endif; ?>
                <span class="meta-item"><?php echo htmlspecialchars($movie['runtime']); ?>m</span>
            </div>

            <p class="movie-description"><?php echo htmlspecialchars($movie['overview']); ?></p>

            <div class="action-buttons">
    <div class="main-buttons">
        <a href="/movie/<?php echo htmlspecialchars($movie['slug']); ?>/watch" class="btn btn-watch">
            <i class="fas fa-play"></i>
            Watch Now
        </a>
        <button class="btn btn-watchlist">
            <i class="fas fa-plus"></i>
            Add Watchlist
        </button>
    </div>
    
    <div class="icon-buttons">
        <div class="icon-btn">
            <i class="fas fa-heart"></i>
        </div>
        <div class="icon-btn">
            <i class="fas fa-download"></i>
        </div>
        <div class="icon-btn">
            <i class="fas fa-share"></i>
        </div>
    </div>
</div>
        </div>
    </div>

    <div class="content-section">
        <?php if (!empty($movie['cast'])): ?>
        <div class="cast-container">
            <h2 class="section-title">Top Cast</h2>
            <div class="cast-grid">
                <?php foreach (array_slice($movie['cast'], 0, 8) as $person): ?>
                    <div class="cast-member">
                        <img class="cast-avatar" 
                             src="<?php echo !empty($person['profile_path']) ? 'https://image.tmdb.org/t/p/w200'.$person['profile_path'] : 'https://via.placeholder.com/100'; ?>" 
                             alt="<?php echo htmlspecialchars($person['name']); ?>">
                        <div class="cast-name"><?php echo htmlspecialchars($person['name']); ?></div>
                        <div class="cast-character"><?php echo htmlspecialchars($person['character_name']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
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
    // These functions are called by onclick="" attributes in your HTML, so they remain global.
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

    // This listener groups all our page interactions together.
    document.addEventListener('DOMContentLoaded', function() {
    
        // Close popup with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopup();
            }
        });

        // Close popup when clicking outside content area
        const mediaPopup = document.getElementById('mediaPopup');
        if (mediaPopup) {
            mediaPopup.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePopup();
                }
            });
        }
        
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
                if (spoilerContent) {
                    spoilerContent.style.display = 'block';
                }
                spoilerWarning.style.display = 'none';
            }

            // --- NEW: REPLY BUTTON LOGIC ---
            const replyButton = e.target.closest('.reply-btn');
            if (replyButton) {
                e.preventDefault();
                const formContainer = replyButton.closest('.comment-container').querySelector('.reply-form-container');
                
                // If a form is already open in this container, close it. Otherwise, open one.
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

            // --- NEW: CANCEL REPLY BUTTON LOGIC ---
            const cancelButton = e.target.closest('.cancel-reply-btn');
            if (cancelButton) {
                e.preventDefault();
                const formToRemove = cancelButton.closest('form');
                if (formToRemove) {
                    formToRemove.remove();
                }
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
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>