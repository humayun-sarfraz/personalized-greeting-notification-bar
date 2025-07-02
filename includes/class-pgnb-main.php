<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class PGNB_Main {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_footer', [ $this, 'render_bar' ], 10 );
        add_shortcode( 'pgnb_bar', [ $this, 'render_bar_shortcode' ] );
        add_action( 'widgets_init', [ $this, 'register_widget' ] );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'pgnb-style', PGNB_URL . 'assets/css/pgnb-style.css', [], PGNB_VERSION );
        wp_enqueue_script( 'pgnb-script', PGNB_URL . 'assets/js/pgnb-script.js', [ 'jquery' ], PGNB_VERSION, true );
        wp_localize_script( 'pgnb-script', 'pgnb_data', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'pgnb_dismiss_nonce' ),
        ] );
        // Inline custom CSS from settings
        $css = $this->get_inline_css();
        if ( $css ) {
            wp_add_inline_style('pgnb-style', $css );
        }
    }

    public function render_bar() {
        if ( get_option( 'pgnb_enabled', '1' ) !== '1' ) return;
        if ( isset( $_COOKIE['pgnb_dismissed'] ) && $_COOKIE['pgnb_dismissed'] === '1' ) return;
        echo $this->get_bar_html();
    }

    public function render_bar_shortcode() {
        return $this->get_bar_html();
    }

    private function get_bar_html() {
        $greeting     = $this->get_greeting();
        $user         = wp_get_current_user();
        $name         = $user->exists() ? esc_html( $user->display_name ) : esc_html( get_option( 'pgnb_guest_label', __( 'Guest', 'pgnb' ) ) );
        $notification = $this->get_notification();

        ob_start(); ?>
        <div id="pgnb-bar" class="pgnb-bar <?php echo esc_attr( get_option( 'pgnb_bar_position', 'top' ) ); ?>">
            <div class="pgnb-content">
                <span class="pgnb-greeting"><?php echo sprintf( esc_html( get_option( 'pgnb_greeting_format', '%1$s, %2$s!' ) ), esc_html( $greeting ), esc_html( $name ) ); ?></span>
                <?php if ( $notification ) : ?>
                    <a href="<?php echo esc_url( $notification['url'] ); ?>" class="pgnb-notice"><?php echo esc_html( $notification['text'] ); ?></a>
                <?php endif; ?>
            </div>
            <button type="button" class="pgnb-dismiss" aria-label="<?php esc_attr_e( 'Dismiss', 'pgnb' ); ?>">&times;</button>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_greeting() {
        $hour = (int) current_time( 'H' );
        $msg_morning   = get_option( 'pgnb_morning_msg', __( 'Good morning', 'pgnb' ) );
        $msg_afternoon = get_option( 'pgnb_afternoon_msg', __( 'Good afternoon', 'pgnb' ) );
        $msg_evening   = get_option( 'pgnb_evening_msg', __( 'Good evening', 'pgnb' ) );

        if ( $hour < 12 ) return $msg_morning;
        elseif ( $hour < 18 ) return $msg_afternoon;
        else return $msg_evening;
    }

    private function get_notification() {
        $type = get_option( 'pgnb_notification_type', 'cart' );
        if ( $type === 'cart' && class_exists( 'WooCommerce' ) && is_user_logged_in() ) {
            $count = WC()->cart->get_cart_contents_count();
            if ( $count > 0 ) {
                return [
                    'text' => sprintf( _n( 'You have %d item in your cart', 'You have %d items in your cart', $count, 'pgnb' ), $count ),
                    'url'  => wc_get_cart_url(),
                ];
            }
        } elseif ( $type === 'latest_post' ) {
            $post = get_posts( [ 'numberposts' => 1 ] );
            if ( $post ) {
                return [
                    'text' => sprintf( __( 'Latest post: %s', 'pgnb' ), $post[0]->post_title ),
                    'url'  => get_permalink( $post[0]->ID ),
                ];
            }
        } elseif ( $type === 'custom' ) {
            $custom = get_option( 'pgnb_custom_notification', '' );
            if ( $custom ) {
                return [
                    'text' => $custom,
                    'url'  => home_url(),
                ];
            }
        }
        return false;
    }

    public function register_widget() {
        register_widget( 'PGNB_Widget' );
    }

    private function get_inline_css() {
        $bg = sanitize_hex_color( get_option( 'pgnb_bg_color', '#333' ) );
        $text = sanitize_hex_color( get_option( 'pgnb_text_color', '#fff' ) );
        $btn = sanitize_hex_color( get_option( 'pgnb_btn_color', '#ff5722' ) );
        $pos = get_option( 'pgnb_bar_position', 'top' );
        $extra = '';
        if ( $pos === 'bottom' ) $extra = '.pgnb-bar { top: auto !important; bottom: 0 !important; }';
        return ".pgnb-bar{background:{$bg};color:{$text}}.pgnb-notice{background:{$btn}}{$extra}";
    }
}
new PGNB_Main();
