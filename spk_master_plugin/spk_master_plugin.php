<?php
/**
 * Plugin Name: SPK - Master Plugin
 * Description: Combining all SmarterWebPackages plugins into one: Quotes, Youtube Embed, Social Toolbar, Dynamic Transfers and Get Permalink
 * Version: 3.1
 * Author: Jake Almeda
 * Author URI: http://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* --------------------------------------------------------------------------------------------
 * | APPLY HACKS
 * ----------------------------------------------------------------------------------------- */
if ( !is_admin() ) {

	/*add_action( 'wp_enqueue_scripts', 'spk_remove_head_scripts' );
	function spk_remove_head_scripts() { 
	   remove_action('wp_head', 'wp_print_scripts'); 
	   remove_action('wp_head', 'wp_print_head_scripts', 9); 
	   remove_action('wp_head', 'wp_enqueue_scripts', 1);
	 
	   add_action('wp_footer', 'wp_print_scripts', 5);
	   add_action('wp_footer', 'wp_enqueue_scripts', 5);
	   add_action('wp_footer', 'wp_print_head_scripts', 5); 
	}*/
	
	// Defer Javascripts
	add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
    function defer_parsing_of_js ( $url ) {
        if( FALSE === strpos( $url, '.js' ) ) return $url;
        if( strpos( $url, 'jquery.js' ) ) return $url;//."' async='async";
      	
        return $url."' defer='defer";
    }

	// FORCE THE CRITICAL CSS TO LOAD INLINE (INSIDE <head></head> TAGS)
    add_action( 'wp_head', 'cor_critical_styling', 10 );
	function cor_critical_styling() {
		// check if critical style file exists
		//$spk_style_critical = get_stylesheet_directory() . '/style_critical_min.css';
		$spk_style_critical = get_stylesheet_directory() . '/style-min.css';
		if( file_exists( $spk_style_critical ) ) {
			$spk_verified = 1;
			$spk_style_critical = spk_redirect_css_image_urls( file_get_contents( $spk_style_critical ) );
		}

		// verify if minimized soliloquy css file exists
		$spk_soli_style = plugin_dir_path( __FILE__ ) . 'css/soliloquy_min.css';
		if( file_exists( $spk_soli_style ) ) {
			$spk_verified = $spk_verified + 1;
			$spk_soli_style = spk_redirect_soliloquy_image_urls( file_get_contents( $spk_soli_style ) );
		}

		// one or both file exists - display
		if( $spk_verified ) {
			echo '<style type="text/css">'.$spk_style_critical.$spk_soli_style.'</style>';
		}
	}
		
	// ADD NON-CRITICAL STYLING TO THE FOOTER
	// NOTE: Enqueued scripts are executed at priority level 20
	/*add_action( 'wp_footer', 'spk_delay_styling_func', 2 );
	function spk_delay_styling_func() {
		$spk_style_critical_non = get_stylesheet_directory() . '/style_critical_non_min.css';
		if( file_exists( $spk_style_critical_non ) ) {
			echo "<style type='text/css'>".spk_redirect_css_image_urls( file_get_contents( $spk_style_critical_non ) )."</style>";	
		}
	}*/

	// DEREGISTER SCRIPTS/STYLES FROM THE FOOTER
	add_action( 'wp_footer', 'spk_remove_scripts_styles_footer');
	function spk_remove_scripts_styles_footer() {
		wp_dequeue_style( 'soliloquy-style-css' );
		wp_deregister_style( 'soliloquy-style-css' );
	}

	add_action( 'wp_footer', 'spk_de_reg_sol_sript', 200 );
	function spk_de_reg_sol_sript() {
	    wp_deregister_script( 'soliloquy-script' );
		wp_register_script( 'soliloquy-script', plugins_url().'/soliloquy/assets/js/min/soliloquy-min.js', NULL, NULL, TRUE );
		wp_enqueue_script( 'soliloquy-script' );
	}

	// DEREGISTER CHILD THEME'S STYLE.CSS - it doesn't contain any styling and is classified by google as a render-blocking css
	add_action( 'wp_enqueue_scripts', 'spk_deregsiter_themes_style_css' );
	function spk_deregsiter_themes_style_css() {
		$child_theme_style_id = str_replace( ' ', '-', strtolower( CHILD_THEME_NAME ) );
	    wp_dequeue_style( $child_theme_style_id );
	    wp_deregister_style( $child_theme_style_id );
	}

	// DOWNLOAD GOOGLE JAVASCRIPT FILES AFTER VALIDATING THE FILE'S AGE (DAILY FILE DOWNLOAD)
	spk_download_external_files();

	// ALLOW PHP TO EXECUTE IN WIDGETS
	//add_filter( 'widget_text', 'php_execute', 100 );
	function php_execute( $html ) {
		if( strpos( $html, "<"."?php" ) !== false ) {
			ob_start();
			eval( "?".">".$html );
			$html = ob_get_contents();
			ob_end_clean();
		}
		return $html;
	}

	// Enable the use of shortcodes in text widgets.
	add_filter( 'widget_text', 'do_shortcode' );

	// DISPLAY JAVASCRIPT HANDLERS (REGISTERED NAMES)
	/*add_action( 'wp_print_scripts', 'wsds_detect_enqueued_scripts' );
	function wsds_detect_enqueued_scripts() {
		global $wp_scripts;
		foreach( $wp_scripts->queue as $handle ) :
			echo $handle . ' | ';
		endforeach;
	}*/

}

