<?php
/**
 * Review Importer Class
 * Handles automatic import of reviews from TripAdvisor, Google, and Airbnb
 */

if (!defined('ABSPATH')) {
    exit;
}

class SRS_Review_Importer {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('srs_import_reviews_cron', array($this, 'import_all_reviews'));
        add_action('wp_ajax_srs_manual_import', array($this, 'manual_import'));
        add_action('wp_ajax_srs_test_connection', array($this, 'test_api_connection'));
    }
    
    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=srs_review',
            __('Import Settings', 'social-review-slider'),
            __('Import Settings', 'social-review-slider'),
            'manage_options',
            'srs-import-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        // General Settings
        register_setting('srs_import_settings', 'srs_auto_import_enabled');
        register_setting('srs_import_settings', 'srs_import_frequency');
        register_setting('srs_import_settings', 'srs_import_limit');
        
        // AI Scraper Service Settings
        register_setting('srs_import_settings', 'srs_scraper_service_url');
        register_setting('srs_import_settings', 'srs_scraper_api_key');
        
        // Platform URLs
        register_setting('srs_import_settings', 'srs_google_place_url');
        register_setting('srs_import_settings', 'srs_tripadvisor_hotel_url');
        register_setting('srs_import_settings', 'srs_airbnb_listing_url');
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap srs-import-settings">
            <h1><?php _e('Review Import Settings', 'social-review-slider'); ?></h1>
            
            <div class="srs-settings-header">
                <p class="description">
                    <?php _e('Configure automatic import of reviews from TripAdvisor, Google, and Airbnb. The plugin will fetch new reviews automatically based on your schedule.', 'social-review-slider'); ?>
                </p>
            </div>
            
            <form method="post" action="options.php">
                <?php settings_fields('srs_import_settings'); ?>
                
                <!-- General Settings -->
                <div class="srs-settings-section">
                    <h2><?php _e('General Settings', 'social-review-slider'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="srs_auto_import_enabled"><?php _e('Enable Auto Import', 'social-review-slider'); ?></label>
                            </th>
                            <td>
                                <label>
                                    <input type="checkbox" id="srs_auto_import_enabled" name="srs_auto_import_enabled" value="1" <?php checked(get_option('srs_auto_import_enabled'), 1); ?>>
                                    <?php _e('Automatically import reviews on schedule', 'social-review-slider'); ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="srs_import_frequency"><?php _e('Import Frequency', 'social-review-slider'); ?></label>
                            </th>
                            <td>
                                <select id="srs_import_frequency" name="srs_import_frequency">
                                    <option value="hourly" <?php selected(get_option('srs_import_frequency', 'daily'), 'hourly'); ?>><?php _e('Every Hour', 'social-review-slider'); ?></option>
                                    <option value="twicedaily" <?php selected(get_option('srs_import_frequency', 'daily'), 'twicedaily'); ?>><?php _e('Twice Daily', 'social-review-slider'); ?></option>
                                    <option value="daily" <?php selected(get_option('srs_import_frequency', 'daily'), 'daily'); ?>><?php _e('Daily', 'social-review-slider'); ?></option>
                                    <option value="weekly" <?php selected(get_option('srs_import_frequency', 'daily'), 'weekly'); ?>><?php _e('Weekly', 'social-review-slider'); ?></option>
                                </select>
                                <p class="description"><?php _e('How often to check for new reviews', 'social-review-slider'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="srs_import_limit"><?php _e('Import Limit', 'social-review-slider'); ?></label>
                            </th>
                            <td>
                                <input type="number" id="srs_import_limit" name="srs_import_limit" value="<?php echo esc_attr(get_option('srs_import_limit', '50')); ?>" min="1" max="100" class="small-text">
                                <p class="description"><?php _e('Maximum number of reviews to import per platform', 'social-review-slider'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- AI Scraper Service Settings -->
                <div class="srs-settings-section">
                    <h2>
                        <span class="dashicons dashicons-cloud" style="color: #7C3AED;"></span>
                        <?php _e('AI Scraper Service', 'social-review-slider'); ?>
                    </h2>
                    <div class="notice notice-info inline">
                        <p>
                            <strong><?php _e('Hassle-Free Setup!', 'social-review-slider'); ?></strong><br>
                            <?php _e('No need for multiple API keys. Our AI-powered scraper service handles everything for you.', 'social-review-slider'); ?><br>
                            <strong><?php _e('Cost:', 'social-review-slider'); ?></strong> ~$0.0001 per review (100x cheaper than commercial APIs!)
                        </p>
                    </div>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="srs_scraper_service_url"><?php _e('Scraper Service URL', 'social-review-slider'); ?></label>
                            </th>
                            <td>
                                <input type="url" id="srs_scraper_service_url" name="srs_scraper_service_url" value="<?php echo esc_attr(get_option('srs_scraper_service_url', 'http://localhost:3000')); ?>" class="regular-text">
                                <p class="description">
                                    <?php _e('URL of your AI scraper service (default: http://localhost:3000)', 'social-review-slider'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="srs_scraper_api_key"><?php _e('API Secret Key', 'social-review-slider'); ?></label>
                            </th>
                            <td>
                                <input type="password" id="srs_scraper_api_key" name="srs_scraper_api_key" value="<?php echo esc_attr(get_option('srs_scraper_api_key')); ?>" class="regular-text">
                                <p class="description">
                                    <?php _e('The API_SECRET_KEY from your scraper service .env file', 'social-review-slider'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Platform URLs -->
                <div class="srs-settings-section">
                    <h2><?php _e('Platform URLs', 'social-review-slider'); ?></h2>
                    <p class="description">
                        <?php _e('Simply paste the URLs to your business listings. No API keys needed!', 'social-review-slider'); ?>
                    </p>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="srs_google_place_url">
                                    <span class="dashicons dashicons-google" style="color: #4285F4;"></span>
                                    <?php _e('Google Business URL', 'social-review-slider'); ?>
                                </label>
                            </th>
                            <td>
                                <input type="url" id="srs_google_place_url" name="srs_google_place_url" value="<?php echo esc_attr(get_option('srs_google_place_url')); ?>" class="large-text">
                                <p class="description">
                                    <?php _e('Example: https://www.google.com/maps/place/Your+Business/@...', 'social-review-slider'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="srs_tripadvisor_hotel_url">
                                    <span class="dashicons dashicons-palmtree" style="color: #34E0A1;"></span>
                                    <?php _e('TripAdvisor URL', 'social-review-slider'); ?>
                                </label>
                            </th>
                            <td>
                                <input type="url" id="srs_tripadvisor_hotel_url" name="srs_tripadvisor_hotel_url" value="<?php echo esc_attr(get_option('srs_tripadvisor_hotel_url')); ?>" class="large-text">
                                <p class="description">
                                    <?php _e('Example: https://www.tripadvisor.com/Hotel_Review-g...-Reviews-Your_Hotel.html', 'social-review-slider'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="srs_airbnb_listing_url">
                                    <span class="dashicons dashicons-admin-home" style="color: #FF5A5F;"></span>
                                    <?php _e('Airbnb URL', 'social-review-slider'); ?>
                                </label>
                            </th>
                            <td>
                                <input type="url" id="srs_airbnb_listing_url" name="srs_airbnb_listing_url" value="<?php echo esc_attr(get_option('srs_airbnb_listing_url')); ?>" class="large-text">
                                <p class="description">
                                    <?php _e('Example: https://www.airbnb.com/rooms/12345678', 'social-review-slider'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <?php submit_button(); ?>
            </form>
            
            <!-- Manual Import Section -->
            <div class="srs-settings-section">
                <h2><?php _e('Manual Import', 'social-review-slider'); ?></h2>
                <p><?php _e('Click the button below to import reviews immediately from all configured platforms.', 'social-review-slider'); ?></p>
                <button type="button" class="button button-primary button-large srs-manual-import">
                    <span class="dashicons dashicons-update"></span>
                    <?php _e('Import Reviews Now', 'social-review-slider'); ?>
                </button>
                <div class="srs-import-status" style="margin-top: 15px;"></div>
            </div>
            
            <!-- Import Log -->
            <div class="srs-settings-section">
                <h2><?php _e('Recent Import Activity', 'social-review-slider'); ?></h2>
                <?php $this->render_import_log(); ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render import log
     */
    private function render_import_log() {
        $log = get_option('srs_import_log', array());
        
        if (empty($log)) {
            echo '<p>' . __('No import activity yet.', 'social-review-slider') . '</p>';
            return;
        }
        
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>' . __('Date/Time', 'social-review-slider') . '</th>';
        echo '<th>' . __('Platform', 'social-review-slider') . '</th>';
        echo '<th>' . __('Reviews Imported', 'social-review-slider') . '</th>';
        echo '<th>' . __('Status', 'social-review-slider') . '</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        
        foreach (array_slice(array_reverse($log), 0, 10) as $entry) {
            echo '<tr>';
            echo '<td>' . esc_html($entry['date']) . '</td>';
            echo '<td>' . esc_html(ucfirst($entry['platform'])) . '</td>';
            echo '<td>' . esc_html($entry['count']) . '</td>';
            echo '<td>' . ($entry['success'] ? '<span style="color: green;">✓ Success</span>' : '<span style="color: red;">✗ Failed</span>') . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
    
    /**
     * Import reviews from Google using AI Scraper Service
     */
    public function import_google_reviews() {
        $service_url = get_option('srs_scraper_service_url', 'http://localhost:3000');
        $api_key = get_option('srs_scraper_api_key');
        $place_url = get_option('srs_google_place_url');
        
        if (empty($api_key) || empty($place_url)) {
            return array('success' => false, 'message' => 'Missing scraper service credentials or Google URL');
        }
        
        $limit = intval(get_option('srs_import_limit', 50));
        
        $response = wp_remote_post($service_url . '/api/scrape/google', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-API-Key' => $api_key,
            ),
            'body' => json_encode(array(
                'placeUrl' => $place_url,
                'maxReviews' => $limit,
            )),
            'timeout' => 60,
        ));
        
        if (is_wp_error($response)) {
            $this->log_import('google', 0, false);
            return array('success' => false, 'message' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($body['success']) || empty($body['reviews'])) {
            $error_msg = isset($body['error']) ? $body['error'] : 'No reviews found';
            $this->log_import('google', 0, false);
            return array('success' => false, 'message' => $error_msg);
        }
        
        $imported = 0;
        
        foreach ($body['reviews'] as $review) {
            // Check if review already exists
            $existing = $this->review_exists($review['author_name'], $review['text'], 'google');
            
            if (!$existing) {
                $this->create_review_post($review, 'google');
                $imported++;
            }
        }
        
        $this->log_import('google', $imported, true);
        
        return array('success' => true, 'count' => $imported);
    }
    
    /**
     * Import reviews from TripAdvisor using AI Scraper Service
     */
    public function import_tripadvisor_reviews() {
        $service_url = get_option('srs_scraper_service_url', 'http://localhost:3000');
        $api_key = get_option('srs_scraper_api_key');
        $hotel_url = get_option('srs_tripadvisor_hotel_url');
        
        if (empty($api_key) || empty($hotel_url)) {
            return array('success' => false, 'message' => 'Missing scraper service credentials or TripAdvisor URL');
        }
        
        $limit = intval(get_option('srs_import_limit', 50));
        
        $response = wp_remote_post($service_url . '/api/scrape/tripadvisor', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-API-Key' => $api_key,
            ),
            'body' => json_encode(array(
                'hotelUrl' => $hotel_url,
                'maxReviews' => $limit,
            )),
            'timeout' => 60,
        ));
        
        if (is_wp_error($response)) {
            $this->log_import('tripadvisor', 0, false);
            return array('success' => false, 'message' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($body['success']) || empty($body['reviews'])) {
            $error_msg = isset($body['error']) ? $body['error'] : 'No reviews found';
            $this->log_import('tripadvisor', 0, false);
            return array('success' => false, 'message' => $error_msg);
        }
        
        $imported = 0;
        
        foreach ($body['reviews'] as $review) {
            $existing = $this->review_exists($review['author_name'], $review['text'], 'tripadvisor');
            
            if (!$existing) {
                $this->create_review_post($review, 'tripadvisor');
                $imported++;
            }
        }
        
        $this->log_import('tripadvisor', $imported, true);
        
        return array('success' => true, 'count' => $imported);
    }
    
    /**
     * Import reviews from Airbnb using AI Scraper Service
     */
    public function import_airbnb_reviews() {
        $service_url = get_option('srs_scraper_service_url', 'http://localhost:3000');
        $api_key = get_option('srs_scraper_api_key');
        $listing_url = get_option('srs_airbnb_listing_url');
        
        if (empty($api_key) || empty($listing_url)) {
            return array('success' => false, 'message' => 'Missing scraper service credentials or Airbnb URL');
        }
        
        $limit = intval(get_option('srs_import_limit', 50));
        
        $response = wp_remote_post($service_url . '/api/scrape/airbnb', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-API-Key' => $api_key,
            ),
            'body' => json_encode(array(
                'listingUrl' => $listing_url,
                'maxReviews' => $limit,
            )),
            'timeout' => 60,
        ));
        
        if (is_wp_error($response)) {
            $this->log_import('airbnb', 0, false);
            return array('success' => false, 'message' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($body['success']) || empty($body['reviews'])) {
            $error_msg = isset($body['error']) ? $body['error'] : 'No reviews found';
            $this->log_import('airbnb', 0, false);
            return array('success' => false, 'message' => $error_msg);
        }
        
        $imported = 0;
        
        foreach ($body['reviews'] as $review) {
            $existing = $this->review_exists($review['author_name'], $review['text'], 'airbnb');
            
            if (!$existing) {
                $this->create_review_post($review, 'airbnb');
                $imported++;
            }
        }
        
        $this->log_import('airbnb', $imported, true);
        
        return array('success' => true, 'count' => $imported);
    }
    
    /**
     * Check if review already exists
     */
    private function review_exists($author, $text, $platform) {
        $args = array(
            'post_type' => 'srs_review',
            'meta_query' => array(
                array(
                    'key' => '_srs_reviewer_name',
                    'value' => $author,
                    'compare' => '=',
                ),
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => 'srs_platform',
                    'field' => 'slug',
                    'terms' => $platform,
                ),
            ),
        );
        
        $query = new WP_Query($args);
        return $query->have_posts();
    }
    
    /**
     * Create review post from AI Scraper data
     */
    private function create_review_post($review_data, $platform) {
        // AI scraper returns unified format
        $author = isset($review_data['author_name']) ? $review_data['author_name'] : 'Anonymous';
        $text = isset($review_data['text']) ? $review_data['text'] : '';
        $rating = isset($review_data['rating']) ? floatval($review_data['rating']) : 5;
        $date = isset($review_data['date']) ? $review_data['date'] : date('Y-m-d');
        
        if (empty($text)) {
            return false;
        }
        
        // Create post
        $post_id = wp_insert_post(array(
            'post_type' => 'srs_review',
            'post_title' => wp_trim_words($text, 10),
            'post_content' => $text,
            'post_status' => 'publish',
        ));
        
        if ($post_id) {
            // Add meta data
            update_post_meta($post_id, '_srs_reviewer_name', sanitize_text_field($author));
            update_post_meta($post_id, '_srs_rating', $rating);
            update_post_meta($post_id, '_srs_review_date', sanitize_text_field($date));
            
            // Set platform taxonomy
            wp_set_object_terms($post_id, $platform, 'srs_platform');
            
            return $post_id;
        }
        
        return false;
    }
    
    /**
     * Log import activity
     */
    private function log_import($platform, $count, $success) {
        $log = get_option('srs_import_log', array());
        
        $log[] = array(
            'date' => current_time('mysql'),
            'platform' => $platform,
            'count' => $count,
            'success' => $success,
        );
        
        // Keep only last 50 entries
        $log = array_slice($log, -50);
        
        update_option('srs_import_log', $log);
    }
    
    /**
     * Import all reviews (cron job)
     */
    public function import_all_reviews() {
        if (!get_option('srs_auto_import_enabled')) {
            return;
        }
        
        $this->import_google_reviews();
        $this->import_tripadvisor_reviews();
        $this->import_airbnb_reviews();
    }
    
    /**
     * Manual import via AJAX
     */
    public function manual_import() {
        check_ajax_referer('srs_manual_import', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $results = array();
        
        // Import from Google
        $google_result = $this->import_google_reviews();
        $results['google'] = $google_result;
        
        // Import from TripAdvisor
        $tripadvisor_result = $this->import_tripadvisor_reviews();
        $results['tripadvisor'] = $tripadvisor_result;
        
        // Import from Airbnb
        $airbnb_result = $this->import_airbnb_reviews();
        $results['airbnb'] = $airbnb_result;
        
        wp_send_json_success($results);
    }
    
    /**
     * Test API connection
     */
    public function test_api_connection() {
        check_ajax_referer('srs_test_connection', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $platform = sanitize_text_field($_POST['platform']);
        
        switch ($platform) {
            case 'google':
                $result = $this->test_google_connection();
                break;
            case 'tripadvisor':
                $result = $this->test_tripadvisor_connection();
                break;
            default:
                $result = array('success' => false, 'message' => 'Invalid platform');
        }
        
        if ($result['success']) {
            wp_send_json_success($result['message']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    private function test_google_connection() {
        $api_key = get_option('srs_google_api_key');
        $place_id = get_option('srs_google_place_id');
        
        if (empty($api_key) || empty($place_id)) {
            return array('success' => false, 'message' => 'Please enter both API Key and Place ID');
        }
        
        $url = add_query_arg(array(
            'place_id' => $place_id,
            'fields' => 'name,rating',
            'key' => $api_key,
        ), 'https://maps.googleapis.com/maps/api/place/details/json');
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return array('success' => false, 'message' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($body['status'] === 'OK') {
            return array('success' => true, 'message' => 'Connection successful! Found: ' . $body['result']['name']);
        } else {
            return array('success' => false, 'message' => 'Error: ' . $body['status']);
        }
    }
    
    private function test_tripadvisor_connection() {
        $api_key = get_option('srs_tripadvisor_api_key');
        $location_id = get_option('srs_tripadvisor_location_id');
        
        if (empty($api_key) || empty($location_id)) {
            return array('success' => false, 'message' => 'Please enter both API Key and Location ID');
        }
        
        $url = "https://api.tripadvisor.com/api/partner/2.0/location/{$location_id}";
        
        $response = wp_remote_get($url, array(
            'headers' => array(
                'X-TripAdvisor-API-Key' => $api_key,
            ),
        ));
        
        if (is_wp_error($response)) {
            return array('success' => false, 'message' => $response->get_error_message());
        }
        
        $code = wp_remote_retrieve_response_code($response);
        
        if ($code === 200) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            return array('success' => true, 'message' => 'Connection successful! Found: ' . $body['name']);
        } else {
            return array('success' => false, 'message' => 'Error: HTTP ' . $code);
        }
    }
}
