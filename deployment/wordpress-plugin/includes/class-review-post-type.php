<?php
/**
 * Review Post Type Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SRS_Review_Post_Type {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomy'));
    }
    
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Reviews', 'Post Type General Name', 'social-review-slider'),
            'singular_name'         => _x('Review', 'Post Type Singular Name', 'social-review-slider'),
            'menu_name'             => __('Reviews', 'social-review-slider'),
            'name_admin_bar'        => __('Review', 'social-review-slider'),
            'archives'              => __('Review Archives', 'social-review-slider'),
            'attributes'            => __('Review Attributes', 'social-review-slider'),
            'parent_item_colon'     => __('Parent Review:', 'social-review-slider'),
            'all_items'             => __('All Reviews', 'social-review-slider'),
            'add_new_item'          => __('Add New Review', 'social-review-slider'),
            'add_new'               => __('Add New', 'social-review-slider'),
            'new_item'              => __('New Review', 'social-review-slider'),
            'edit_item'             => __('Edit Review', 'social-review-slider'),
            'update_item'           => __('Update Review', 'social-review-slider'),
            'view_item'             => __('View Review', 'social-review-slider'),
            'view_items'            => __('View Reviews', 'social-review-slider'),
            'search_items'          => __('Search Review', 'social-review-slider'),
            'not_found'             => __('Not found', 'social-review-slider'),
            'not_found_in_trash'    => __('Not found in Trash', 'social-review-slider'),
        );
        
        $args = array(
            'label'                 => __('Review', 'social-review-slider'),
            'description'           => __('Social platform reviews', 'social-review-slider'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 25,
            'menu_icon'             => 'dashicons-star-filled',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'post',
            'show_in_rest'          => false,
        );
        
        register_post_type('srs_review', $args);
    }
    
    public function register_taxonomy() {
        $labels = array(
            'name'              => _x('Platforms', 'taxonomy general name', 'social-review-slider'),
            'singular_name'     => _x('Platform', 'taxonomy singular name', 'social-review-slider'),
            'search_items'      => __('Search Platforms', 'social-review-slider'),
            'all_items'         => __('All Platforms', 'social-review-slider'),
            'parent_item'       => __('Parent Platform', 'social-review-slider'),
            'parent_item_colon' => __('Parent Platform:', 'social-review-slider'),
            'edit_item'         => __('Edit Platform', 'social-review-slider'),
            'update_item'       => __('Update Platform', 'social-review-slider'),
            'add_new_item'      => __('Add New Platform', 'social-review-slider'),
            'new_item_name'     => __('New Platform Name', 'social-review-slider'),
            'menu_name'         => __('Platforms', 'social-review-slider'),
        );
        
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'review-platform'),
            'show_in_rest'      => false,
        );
        
        register_taxonomy('srs_platform', array('srs_review'), $args);
        
        // Add default platforms
        $this->add_default_platforms();
    }
    
    private function add_default_platforms() {
        $platforms = array(
            'tripadvisor' => 'TripAdvisor',
            'google'      => 'Google',
            'airbnb'      => 'Airbnb',
        );
        
        foreach ($platforms as $slug => $name) {
            if (!term_exists($slug, 'srs_platform')) {
                wp_insert_term($name, 'srs_platform', array('slug' => $slug));
            }
        }
    }
}