/* --------------------------------------------------------------------------------------------
 * | REPLACE THE IMAGE'S URL TO BE SPECIFIC
 * | INSTEAD OF images/name.jpg, it should be changed to FULL-URL/themes/images/name.jpg
 * ----------------------------------------------------------------------------------------- */
function spk_redirect_css_image_urls( $value ) {
	
	// images
	$value = str_replace( 'images/', get_stylesheet_directory_uri().'/images/', $value );
	$value = str_replace( "'images/", "'".get_stylesheet_directory_uri()."/images/", $value );
	$value = str_replace( '"images/', '"'.get_stylesheet_directory_uri().'/images/', $value );

	// add version
	$value = spk_add_timestamps( get_stylesheet_directory().'/images/', get_stylesheet_directory_uri().'/images/', $value, spk_images_in_styles() );

	// fonts
	$value = str_replace( 'fonts/', get_stylesheet_directory_uri().'/fonts/', $value );
	$value = str_replace( "'fonts/", "'".get_stylesheet_directory_uri()."/fonts/", $value );
	$value = str_replace( '"fonts/', '"'.get_stylesheet_directory_uri().'/fonts/', $value );

	// add version
	//return spk_add_timestamps( get_stylesheet_directory_uri().'/fonts/', $value );
	return $value;

}

/* --------------------------------------------------------------------------------------------
 * | REPLACE THE SOLILOQUY'S IMAGE URLs TO BE SPECIFIC
 * | INSTEAD OF images/name.jpg, it should be changed to PLUGIN-FULL-URL/soliloquy/assets/css/images/name.jpg
 * ----------------------------------------------------------------------------------------- */
function spk_redirect_soliloquy_image_urls( $value ) {

	$value = str_replace( 'images/', plugins_url().'/soliloquy/assets/css/images/', $value );
	$value = str_replace( "'images/", "'".plugins_url()."/soliloquy/assets/css/images/", $value );
	$value = str_replace( '"images/', '"'.plugins_url().'/soliloquy/assets/css/images/', $value );

	// add version
	$value = spk_add_timestamps( plugin_dir_path( __FILE__ ).'../soliloquy/assets/css/images/', plugins_url().'/soliloquy/assets/css/images/', $value, spk_images_in_soliloquy() );

	return $value;

}

/* --------------------------------------------------------------------------------------------
 * | GET THE FILE'S TIMESTAMP AND APPEND IT AFTER THE FILE EXTENSION
 * ----------------------------------------------------------------------------------------- */
function spk_add_timestamps( $path_dir, $path_url, $value, $array ){
	foreach( $array as $val ) {
		//echo $path_url.''.$val.' == '.date( 'Y-m-d H:i:s', filemtime( $path_dir.$val ) ).'<br />'; 
		$value = str_replace( $path_url.$val, $path_url.$val.'?ver='.date( 'YmdHis', filemtime( $path_dir.$val ) ), $value );
	}
	return $value;
}

