<?php
/*
Plugin Name: Staged Site Border
Description: Adds a customizable solid color border around the browser viewport.
Version: 1.2
Author: Thomas Wurwal
*/

// Add a menu item in the admin panel
function green_border_menu() {
    add_menu_page(
        'Green Border Plugin Settings',
        'Green Border Settings',
        'manage_options',
        'green_border_settings',
        'green_border_settings_page'
    );
}
add_action('admin_menu', 'green_border_menu');

// Register plugin settings
function green_border_register_settings() {
    register_setting('green_border_settings_group', 'green_border_color');
    register_setting('green_border_settings_group', 'green_border_enabled');
}
add_action('admin_init', 'green_border_register_settings');

// Create the settings page
function green_border_settings_page() {
    ?>
    <div class="wrap">
        <h2>Green Border Plugin Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('green_border_settings_group'); ?>
            <?php do_settings_sections('green_border_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Border Color</th>
                    <td>
                        <input type="text" name="green_border_color" value="<?php echo esc_attr(get_option('green_border_color', '#00ff00')); ?>" class="color-picker" />
                        <p class="description">Choose the color for the green border.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable Border</th>
                    <td>
                        <label>
                            <input type="checkbox" name="green_border_enabled" value="1" <?php checked(get_option('green_border_enabled', 1)); ?> />
                            Enable Green Border
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('.color-picker').wpColorPicker();
        });
    </script>
    <?php
}

// Add the green border based on the selected color and toggle setting
function add_green_border() {
    $border_enabled = get_option('green_border_enabled', 1);
    if ($border_enabled) {
        $border_color = get_option('green_border_color', '#00ff00');
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('body').prepend('<div id="green-border"></div>');
                $('#green-border').css({
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%',
                    'border': 'solid 5px <?php echo esc_js($border_color); ?>',
                    'pointer-events': 'none',
                    'z-index': '9999999999999999'
                });
            });
        </script>
        <?php
    }
}

add_action('wp_footer', 'add_green_border');
?>
