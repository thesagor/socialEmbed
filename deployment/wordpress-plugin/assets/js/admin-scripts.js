/**
 * Admin Scripts for Social Review Slider
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Media uploader for reviewer avatar
        let mediaUploader;
        
        $('.srs-upload-avatar-btn').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $input = $('#srs_reviewer_avatar');
            const $preview = $('.srs-avatar-preview');
            const $removeBtn = $('.srs-remove-avatar-btn');
            
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Extend the wp.media object
            mediaUploader = wp.media({
                title: 'Choose Reviewer Avatar',
                button: {
                    text: 'Use this image'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            // When a file is selected, grab the URL and set it as the input value
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                $input.val(attachment.url);
                $preview.html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto; border-radius: 50%;">');
                $removeBtn.show();
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        // Remove avatar
        $('.srs-remove-avatar-btn').on('click', function(e) {
            e.preventDefault();
            
            $('#srs_reviewer_avatar').val('');
            $('.srs-avatar-preview').html('');
            $(this).hide();
        });
        
        // Rating validation
        $('#srs_rating').on('change', function() {
            const rating = parseFloat($(this).val());
            if (rating < 1 || rating > 5) {
                alert('Rating must be between 1 and 5');
                $(this).val('');
            }
        });
        
        // Add shortcode helper to the sidebar
        if ($('#srs_review_details').length) {
            const shortcodeHelper = `
                <div class="srs-shortcode-helper">
                    <h3>How to Use</h3>
                    <p>Use this shortcode to display reviews:</p>
                    <pre><code>[social_reviews]</code></pre>
                    
                    <h4>Shortcode Parameters:</h4>
                    <ul>
                        <li><code>platform</code> - Filter by platform (all, tripadvisor, google, airbnb)</li>
                        <li><code>count</code> - Number of reviews to show (-1 for all)</li>
                        <li><code>autoplay</code> - Enable autoplay (true/false)</li>
                        <li><code>autoplay_delay</code> - Autoplay delay in milliseconds</li>
                        <li><code>slides_per_view</code> - Number of slides visible at once</li>
                        <li><code>random</code> - Random order (true/false)</li>
                    </ul>
                    
                    <h4>Example:</h4>
                    <pre><code>[social_reviews platform="google" count="5" autoplay="true"]</code></pre>
                </div>
            `;
            
            $('#postbox-container-1 .inside').first().prepend(shortcodeHelper);
        }
        
        // Auto-save reminder
        let formChanged = false;
        
        $('#post input, #post textarea, #post select').on('change', function() {
            formChanged = true;
        });
        
        $(window).on('beforeunload', function() {
            if (formChanged) {
                return 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
        
        $('#publish, #save-post').on('click', function() {
            formChanged = false;
        });
        
        // ===== IMPORT SETTINGS PAGE =====
        
        // Manual Import
        $('.srs-manual-import').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $status = $('.srs-import-status');
            
            // Disable button and show loading
            $button.prop('disabled', true).addClass('loading');
            $button.find('.dashicons').addClass('dashicons-update-alt');
            
            $status.removeClass('success error').addClass('info').show();
            $status.html('<h4>Importing reviews...</h4><p>Please wait while we fetch reviews from all platforms.</p>');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'srs_manual_import',
                    nonce: srsAdmin.manualImportNonce
                },
                success: function(response) {
                    $button.prop('disabled', false).removeClass('loading');
                    
                    if (response.success) {
                        const data = response.data;
                        let html = '<h4>Import Complete!</h4><ul>';
                        
                        if (data.google) {
                            html += '<li><strong>Google:</strong> ' + (data.google.success ? data.google.count + ' reviews imported' : 'Failed - ' + data.google.message) + '</li>';
                        }
                        
                        if (data.tripadvisor) {
                            html += '<li><strong>TripAdvisor:</strong> ' + (data.tripadvisor.success ? data.tripadvisor.count + ' reviews imported' : 'Failed - ' + data.tripadvisor.message) + '</li>';
                        }
                        
                        if (data.airbnb) {
                            html += '<li><strong>Airbnb:</strong> ' + (data.airbnb.success ? data.airbnb.count + ' reviews imported' : 'Failed - ' + data.airbnb.message) + '</li>';
                        }
                        
                        html += '</ul>';
                        
                        $status.removeClass('info').addClass('success').html(html);
                        
                        // Reload page after 3 seconds to show new reviews
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        $status.removeClass('info').addClass('error');
                        $status.html('<h4>Import Failed</h4><p>' + response.data + '</p>');
                    }
                },
                error: function(xhr, status, error) {
                    $button.prop('disabled', false).removeClass('loading');
                    $status.removeClass('info').addClass('error');
                    $status.html('<h4>Import Failed</h4><p>An error occurred: ' + error + '</p>');
                }
            });
        });
        
        // Test Connection
        $('.srs-test-connection').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const platform = $button.data('platform');
            const originalText = $button.text();
            
            $button.prop('disabled', true).text('Testing...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'srs_test_connection',
                    platform: platform,
                    nonce: srsAdmin.testConnectionNonce
                },
                success: function(response) {
                    $button.prop('disabled', false).text(originalText);
                    
                    if (response.success) {
                        alert('✓ ' + response.data);
                    } else {
                        alert('✗ ' + response.data);
                    }
                },
                error: function() {
                    $button.prop('disabled', false).text(originalText);
                    alert('✗ Connection test failed. Please try again.');
                }
            });
        });
        
        // Auto-import toggle
        $('#srs_auto_import_enabled').on('change', function() {
            const $frequencyRow = $(this).closest('table').find('tr:has(#srs_import_frequency)');
            
            if ($(this).is(':checked')) {
                $frequencyRow.show();
            } else {
                $frequencyRow.hide();
            }
        }).trigger('change');
        
    });
    
})(jQuery);
