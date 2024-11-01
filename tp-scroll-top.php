<?php
/*
Plugin Name: Tp Scroll Top
Plugin URI: https://www.themepoints.com
Description: Tp Scroll To Top is a fully responsive plugin for WordPress.
Version: 1.4
Author: Themepoints
Author URI: https://www.themepoints.com
License: GPLv2
Text Domain: scrolltop
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'TP_SCROLL_TOP_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );

// Enqueue scripts and styles
function tp_scroll_top_enqueue_scripts() {
    wp_enqueue_style( 'scroll-top-css', TP_SCROLL_TOP_PLUGIN_PATH . 'css/tp-scroll-top.css' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'scroll-top-js', TP_SCROLL_TOP_PLUGIN_PATH . 'js/ap-scroll-top.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'scrolltop-wp-color-picker', TP_SCROLL_TOP_PLUGIN_PATH . 'js/color-picker.js', array( 'wp-color-picker' ), false, true );
}
add_action( 'init', 'tp_scroll_top_enqueue_scripts' );

// Load plugin textdomain for translations
function tp_scroll_top_load_textdomain() {
    load_plugin_textdomain( 'scrolltop', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'tp_scroll_top_load_textdomain' );

// Register plugin settings
function tp_scroll_top_register_settings() {
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_option_enable' );
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scroll_fade_speed' );
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_visibility_fade_speed' );
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_visibility_trigger' );
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scroll_position' );
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scrollbg' );
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scrollbg_hover' );
    register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scrollradious' );
}
add_action( 'admin_init', 'tp_scroll_top_register_settings' );

// Output custom CSS for the scroll button
function tp_scroll_top_custom_css() {
	$scrollbg       = get_option( 'tp_scroll_top_scrollbg', '#ffc107' );
	$scrollbg_hover = get_option( 'tp_scroll_top_scrollbg_hover', '#212121' );
	$scroll_radius  = get_option( 'tp_scroll_top_scrollradious', '50' );
    ?>
    <style type="text/css">
        .apst-button {
            background-color: <?php echo esc_attr( $scrollbg ); ?>;
            border-radius: <?php echo esc_attr( $scroll_radius ); ?>%;
            height: 60px;
            width: 60px;
            transition: all 0.2s ease;
        }
        .apst-button:hover {
            background-color: <?php echo esc_attr( $scrollbg_hover ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'tp_scroll_top_custom_css' );

// Enqueue the scroll top script with settings
function tp_scroll_top_script() {
	$option_enable         = get_option( 'tp_scroll_top_option_enable', 'true' );
	$scroll_fade_speed     = get_option( 'tp_scroll_top_scroll_fade_speed', '100' );
	$visibility_fade_speed = get_option( 'tp_scroll_top_visibility_fade_speed', '100' );
	$scroll_position       = get_option( 'tp_scroll_top_scroll_position', 'bottom right' );
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery.apScrollTop({
                enabled: <?php echo esc_js( $option_enable ); ?>,
                visibilityTrigger: 100,
                visibilityFadeSpeed: <?php echo esc_js( $visibility_fade_speed ); ?>,
                scrollSpeed: <?php echo esc_js( $scroll_fade_speed ); ?>,
                position: '<?php echo esc_js( $scroll_position ); ?>',
            });
        });
    </script>
    <?php
}
add_action( 'wp_head', 'tp_scroll_top_script' );

// Include the admin settings page
function tp_scroll_top_admin_page() {
    include 'admin/tp-scroll-top-admin.php';
}

// Add the plugin settings menu
function tp_scroll_top_add_admin_menu() {
    add_menu_page(
        __( 'Tp Scroll Top', 'scrolltop' ),
        __( 'Tp Scroll Top', 'scrolltop' ),
        'manage_options',
        'tp_scroll_top_option_settings',
        'tp_scroll_top_admin_page'
    );
}
add_action( 'admin_menu', 'tp_scroll_top_add_admin_menu' );
