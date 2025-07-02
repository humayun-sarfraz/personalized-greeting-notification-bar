<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class PGNB_Admin {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }
    public function add_menu() {
        add_options_page(
            __( 'Greeting Bar Settings', 'pgnb' ),
            __( 'Greeting Bar', 'pgnb' ),
            'manage_options',
            'pgnb_settings',
            [ $this, 'settings_page' ]
        );
    }
    public function register_settings() {
        register_setting( 'pgnb_settings', 'pgnb_enabled', 'absint' );
        register_setting( 'pgnb_settings', 'pgnb_bg_color', 'sanitize_hex_color' );
        register_setting( 'pgnb_settings', 'pgnb_text_color', 'sanitize_hex_color' );
        register_setting( 'pgnb_settings', 'pgnb_btn_color', 'sanitize_hex_color' );
        register_setting( 'pgnb_settings', 'pgnb_bar_position', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_greeting_format', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_morning_msg', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_afternoon_msg', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_evening_msg', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_guest_label', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_notification_type', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_custom_notification', 'sanitize_text_field' );
        register_setting( 'pgnb_settings', 'pgnb_cookie_duration', 'absint' );
    }
    public function settings_page() { ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Greeting & Notification Bar Settings', 'pgnb' ); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'pgnb_settings' ); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Bar', 'pgnb' ); ?></th>
                        <td><input type="checkbox" name="pgnb_enabled" value="1" <?php checked( get_option('pgnb_enabled', 1), 1 ); ?>></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Bar Position', 'pgnb' ); ?></th>
                        <td>
                            <select name="pgnb_bar_position">
                                <option value="top" <?php selected( get_option('pgnb_bar_position', 'top'), 'top' ); ?>><?php esc_html_e( 'Top', 'pgnb' ); ?></option>
                                <option value="bottom" <?php selected( get_option('pgnb_bar_position'), 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'pgnb' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Bar Background Color', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_bg_color" value="<?php echo esc_attr( get_option('pgnb_bg_color', '#333') ); ?>" class="pgnb-color"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Text Color', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_text_color" value="<?php echo esc_attr( get_option('pgnb_text_color', '#fff') ); ?>" class="pgnb-color"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Button Color', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_btn_color" value="<?php echo esc_attr( get_option('pgnb_btn_color', '#ff5722') ); ?>" class="pgnb-color"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Dismiss Cookie Duration (days)', 'pgnb' ); ?></th>
                        <td><input type="number" min="1" name="pgnb_cookie_duration" value="<?php echo absint( get_option('pgnb_cookie_duration', 1) ); ?>"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Greeting Format', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_greeting_format" value="<?php echo esc_attr( get_option('pgnb_greeting_format', '%1$s, %2$s!') ); ?>"> <small><?php esc_html_e( 'Use %1$s for greeting, %2$s for user name.', 'pgnb' ); ?></small></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Greeting (Morning)', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_morning_msg" value="<?php echo esc_attr( get_option('pgnb_morning_msg', __('Good morning','pgnb')) ); ?>"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Greeting (Afternoon)', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_afternoon_msg" value="<?php echo esc_attr( get_option('pgnb_afternoon_msg', __('Good afternoon','pgnb')) ); ?>"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Greeting (Evening)', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_evening_msg" value="<?php echo esc_attr( get_option('pgnb_evening_msg', __('Good evening','pgnb')) ); ?>"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Guest Label', 'pgnb' ); ?></th>
                        <td><input type="text" name="pgnb_guest_label" value="<?php echo esc_attr( get_option('pgnb_guest_label', __('Guest','pgnb')) ); ?>"></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Notification Type', 'pgnb' ); ?></th>
                        <td>
                            <select name="pgnb_notification_type">
                                <option value="cart" <?php selected( get_option('pgnb_notification_type', 'cart'), 'cart' ); ?>><?php esc_html_e('WooCommerce Cart', 'pgnb'); ?></option>
                                <option value="latest_post" <?php selected( get_option('pgnb_notification_type'), 'latest_post' ); ?>><?php esc_html_e('Latest Post', 'pgnb'); ?></option>
                                <option value="custom" <?php selected( get_option('pgnb_notification_type'), 'custom' ); ?>><?php esc_html_e('Custom Message', 'pgnb'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Custom Notification Message', 'pgnb'); ?></th>
                        <td><input type="text" name="pgnb_custom_notification" value="<?php echo esc_attr( get_option('pgnb_custom_notification') ); ?>"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <script>
        jQuery(function($){
            if ($('.pgnb-color').wpColorPicker) {
                $('.pgnb-color').wpColorPicker();
            }
        });
        </script>
    <?php }
}
new PGNB_Admin();
