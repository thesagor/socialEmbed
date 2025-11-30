<?php
/**
 * Review Widget Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SRS_Review_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'srs_review_widget',
            __('Social Review Slider', 'social-review-slider'),
            array(
                'description' => __('Display social platform reviews in a slider', 'social-review-slider'),
            )
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $shortcode_atts = array(
            'platform'        => !empty($instance['platform']) ? $instance['platform'] : 'all',
            'count'           => !empty($instance['count']) ? $instance['count'] : -1,
            'autoplay'        => !empty($instance['autoplay']) ? 'true' : 'false',
            'autoplay_delay'  => !empty($instance['autoplay_delay']) ? $instance['autoplay_delay'] : '5000',
            'slides_per_view' => !empty($instance['slides_per_view']) ? $instance['slides_per_view'] : '1',
            'random'          => !empty($instance['random']) ? 'true' : 'false',
        );
        
        $shortcode = '[social_reviews';
        foreach ($shortcode_atts as $key => $value) {
            $shortcode .= ' ' . $key . '="' . esc_attr($value) . '"';
        }
        $shortcode .= ']';
        
        echo do_shortcode($shortcode);
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $platform = !empty($instance['platform']) ? $instance['platform'] : 'all';
        $count = !empty($instance['count']) ? $instance['count'] : -1;
        $autoplay = !empty($instance['autoplay']) ? $instance['autoplay'] : true;
        $autoplay_delay = !empty($instance['autoplay_delay']) ? $instance['autoplay_delay'] : '5000';
        $slides_per_view = !empty($instance['slides_per_view']) ? $instance['slides_per_view'] : '1';
        $random = !empty($instance['random']) ? $instance['random'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'social-review-slider'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('platform')); ?>"><?php _e('Platform:', 'social-review-slider'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('platform')); ?>" name="<?php echo esc_attr($this->get_field_name('platform')); ?>">
                <option value="all" <?php selected($platform, 'all'); ?>><?php _e('All Platforms', 'social-review-slider'); ?></option>
                <option value="tripadvisor" <?php selected($platform, 'tripadvisor'); ?>><?php _e('TripAdvisor', 'social-review-slider'); ?></option>
                <option value="google" <?php selected($platform, 'google'); ?>><?php _e('Google', 'social-review-slider'); ?></option>
                <option value="airbnb" <?php selected($platform, 'airbnb'); ?>><?php _e('Airbnb', 'social-review-slider'); ?></option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>"><?php _e('Number of Reviews (-1 for all):', 'social-review-slider'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>" type="number" value="<?php echo esc_attr($count); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('slides_per_view')); ?>"><?php _e('Slides Per View:', 'social-review-slider'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('slides_per_view')); ?>" name="<?php echo esc_attr($this->get_field_name('slides_per_view')); ?>" type="number" min="1" max="5" value="<?php echo esc_attr($slides_per_view); ?>">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($autoplay, true); ?> id="<?php echo esc_attr($this->get_field_id('autoplay')); ?>" name="<?php echo esc_attr($this->get_field_name('autoplay')); ?>" value="1">
            <label for="<?php echo esc_attr($this->get_field_id('autoplay')); ?>"><?php _e('Enable Autoplay', 'social-review-slider'); ?></label>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('autoplay_delay')); ?>"><?php _e('Autoplay Delay (ms):', 'social-review-slider'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('autoplay_delay')); ?>" name="<?php echo esc_attr($this->get_field_name('autoplay_delay')); ?>" type="number" value="<?php echo esc_attr($autoplay_delay); ?>">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($random, true); ?> id="<?php echo esc_attr($this->get_field_id('random')); ?>" name="<?php echo esc_attr($this->get_field_name('random')); ?>" value="1">
            <label for="<?php echo esc_attr($this->get_field_id('random')); ?>"><?php _e('Random Order', 'social-review-slider'); ?></label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['platform'] = !empty($new_instance['platform']) ? sanitize_text_field($new_instance['platform']) : 'all';
        $instance['count'] = !empty($new_instance['count']) ? intval($new_instance['count']) : -1;
        $instance['autoplay'] = !empty($new_instance['autoplay']) ? true : false;
        $instance['autoplay_delay'] = !empty($new_instance['autoplay_delay']) ? intval($new_instance['autoplay_delay']) : 5000;
        $instance['slides_per_view'] = !empty($new_instance['slides_per_view']) ? intval($new_instance['slides_per_view']) : 1;
        $instance['random'] = !empty($new_instance['random']) ? true : false;
        
        return $instance;
    }
}
