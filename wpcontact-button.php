<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://keremcan.com.tr
 * @since             1.0.0
 * @package           Wpcontact_Button
 *
 * @wordpress-plugin
 * Plugin Name:       WP Contact Button
 * Plugin URI:        https://keremcan.com.tr/wp-contact
 * Description:       This plugin seamlessly integrates a button into your WordPress site, allowing visitors to contact you directly via WhatsApp. It's fast, user-friendly, and fully customizable.


 * Version:           1.0.0
 * Author:            Kerem Can
 * Author URI:        https://keremcan.com.tr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpcontact-button
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPCONTACT_BUTTON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpcontact-button-activator.php
 */
function activate_wpcontact_button() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcontact-button-activator.php';
	Wpcontact_Button_Activator::activate();
}

function wpcontact_enqueue_admin_styles() {
    // Determine the path to the CSS file
    $admin_css_path = plugins_url('admin/css/wpcontact-button-admin.css', __FILE__);
    
    // Enqueue the CSS file to the admin panel
    wp_enqueue_style('wpcontact-button-admin-style', $admin_css_path);
}

// Hook the function to 'admin_enqueue_scripts'
add_action('admin_enqueue_scripts', 'wpcontact_enqueue_admin_styles');


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpcontact-button-deactivator.php
 */
function deactivate_wpcontact_button() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcontact-button-deactivator.php';
	Wpcontact_Button_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpcontact_button' );
register_deactivation_hook( __FILE__, 'deactivate_wpcontact_button' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpcontact-button.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpcontact_button() {

	$plugin = new Wpcontact_Button();
	$plugin->run();

}
run_wpcontact_button();

// Admin menu function
function wpcontact_button_admin_menu() {
    add_menu_page(
        'WP Contact Button', // Page title
        'WP Contact Button', // Menu title
        'manage_options', // Capability
        'wpcontact-button', // Menu slug
        'wpcontact_button_admin_page', // Function to display the content
        'dashicons-format-chat', // Icon URL (using a Dashicon for now)
        80 // Position
    );
}

function wpcontact_button_admin_page() {
    // Check if form is submitted and save the options
    if (isset($_POST['wpcontact_button_save'])) {
        update_option('wpcontact_whatsapp_number', sanitize_text_field($_POST['wpcontact_whatsapp_number']));
        update_option('wpcontact_whatsapp_message', sanitize_textarea_field($_POST['wpcontact_whatsapp_message']));
        echo '<div class="updated"><p>Ayarlar kaydedildi.</p></div>';
    }

    $whatsapp_number = get_option('wpcontact_whatsapp_number', '');
    $whatsapp_message = get_option('wpcontact_whatsapp_message', '');

    echo '<h1 class="wpcontact-admin-title">WP İletişim Butonu Ayarları</h1>';
echo '<form class="wpcontact-admin-form" method="POST">';
echo '<label class="wpcontact-admin-label" for="wpcontact_whatsapp_number">WhatsApp Numaranız:</label>';
echo '<input class="wpcontact-admin-input" type="text" name="wpcontact_whatsapp_number" value="' . esc_attr($whatsapp_number) . '">';
echo '<br>';
echo '<label class="wpcontact-admin-label" for="wpcontact_whatsapp_message">WhatsApp Mesajınız:</label>';
echo '<textarea class="wpcontact-admin-textarea" name="wpcontact_whatsapp_message" rows="4" cols="50">' . esc_textarea($whatsapp_message) . '</textarea>';
echo '<br>';
echo '<input class="wpcontact-admin-submit" type="submit" name="wpcontact_button_save" value="Kaydet">';
echo '</form>';

}

add_action('admin_menu', 'wpcontact_button_admin_menu');


function wpcontact_button_frontend() {
    $whatsapp_number = get_option('wpcontact_whatsapp_number', '');
    $whatsapp_message = get_option('wpcontact_whatsapp_message', '');

    if (!$whatsapp_number) return;

    // Eklentinin dosya yolu
    $plugin_url = plugin_dir_url(__FILE__);

    echo '<a href="https://wa.me/' . esc_attr($whatsapp_number) . '?text=' . urlencode($whatsapp_message) . '" target="_blank" style="position:fixed; bottom:10px; right:10px; z-index:9999;">';
echo '<button class="button2">WhatsApp<svg viewBox="0 0 48 48" y="0px" x="0px" xmlns="http://www.w3.org/2000/svg">
<path d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z" fill="#fff"></path><path d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z" fill="#fff"></path><path d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z" fill="#cfd8dc"></path><path d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z" fill="#40c351"></path><path clip-rule="evenodd" d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z" fill-rule="evenodd" fill="#fff"></path>
</svg></button>'; 
    echo '</a>';
}

add_action('wp_footer', 'wpcontact_button_frontend');



