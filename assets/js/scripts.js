/**
 * Frontend Scripts for Social Review Slider
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Add smooth scroll behavior
        $('.srs-review-link').on('click', function(e) {
            // Let the link open normally
            // Add any additional tracking or analytics here if needed
        });
        
        // Add keyboard navigation support
        $('.srs-review-slider-wrapper').each(function() {
            const $slider = $(this);
            
            $slider.on('keydown', function(e) {
                const swiper = $slider.find('.swiper')[0].swiper;
                
                if (!swiper) return;
                
                switch(e.key) {
                    case 'ArrowLeft':
                        e.preventDefault();
                        swiper.slidePrev();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        swiper.slideNext();
                        break;
                }
            });
        });
        
        // Add accessibility attributes
        $('.srs-review-card').attr('role', 'article');
        $('.srs-rating').attr('aria-label', function() {
            const rating = $(this).find('.srs-rating-number').text();
            return 'Rating: ' + rating + ' out of 5 stars';
        });
        
        // Lazy load images if needed
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });
            
            $('.srs-reviewer-avatar img[data-src]').each(function() {
                imageObserver.observe(this);
            });
        }
        
        // Add animation on scroll
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('srs-animated');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            $('.srs-review-card').each(function() {
                animationObserver.observe(this);
            });
        }
    });
    
})(jQuery);
