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

/* ----------------------------------------------------------------------------
 * Plugins
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