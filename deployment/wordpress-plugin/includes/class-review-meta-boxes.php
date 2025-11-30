<?php
/**
 * Review Meta Boxes Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SRS_Review_Meta_Boxes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'srs_review_details',
            __('Review Details', 'social-review-slider'),
            array($this, 'render_review_details_meta_box'),
            'srs_review',
            'normal',
            'high'
        );
    }
    
    public function render_review_details_meta_box($post) {
        wp_nonce_field('srs_review_details_nonce', 'srs_review_details_nonce_field');
        
        $reviewer_name = get_post_meta($post->ID, '_srs_reviewer_name', true);
        $rating = get_post_meta($post->ID, '_srs_rating', true);
        $review_date = get_post_meta($post->ID, '_srs_review_date', true);
        $review_url = get_post_meta($post->ID, '_srs_review_url', true);
        $reviewer_avatar = get_post_meta($post->ID, '_srs_reviewer_avatar', true);
        $reviewer_location = get_post_meta($post->ID, '_srs_reviewer_location', true);
        
        ?>
        <div class="srs-meta-box">
            <table class="form-table">
                <tr>
                    <th><label for="srs_reviewer_name"><?php _e('Reviewer Name', 'social-review-slider'); ?></label></th>
                    <td>
                        <input type="text" id="srs_reviewer_name" name="srs_reviewer_name" value="<?php echo esc_attr($reviewer_name); ?>" class="regular-text" required>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="srs_rating"><?php _e('Rating (1-5)', 'social-review-slider'); ?></label></th>
                    <td>
                        <select id="srs_rating" name="srs_rating" required>
                            <option value=""><?php _e('Select Rating', 'social-review-slider'); ?></option>
                            <?php for ($i = 1; $i <= 5; $i += 0.5): ?>
                                <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>><?php echo $i; ?> <?php _e('Stars', 'social-review-slider'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="srs_review_date"><?php _e('Review Date', 'social-review-slider'); ?></label></th>
                    <td>
                        <input type="date" id="srs_review_date" name="srs_review_date" value="<?php echo esc_attr($review_date); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="srs_review_url"><?php _e('Review URL', 'social-review-slider'); ?></label></th>
                    <td>
                        <input type="url" id="srs_review_url" name="srs_review_url" value="<?php echo esc_url($review_url); ?>" class="regular-text" placeholder="https://">
                        <p class="description"><?php _e('Link to the original review on the platform', 'social-review-slider'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="srs_reviewer_location"><?php _e('Reviewer Location', 'social-review-slider'); ?></label></th>
                    <td>
                        <input type="text" id="srs_reviewer_location" name="srs_reviewer_location" value="<?php echo esc_attr($reviewer_location); ?>" class="regular-text" placeholder="e.g., New York, USA">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="srs_reviewer_avatar"><?php _e('Reviewer Avatar', 'social-review-slider'); ?></label></th>
                    <td>
                        <div class="srs-avatar-upload">
                            <input type="hidden" id="srs_reviewer_avatar" name="srs_reviewer_avatar" value="<?php echo esc_attr($reviewer_avatar); ?>">
                            <button type="button" class="button srs-upload-avatar-btn"><?php _e('Upload Avatar', 'social-review-slider'); ?></button>
                            <button type="button" class="button srs-remove-avatar-btn" style="<?php echo empty($reviewer_avatar) ? 'display:none;' : ''; ?>"><?php _e('Remove', 'social-review-slider'); ?></button>
                            <div class="srs-avatar-preview" style="margin-top: 10px;">
                                <?php if ($reviewer_avatar): ?>
                                    <img src="<?php echo esc_url($reviewer_avatar); ?>" style="max-width: 100px; height: auto; border-radius: 50%;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    public function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['srs_review_details_nonce_field']) || 
            !wp_verify_nonce($_POST['srs_review_details_nonce_field'], 'srs_review_details_nonce')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save fields
        $fields = array(
            'srs_reviewer_name',
            'srs_rating',
            'srs_review_date',
            'srs_review_url',
            'srs_reviewer_avatar',
            'srs_reviewer_location',
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
