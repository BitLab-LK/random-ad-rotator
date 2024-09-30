<?php
/*
Plugin Name: Random Ad Rotator | BitLab
Plugin URI: https://github.com/BitLab-LK/random-ad-rotator
Description: A plugin to rotate random ad images for multiple ad sizes.
Version: 1.0.0
Author: BitLab (Pvt) Ltd
Author URI: https://bitlab.lk/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: random-ad-rotator
*/

// Include the admin file
require_once plugin_dir_path(__FILE__) . 'admin/random-ad-rotator-admin.php';

// Register the shortcode [random_ad size="345x345/680x180/970x180/400x90"]
function display_random_ad_image($atts) {
    $atts = shortcode_atts(
        array('size' => '345x345'), // Default size is '345x345'
        $atts,
        'random_ad'
    );

    // Output a div with a data attribute for the ad size
    return '<div class="random-ad-container" data-size="' . esc_attr($atts['size']) . '">Loading ad...</div>';
}
add_shortcode('random_ad', 'display_random_ad_image');

// Register the AJAX action for logged-in and non-logged-in users
add_action('wp_ajax_get_random_ad_image', 'get_random_ad_image');
add_action('wp_ajax_nopriv_get_random_ad_image', 'get_random_ad_image');

function get_random_ad_image() {
    // Get the ad size from the AJAX request
    $size = isset($_POST['size']) ? sanitize_text_field($_POST['size']) : '345x345';

    // Get the saved ad images from the settings
    $ad_images = get_option('random_ad_images');

    // Get the ad images for the specified size
    $ad_size = isset($ad_images[$size]) ? $ad_images[$size] : array();

    // If no images are available, return an empty response
    if (empty($ad_size)) {
        wp_send_json_error('No images available');
    }

    // Select a random ad image
    $random_image = $ad_size[array_rand($ad_size)];

    // Send the image URL back as a JSON response
    wp_send_json_success(esc_url($random_image));
}

// Enqueue the AJAX script
function random_ad_rotator_enqueue_scripts() {
    wp_enqueue_script('random-ad-rotator-ajax', plugin_dir_url(__FILE__) . 'assets/random-ad-rotator-ajax.js', array('jquery'), null, true);

    // Localize the script with the admin AJAX URL
    wp_localize_script('random-ad-rotator-ajax', 'randomAdRotator', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'random_ad_rotator_enqueue_scripts');
