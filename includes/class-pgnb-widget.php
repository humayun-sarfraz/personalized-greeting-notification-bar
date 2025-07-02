<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class PGNB_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'pgnb_widget',
            __( 'Greeting & Notification Bar', 'pgnb' ),
            [ 'description' => __( 'Displays the personalized greeting and notifications.', 'pgnb' ) ]
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        echo do_shortcode( '[pgnb_bar]' );
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        echo '<p>' . esc_html__( 'No settings available for this widget.', 'pgnb' ) . '</p>';
    }
}
