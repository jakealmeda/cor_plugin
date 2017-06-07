<?php
/**
 * Plugin Name: SPK - Master Plugin V3
 * Description: Combining all SmarterWebPackages plugins into one: Shortcoder, Quotes, Youtube Embed, Social Toolbar, Dynamic Transfers and Get Permalink
 * Version: 3.0
 * Author: Jake Almeda
 * Author URI: http://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* --------------------------------------------------------------------------------------------
 * | ENQUEUE SCRIPTS
 * ----------------------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'spk_masterplug_js_scripts' );
function spk_masterplug_js_scripts() {

    // enqueue needed native jQuery files
    if( !wp_script_is( 'jquery-ui-core', 'enqueued' ) ) {
        wp_enqueue_script('jquery-ui-core');
    }

    if( !wp_script_is( 'jquery-effects-core', 'enqueued' ) ) {
        wp_enqueue_script('jquery-effects-core');
    }

    if( !wp_script_is( 'jquery-effects-slide', 'enqueued' ) ) {
        wp_enqueue_script('jquery-effects-slide');
    }

    if( !wp_script_is( 'jquery-effects-fade', 'enqueued' ) ) {
        wp_enqueue_script('jquery-effects-fade');
    }

    if( !wp_script_is( 'jquery-ui-accordion', 'enqueued' ) ) {
        wp_enqueue_script( 'jquery-ui-accordion' );
    }

}

/* --------------------------------------------------------------------------------------------
 * | APPLY HACKS
 * ----------------------------------------------------------------------------------------- */
if ( !is_admin() ) {

	// remove enqueued scripts from the header
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	// then load in the footer
	add_action('wp_footer', 'wp_enqueue_scripts', 3);

	// Defer Javascripts
	add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
    function defer_parsing_of_js ( $url ) {
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( strpos( $url, 'jquery.js' ) ) return $url;
        // return "$url' defer ";
        return $url."' defer onload='";
    }

	// FORCE THE CRITICAL CSS TO LOAD INLINE (INSIDE <head></head> TAGS)
    add_action( 'wp_head', 'cor_critical_styling', 10 );
	function cor_critical_styling() {
		//$custom_css = file_get_contents( get_stylesheet_directory_uri() . '/style_critical_min.css' );
		$critical_style = file_get_contents( plugin_dir_url( __FILE__ ) . 'css/style_critical_min.css' );
		$soliloquy_css = file_get_contents( plugin_dir_url( __FILE__ ) . 'css/soliloquy_min.css' );

		echo '<style type="text/css">'.$critical_style.$soliloquy_css.'</style>';
	}

	// Check if current post has soliloquy shortcode
	//add_filter('script_loader_tag', 'add_async_attribute', 1, 2);
	function add_async_attribute( $tag, $handle ) {
	    if ( 'jquery-core' !== $handle )
	    	//echo $handle.'<br>';
	        return $tag;
	    return str_replace( ' src', ' defer src', $tag );
	}
		
	// ADD NON-CRITICAL STYLING TO THE FOOTER
	// NOTE: Enqueued scripts are executed at priority level 20
	add_action( 'wp_footer', 'spk_delay_styling_func', 2 );
	function spk_delay_styling_func() {
		echo "<style type='text/css'>".file_get_contents( plugin_dir_url( __FILE__ ) . 'css/style_non_critical_min.css' )."</style>";	
	}

	// DEREGISTER SCRIPTS/STYLES FROM THE FOOTER
	add_action('wp_footer', 'spk_remove_scripts_styles_footer');
	function spk_remove_scripts_styles_footer() {
		wp_dequeue_style( 'soliloquy-style-css' );
		wp_deregister_style( 'soliloquy-style-css' );
	}

	// DEREGISTER CHILD THEME'S STYLE.CSS - it doesn't contain any styling and is classified by google as a render-blocking css
	add_action( 'wp_enqueue_scripts', 'spk_deregsiter_themes_style_css' );
	function spk_deregsiter_themes_style_css() {
	    wp_dequeue_style( 'basic-sass' );
	    wp_deregister_style( 'basic-sass' );
	}

}

/* ----------------------------------------------------------------------------
 * INCLUDE OTHER PLUGIN FILES
 * ------------------------------------------------------------------------- */
// shortcoder
//require_once( 'codec/spk_shortcoder.php' );
// quotes
require_once( 'codec/spk_quotes.php' );
// youtube embed, social toolbar and dynamic div transfer
require_once( 'codec/spk_master_plug_v1.php' );
// get permalink
require_once( 'codec/spk_get_permalink.php' );

// shortcode ultimate remnant
require_once( 'codec/spk_sc_get_post_content.php' );