<?php
/**
 * Plugin Name: Social Review Slider
 * Plugin URI: https://yourwebsite.com/social-review-slider
 * Description: Embed TripAdvisor, Google, and Airbnb reviews as a beautiful slider with ratings and platform logos.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: social-review-slider
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SRS_VERSION', '1.0.0');
define('SRS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SRS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SRS_PLUGIN_DIR . 'includes/class-review-post-type.php';
require_once SRS_PLUGIN_DIR . 'includes/class-review-meta-boxes.php';
require_once SRS_PLUGIN_DIR . 'includes/class-review-shortcode.php';
require_once SRS_PLUGIN_DIR . 'includes/class-review-widget.php';
require_once SRS_PLUGIN_DIR . 'includes/class-review-importer.php';

/**
 * Main Plugin Class
 */
class Social_Review_Slider {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Initialize components
        SRS_Review_Post_Type::get_instance();
        SRS_Review_Meta_Boxes::get_instance();
        SRS_Review_Shortcode::get_instance();
        SRS_Review_Importer::get_instance();
        
        // Register widget
        add_action('widgets_init', function() {
            register_widget('SRS_Review_Widget');
        });
    }
    
    public function enqueue_scripts() {
        // Enqueue Swiper CSS
        wp_enqueue_style(
            'swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.0'
        );
        
        // Enqueue plugin CSS
        wp_enqueue_style(
            'srs-styles',
            SRS_PLUGIN_URL . 'assets/css/styles.css',
            array('swiper-css'),
            SRS_VERSION
        );
        
        // Enqueue Swiper JS
        wp_enqueue_script(
            'swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.0',
            true
        );
        
        // Enqueue plugin JS
        wp_enqueue_script(
            'srs-scripts',
            SRS_PLUGIN_URL . 'assets/js/scripts.js',
            array('jquery', 'swiper-js'),
            SRS_VERSION,
            true
        );
    }
    
    public function admin_enqueue_scripts($hook) {
        // Only load on review edit pages
        global $post_type;
        if ('srs_review' !== $post_type && strpos($hook, 'srs-import-settings') === false) {
            return;
        }
        
        wp_enqueue_style(
            'srs-admin-styles',
            SRS_PLUGIN_URL . 'assets/css/admin-styles.css',
            array(),
            SRS_VERSION
        );
        
        wp_enqueue_script(
            'srs-admin-scripts',
            SRS_PLUGIN_URL . 'assets/js/admin-scripts.js',
            array('jquery'),
            SRS_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('srs-admin-scripts', 'srsAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'manualImportNonce' => wp_create_nonce('srs_manual_import'),
            'testConnectionNonce' => wp_create_nonce('srs_test_connection'),
        ));
        
        wp_enqueue_media();
    }
    
    public function activate() {
        // Register post type
        SRS_Review_Post_Type::get_instance()->register_post_type();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Schedule cron job for automatic imports
        if (!wp_next_scheduled('srs_import_reviews_cron')) {
            $frequency = get_option('srs_import_frequency', 'daily');
            wp_schedule_event(time(), $frequency, 'srs_import_reviews_cron');
        }
    }
    
    public function deactivate() {
        // Clear scheduled cron job
        $timestamp = wp_next_scheduled('srs_import_reviews_cron');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'srs_import_reviews_cron');
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

// Initialize the plugin
Social_Review_Slider::get_instance();
