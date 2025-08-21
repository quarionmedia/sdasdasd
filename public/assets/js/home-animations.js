document.addEventListener('DOMContentLoaded', function() {
    let hoverTimeout;
    let leaveTimeout;
    let activeCard = null;

    document.querySelectorAll('.poster-card').forEach(card => {
        const panel = card.querySelector('.preview-panel');
        const slide = card.closest('.swiper-slide');
        const section = card.closest('.content-section');
        if (!panel || !slide || !section) return;

        // Store the original image source for later use
        const staticImage = panel.querySelector('.preview-media img');
        card.originalImage = staticImage ? staticImage.cloneNode(true) : null;

        card.addEventListener('mouseenter', (e) => {
            clearTimeout(leaveTimeout);
            hoverTimeout = setTimeout(() => {
                // Deactivate any other active card first
                if (activeCard && activeCard !== card) {
                    const otherPanel = activeCard.querySelector('.preview-panel');
                    otherPanel.classList.remove('active');
                    activeCard.closest('.content-section').classList.remove('is-hovered');
                    const otherMedia = otherPanel.querySelector('.preview-media');
                    otherMedia.innerHTML = ''; // Clear iframe
                    if(activeCard.originalImage) otherMedia.appendChild(activeCard.originalImage.cloneNode(true));
                }
                
                activeCard = card;
                
                // Elevate the entire section to prevent overlap from rows below
                section.classList.add('is-hovered');

                // Position the panel
                const cardRect = card.getBoundingClientRect();
                const container = card.closest('.swiper-container');
                const containerRect = container.getBoundingClientRect();

                panel.style.left = '';
                panel.style.right = '';
                panel.style.transformOrigin = 'center';

                let left = cardRect.left - containerRect.left - (panel.offsetWidth - cardRect.width) / 2;
                
                if (left + panel.offsetWidth > container.offsetWidth) {
                    left = cardRect.right - containerRect.left - panel.offsetWidth;
                    panel.style.transformOrigin = 'right center';
                }
                if (left < 0) {
                    left = cardRect.left - containerRect.left;
                    panel.style.transformOrigin = 'left center';
                }
                
                panel.style.left = `${left}px`;
                panel.style.top = `-${(panel.offsetHeight - cardRect.height) / 2}px`;
                
                panel.classList.add('active');
                
                const trailerKey = card.dataset.trailerKey;
                const mediaContainer = panel.querySelector('.preview-media');
                if (trailerKey) {
                    const iframe = document.createElement('iframe');
                    iframe.src = `https://www.youtube.com/embed/${trailerKey}?autoplay=1&mute=1&controls=0&loop=1&playlist=${trailerKey}&showinfo=0&iv_load_policy=3`;
                    iframe.allow = 'autoplay; encrypted-media';
                    mediaContainer.innerHTML = ''; // Clear the static image
                    mediaContainer.appendChild(iframe);
                }

            }, 500); // 500ms delay before showing
        });

        panel.addEventListener('mouseleave', () => {
            clearTimeout(hoverTimeout);
            panel.classList.remove('active');
            section.classList.remove('is-hovered');
            
            const mediaContainer = panel.querySelector('.preview-media');
            mediaContainer.innerHTML = ''; // Clear iframe
            if(card.originalImage) mediaContainer.appendChild(card.originalImage.cloneNode(true));
            
            activeCard = null;
        });
        
        const volumeBtn = panel.querySelector('.volume-btn');
        let isMuted = true;
        volumeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const iframe = panel.querySelector('iframe');
            if (iframe) {
                isMuted = !isMuted;
                let newSrc = iframe.src;
                if(isMuted) {
                    newSrc = newSrc.replace('&mute=0', '&mute=1');
                } else {
                    newSrc = newSrc.replace('&mute=1', '&mute=0');
                }
                iframe.src = newSrc;
                volumeBtn.innerHTML = isMuted ? '<i class="fas fa-volume-mute"></i>' : '<i class="fas fa-volume-up"></i>';
            }
        });
    });

    // Initialize all carousels
    new Swiper('.content-carousel, .trending-carousel', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        grabCursor: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    
    // Initialize the hero carousel
    new Swiper('.hero-carousel', {
        loop: true,
        effect: 'fade',
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });
});