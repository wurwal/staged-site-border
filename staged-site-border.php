<?php
/*
Plugin Name: Staged Site Border
Description: Adds a customizable solid color border around the browser viewport and allows inserting an editable message. This helps users differentiate your staged site with the live one.
Version: 1.1
Author: Thomas Wurwal
*/

// Add a menu item in the admin panel
function staged_site_border_menu()
{
    add_menu_page(
        'Staged Site Border Settings',
        'Staged Site Border',
        'manage_options',
        'staged_site_border_settings',
        'staged_site_border_settings_page'
    );
}
add_action('admin_menu', 'staged_site_border_menu');

// Register plugin settings
function staged_site_border_register_settings()
{
    register_setting('staged_site_border_settings_group', 'border_color');
    register_setting('staged_site_border_settings_group', 'border_enabled');
    register_setting('staged_site_border_settings_group', 'border_message');
    register_setting('staged_site_border_settings_group', 'border_thickness');
}
add_action('admin_init', 'staged_site_border_register_settings');

// Create the settings page
function staged_site_border_settings_page()
{
    ?>
    <div class="wrap">
        <h2>Staged Site Border Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('staged_site_border_settings_group'); ?>
            <?php do_settings_sections('staged_site_border_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Border Color</th>
                    <td>
                        <input type="text" name="border_color"
                            value="<?php echo esc_attr(get_option('border_color', '#00ff00')); ?>" class="color-picker" />
                        <p class="description">Choose the color for the site border.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Border Thickness</th>
                    <td>
                        <input type="number" name="border_thickness"
                            value="<?php echo esc_attr(get_option('border_thickness', '5')); ?>" min="1" />
                        <p class="description">Specify the thickness of the site border in pixels.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable Border</th>
                    <td>
                        <label>
                            <input type="checkbox" name="border_enabled" value="1" <?php checked(get_option('border_enabled', 1)); ?> />
                            Enable Staged Site Border
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Border Message</th>
                    <td>
                        <textarea name="border_message" rows="3"
                            cols="50"><?php echo esc_textarea(get_option('border_message', '')); ?></textarea>
                        <p class="description">Enter the message to display alongside the border. HTML is allowed.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $('.color-picker').wpColorPicker();
        });
    </script>
    <?php
}

// Add the staged site border based on the selected color, toggle setting, message, and thickness
function add_staged_site_border()
{
    $border_enabled = get_option('border_enabled', 1);
    if ($border_enabled) {
        $border_color = get_option('border_color', '#00ff00');
        $border_thickness = get_option('border_thickness', '5');
        $border_message = get_option('border_message', '');
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('body').prepend('<div id="staged-site-border"></div>');
                $('#staged-site-border').css({
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%',
                    'border': 'solid <?php echo esc_js($border_thickness); ?>px <?php echo esc_js($border_color); ?>',
                    'pointer-events': 'none',
                    'z-index': '999999'
                });
                if ('<?php echo esc_js($border_message); ?>' !== '') {
                    $('body').prepend('<div id="border-message"><?php echo esc_js($border_message); ?></div>');
                    $('#border-message').css({
                        'position': 'fixed',
                        'bottom': '0',
                        'left': '0',
                        'width': '100%',
                        'background': '<?php echo esc_js($border_color); ?>',
                        'color': '#ffffff',
                        'font-size': '16px',
                        'padding': '5px',
                        'box-sizing': 'border-box',
                        'z-index': '1000000',
                        'text-align' : 'center'
                    });
                }
            });
        </script>
        <?php
    }
}

add_action('wp_footer', 'add_staged_site_border');
?>
