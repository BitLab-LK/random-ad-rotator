<?php
// Add a root-level menu item for the plugin settings page
function random_ad_rotator_menu() {
    add_menu_page(
        'Random Ad Rotator Settings',   // Page title
        'Random Ad Rotator',            // Menu title
        'manage_options',               // Capability
        'random-ad-rotator',            // Menu slug
        'random_ad_rotator_settings_page',  // Callback function
        'dashicons-embed-photo',        // Icon (dashicons-embed-photo)
        20                              // Position in the menu (lower numbers for higher positions)
    );
}
add_action('admin_menu', 'random_ad_rotator_menu');

// Display the settings page
function random_ad_rotator_settings_page() {
    ?>
    <div class="wrap">
        <h1>Random Ad Rotator Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('random_ad_rotator_options');
            do_settings_sections('random_ad_rotator');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Initialize plugin settings
function random_ad_rotator_settings_init() {
    register_setting('random_ad_rotator_options', 'random_ad_images');

    add_settings_section(
        'random_ad_rotator_section',
        'Ad Images for Different Sizes',
        null,
        'random_ad_rotator'
    );

    // 345x345 ad field
    add_settings_field(
        'random_ad_345x345',
        '345x345 Ad Images',
        'random_ad_image_field',
        'random_ad_rotator',
        'random_ad_rotator_section',
        array('size' => '345x345')
    );

    // 680x180 ad field
    add_settings_field(
        'random_ad_680x180',
        '680x180 Ad Images',
        'random_ad_image_field',
        'random_ad_rotator',
        'random_ad_rotator_section',
        array('size' => '680x180')
    );

    // 970x180 ad field
    add_settings_field(
        'random_ad_970x180',
        '970x180 Ad Images',
        'random_ad_image_field',
        'random_ad_rotator',
        'random_ad_rotator_section',
        array('size' => '970x180')
    );

    // 400x90 ad field
    add_settings_field(
        'random_ad_400x90',
        '400x90 Ad Images',
        'random_ad_image_field',
        'random_ad_rotator',
        'random_ad_rotator_section',
        array('size' => '400x90')
    );
}
add_action('admin_init', 'random_ad_rotator_settings_init');

// Callback function to render the image field for different ad sizes
function random_ad_image_field($args) {
    $size = $args['size'];
    $ad_images = get_option('random_ad_images');
    $images = isset($ad_images[$size]) ? $ad_images[$size] : array();

    echo '<div id="ad-images-' . esc_attr($size) . '">';
    if (!empty($images)) {
        echo '<table><tr>';
        foreach ($images as $image) {
            echo '<td>';
            echo '<img src="' . esc_url($image) . '" alt="Ad" style="width: 100px; height: auto;" /><br>';
            echo '<input type="text" name="random_ad_images[' . esc_attr($size) . '][]" value="' . esc_url($image) . '" size="50" />';
            echo '<button class="button remove-ad-image">Remove</button>';
            echo '</td>';
        }
        echo '</tr></table>';
    }
    echo '</div>';
    echo '<button class="button add-ad-image" data-size="' . esc_attr($size) . '">Add Image</button>';
}

// Enqueue media uploader script and custom JS for the settings page
function random_ad_rotator_admin_scripts($hook) {
    if ($hook != 'settings_page_random-ad-rotator') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('random-ad-rotator-admin-js', plugin_dir_url(__FILE__) . 'random-ad-rotator-admin.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'random_ad_rotator_admin_scripts');