/* --------------------------------------------------------------------------------------------
 * | FUNCTION TO DOWNLOAD EXTERNAL FILES
 * ----------------------------------------------------------------------------------------- */
function spk_download_external_files() {
	
	$spk_externals = array(
		'amazon_marketplace' 	=> 'http://z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=9f2cb097-ecee-468c-b007-0b4fcd5a22c9',
		'adsbygoogle' 			=> 'http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
		'google_analytics' 		=> 'https://www.google-analytics.com/analytics.js',
		'addthis'				=> 'http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5580891d2117b457',
		'fbds'					=> 'https://connect.facebook.net/en_US/fbds.js',
		); //		'osd' 					=> 'https://pagead2.googlesyndication.com/pagead/osd.js',

	// set directory
	//$spk_file_dir = dirname(__FILE__).'/js_external/';
	$spk_file_dir = plugin_dir_path( __FILE__ ).'js_external/';

	// loop through each entry
	foreach( $spk_externals as $key => $value ) {
		
		// set filename
		$filename = $spk_file_dir.$key.'.js';
		
    	if( file_exists( $filename ) ) {

    		// validate time stamps
			$start  	= date('Y-m-d H:i:s'); 
		    $end    	= date('Y-m-d H:i:s',filemtime( $filename )); 
		    $d_start    = new DateTime($start); 
		    $d_end      = new DateTime($end); 
		    $diff 		= $d_start->diff($d_end);
		    //var_dump($diff); echo '<hr>';

			$file_age = 1;

			/* $diff->d for days
			 * $diff->h for hours
			 * $diff->i for minutes
			 */
    		if( $diff->h >= $file_age ) {
    			//echo $diff->i.' > '.$file_age.' <br />';
    			spk_download_external_files_now( $filename, $key, $value );
    		}/* else {
    			echo $diff->i.' < '.$file_age.' <br />';
    		}*/

    	} else {
    		// file doesn't exists
    		//echo "file doesn't exists.";
    		spk_download_external_files_now( $filename, $key, $value );
    	}

	}
}

/* --------------------------------------------------------------------------------------------
 * | DOWNLOADER FUNCTION
 * ----------------------------------------------------------------------------------------- */
function spk_download_external_files_now( $filename, $key, $value ) {

	$value = file_get_contents( $value );

	// facebook's JS is already minified but they added comments in it caused it to be tagged by Google for minification
	if( $key == 'fbds' ) {
		$value = preg_replace( '!/\*.*?\*/!s', '', $value );
		$value = preg_replace( '/\n\s*\n/', "\n", $value );
		$value = preg_replace('/^[ \t]*[\r\n]+/m', '', $value);
	}

	if( file_put_contents( $filename, $value ) ) {
		return TRUE;
	}

}

/* --------------------------------------------------------------------------------------------
 * | ENQUEUE SCRIPTS
 * ----------------------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'spk_masterplug_js_scripts' );
function spk_masterplug_js_scripts() {

	$scripts = array( 'jquery-ui-core', 'jquery-effects-core', 'jquery-effects-slide', 'jquery-effects-fade', 'jquery-ui-accordion' );

	foreach ( $scripts as $value ) {
		if( !wp_script_is( $value, 'enqueued' ) ) {
        	wp_enqueue_script( $value );
    	}
	}

}

/* ----------------------------------------------------------------------------
 * INCLUDE OTHER PLUGIN FILES
 * ------------------------------------------------------------------------- */
// quotes
require_once( 'codec/spk_quotes.php' );
// youtube embed, social toolbar and dynamic div transfer
require_once( 'codec/spk_master_plug_v1.php' );
// get permalink
require_once( 'codec/spk_get_permalink.php' );
// sessions
//require_once( 'codec/spk_sessions.php' );
// shortcode ultimate remnant
require_once( 'codec/spk_sc_get_post_content.php' );
// hardcoded shortcodes
require_once( 'more_shortcodes.php' );
// list of images found in style.css (will be used for versioning)
require_once( 'images_in_styles_list.php' );
