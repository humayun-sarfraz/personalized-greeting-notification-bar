<?php
/**
 * Plugin Name: Personalized Greeting & Notification Bar
 * Plugin URI:  https://github.com/humayun-sarfraz/personalized-greeting-notification-bar
 * Description: Customizable greeting bar with notifications (WooCommerce, posts, or custom message) and admin options.
 * Version:     2.0.0
 * Author:      Humayun Sarfraz
 * Author URI:  https://github.com/humayun-sarfraz
 * Text Domain: pgnb
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'PGNB_VERSION', '2.0.0' );
define( 'PGNB_DIR', plugin_dir_path( __FILE__ ) );
define( 'PGNB_URL', plugin_dir_url( __FILE__ ) );

require_once PGNB_DIR . 'includes/class-pgnb-main.php';
require_once PGNB_DIR . 'includes/class-pgnb-admin.php';
require_once PGNB_DIR . 'includes/class-pgnb-widget.php';

// AJAX for dismiss
add_action( 'wp_ajax_pgnb_dismiss', 'pgnb_handle_dismiss' );
add_action( 'wp_ajax_nopriv_pgnb_dismiss', 'pgnb_handle_dismiss' );
function pgnb_handle_dismiss() {
    check_ajax_referer( 'pgnb_dismiss_nonce', 'nonce' );
    $duration = (int) get_option('pgnb_cookie_duration', 1); // days
    setcookie( 'pgnb_dismissed', '1', time() + ( DAY_IN_SECONDS * $duration ), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
    wp_send_json_success();
}
