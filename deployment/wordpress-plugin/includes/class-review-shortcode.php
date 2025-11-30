<?php
/**
 * Review Shortcode Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SRS_Review_Shortcode {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('social_reviews', array($this, 'render_shortcode'));
    }
    
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'platform'       => 'all', // all, tripadvisor, google, airbnb
            'count'          => -1,
            'autoplay'       => 'true',
            'autoplay_delay' => '5000',
            'loop'           => 'true',
            'slides_per_view' => '1',
            'space_between'  => '30',
            'show_navigation' => 'true',
            'show_pagination' => 'true',
            'random'         => 'true',
        ), $atts);
        
        // Query args
        $query_args = array(
            'post_type'      => 'srs_review',
            'posts_per_page' => intval($atts['count']),
            'post_status'    => 'publish',
        );
        
        // Filter by platform
        if ($atts['platform'] !== 'all') {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'srs_platform',
                    'field'    => 'slug',
                    'terms'    => $atts['platform'],
                ),
            );
        }
        
        // Random order
        if ($atts['random'] === 'true') {
            $query_args['orderby'] = 'rand';
        } else {
            $query_args['orderby'] = 'date';
            $query_args['order'] = 'DESC';
        }
        
        $reviews = new WP_Query($query_args);
        
        if (!$reviews->have_posts()) {
            return '<p>' . __('No reviews found.', 'social-review-slider') . '</p>';
        }
        
        // Generate unique ID for this slider
        $slider_id = 'srs-slider-' . uniqid();
        
        ob_start();
        ?>
        <div class="srs-review-slider-wrapper" data-slider-id="<?php echo esc_attr($slider_id); ?>">
            <div class="swiper <?php echo esc_attr($slider_id); ?>">
                <div class="swiper-wrapper">
                    <?php while ($reviews->have_posts()): $reviews->the_post(); ?>
                        <?php
                        $reviewer_name = get_post_meta(get_the_ID(), '_srs_reviewer_name', true);
                        $rating = get_post_meta(get_the_ID(), '_srs_rating', true);
                        $review_date = get_post_meta(get_the_ID(), '_srs_review_date', true);
                        $review_url = get_post_meta(get_the_ID(), '_srs_review_url', true);
                        $reviewer_avatar = get_post_meta(get_the_ID(), '_srs_reviewer_avatar', true);
                        $reviewer_location = get_post_meta(get_the_ID(), '_srs_reviewer_location', true);
                        
                        // Get platform
                        $platforms = wp_get_post_terms(get_the_ID(), 'srs_platform');
                        $platform_name = !empty($platforms) ? $platforms[0]->name : '';
                        $platform_slug = !empty($platforms) ? $platforms[0]->slug : '';
                        ?>
                        <div class="swiper-slide">
                            <div class="srs-review-card" data-platform="<?php echo esc_attr($platform_slug); ?>">
                                <div class="srs-review-header">
                                    <div class="srs-reviewer-info">
                                        <?php if ($reviewer_avatar): ?>
                                            <div class="srs-reviewer-avatar">
                                                <img src="<?php echo esc_url($reviewer_avatar); ?>" alt="<?php echo esc_attr($reviewer_name); ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="srs-reviewer-details">
                                            <h3 class="srs-reviewer-name"><?php echo esc_html($reviewer_name); ?></h3>
                                            <?php if ($reviewer_location): ?>
                                                <p class="srs-reviewer-location"><?php echo esc_html($reviewer_location); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if ($platform_name): ?>
                                        <div class="srs-platform-badge" data-platform="<?php echo esc_attr($platform_slug); ?>">
                                            <span class="srs-platform-icon">
                                                <?php echo $this->get_platform_icon($platform_slug); ?>
                                            </span>
                                            <span class="srs-platform-name"><?php echo esc_html($platform_name); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($rating): ?>
                                    <div class="srs-rating">
                                        <?php echo $this->render_stars($rating); ?>
                                        <span class="srs-rating-number"><?php echo esc_html($rating); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="srs-review-content">
                                    <?php the_content(); ?>
                                </div>
                                
                                <div class="srs-review-footer">
                                    <?php if ($review_date): ?>
                                        <span class="srs-review-date"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($review_date))); ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if ($review_url): ?>
                                        <a href="<?php echo esc_url($review_url); ?>" target="_blank" rel="noopener noreferrer" class="srs-review-link">
                                            <?php _e('View Original', 'social-review-slider'); ?>
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                <path d="M10.5 1.5L1.5 10.5M10.5 1.5H4.5M10.5 1.5V7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <?php if ($atts['show_pagination'] === 'true'): ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
                
                <?php if ($atts['show_navigation'] === 'true'): ?>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                <?php endif; ?>
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.<?php echo esc_js($slider_id); ?>', {
                slidesPerView: <?php echo intval($atts['slides_per_view']); ?>,
                spaceBetween: <?php echo intval($atts['space_between']); ?>,
                loop: <?php echo $atts['loop'] === 'true' ? 'true' : 'false'; ?>,
                autoplay: <?php echo $atts['autoplay'] === 'true' ? '{delay: ' . intval($atts['autoplay_delay']) . ', disableOnInteraction: false}' : 'false'; ?>,
                pagination: {
                    el: '.<?php echo esc_js($slider_id); ?> .swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.<?php echo esc_js($slider_id); ?> .swiper-button-next',
                    prevEl: '.<?php echo esc_js($slider_id); ?> .swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: Math.min(2, <?php echo intval($atts['slides_per_view']); ?>),
                    },
                    1024: {
                        slidesPerView: <?php echo intval($atts['slides_per_view']); ?>,
                    },
                },
            });
        });
        </script>
        <?php
        
        wp_reset_postdata();
        
        return ob_get_clean();
    }
    
    private function render_stars($rating) {
        $full_stars = floor($rating);
        $half_star = ($rating - $full_stars) >= 0.5;
        $empty_stars = 5 - ceil($rating);
        
        $output = '<div class="srs-stars">';
        
        // Full stars
        for ($i = 0; $i < $full_stars; $i++) {
            $output .= '<span class="srs-star srs-star-full">★</span>';
        }
        
        // Half star
        if ($half_star) {
            $output .= '<span class="srs-star srs-star-half">★</span>';
        }
        
        // Empty stars
        for ($i = 0; $i < $empty_stars; $i++) {
            $output .= '<span class="srs-star srs-star-empty">★</span>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    private function get_platform_icon($platform) {
        $icons = array(
            'tripadvisor' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm8 0c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/></svg>',
            'google' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>',
            'airbnb' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm0 22C6.5 22 2 17.5 2 12S6.5 2 12 2s10 4.5 10 10-4.5 10-10 10zm5-13c-1.7 0-3 1.3-3 3v5h2v-5c0-.6.4-1 1-1s1 .4 1 1v5h2v-5c0-1.7-1.3-3-3-3zm-10 0c-1.7 0-3 1.3-3 3v5h2v-5c0-.6.4-1 1-1s1 .4 1 1v5h2v-5c0-1.7-1.3-3-3-3z"/></svg>',
        );
        
        return isset($icons[$platform]) ? $icons[$platform] : '';
    }
}
