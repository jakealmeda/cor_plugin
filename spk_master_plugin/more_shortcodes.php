<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* --------------------------------------------------------------------------------------------
 * | Genesis Header Scripts
 * | --------------
 * | REGISTER SHORTCODE TO HIDE GOOGLE'S OWN SCRIPTS FROM BEING TAGGED BY THEM
 * ----------------------------------------------------------------------------------------- */
add_shortcode( 'spk_genesis_header_scripts_js', 'spk_genesis_header_scripts_js_func' );
function spk_genesis_header_scripts_js_func() {
	if( spk_bot_detected() ) {
		return '<meta name="p:domain_verify" content="0a4ace3e1ac7c1854a32de7541879163"/>
				
				<script async src="'.plugin_dir_url( __FILE__ )."js_external/adsbygoogle.js?ver=".date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."js_external/adsbygoogle.js" ) ).'"></script>
				<script>
				  (adsbygoogle = window.adsbygoogle || []).push({
				    google_ad_client: "ca-pub-0947746501358966",
				    enable_page_level_ads: true
				  });
				</script>';
	}
}

/* --------------------------------------------------------------------------------------------
 * | Genesis Footer Scripts
 * | --------------
 * | REGISTER SHORTCODE TO HIDE GOOGLE'S OWN SCRIPTS FROM BEING TAGGED BY THEM
 * ----------------------------------------------------------------------------------------- */
add_shortcode( 'spk_genesis_footer_scripts_js', 'spk_genesis_footer_scripts_js_func' );
function spk_genesis_footer_scripts_js_func() {
	if( spk_bot_detected() ) {
		return "<script> 
				 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ 
				 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), 
				 
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) 
				 })(window,document,'script','".plugin_dir_url( __FILE__ ).'js_external/google_analytics.js?ver='.date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."js_external/google_analytics.js" ) )."','ga'); 
				 ga('create', 'UA-556922-1', 'auto'); 
				 ga('send', 'pageview'); 
				</script>

				<script>(function() {
				  var _fbq = window._fbq || (window._fbq = []);
				  if (!_fbq.loaded) {
				    var fbds = document.createElement('script');
				    fbds.async = true;
				    fbds.src = '".plugin_dir_url( __FILE__ ).'js_external/fbds.js?ver='.date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."js_external/fbds.js" ) )."';
				    var s = document.getElementsByTagName('script')[0];
				    s.parentNode.insertBefore(fbds, s);
				    _fbq.loaded = true;
				  }
				  _fbq.push(['addPixelId', '342285032648063']);
				})();
				window._fbq = window._fbq || [];
				window._fbq.push(['track', 'PixelInitialized', {}]);
				</script>
				<noscript><img height='1' width='1' alt='' style='display:none' src='https://www.facebook.com/tr?id=342285032648063&amp;ev=PixelInitialized' /></noscript>


				<script>
				  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				  })(window,document,'script','".plugin_dir_url( __FILE__ ).'js_external/google_analytics.js?ver='.date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."js_external/google_analytics.js" ) )."','ga');

				  ga('create', 'UA-90942410-1', 'auto');
				  ga('send', 'pageview');

				</script>";
	}
}

/* --------------------------------------------------------------------------------------------
 * | Widget entry
 * | --------------
 * | REGISTER SHORTCODE TO HIDE GOOGLE'S OWN SCRIPTS FROM BEING TAGGED BY THEM
 * ----------------------------------------------------------------------------------------- */
add_shortcode( 'spk_adsbygoogle_js', 'spk_hide_me_from_google_pagespeedinsights' );
function spk_hide_me_from_google_pagespeedinsights() {
	if( spk_bot_detected() ) {
    	return '<script async src="'.plugin_dir_url( __FILE__ )."js_external/adsbygoogle.js?ver=".date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."js_external/adsbygoogle.js" ) ).'"></script>
				<!-- Page & Post Article Body Resposive Ad -->
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-client="ca-pub-0947746501358966"
				     data-ad-slot="7597430493"
				     data-ad-format="auto"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>';
	}
}

/* --------------------------------------------------------------------------------------------
 * | Widget entry
 * | --------------
 * | REGISTER SHORTCODE TO HIDE GOOGLE'S OWN SCRIPTS FROM BEING TAGGED BY THEM - 2
 * ----------------------------------------------------------------------------------------- */
add_shortcode( 'spk_google_suggested_articles_js', 'spk_hide_me_from_google_pagespeedinsights_2' );
function spk_hide_me_from_google_pagespeedinsights_2() {
	if( spk_bot_detected() ) {
    	return '<script async src="'.plugin_dir_url( __FILE__ )."js_external/adsbygoogle.js?ver=".date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."js_external/adsbygoogle.js" ) ).'"></script>
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-format="autorelaxed"
				     data-ad-client="ca-pub-0947746501358966"
				     data-ad-slot="2135583692"></ins>
				<script>
				     (adsbygoogle = window.adsbygoogle || []).push({});
				</script>';
	}
}

/* --------------------------------------------------------------------------------------------
 * | Putting the Amazon scripts here to test if we can enqueue the scripts properly
 * ----------------------------------------------------------------------------------------- */
add_shortcode( 'spk_amazon_market_place', 'spk_amazon_market_place_func' );
function spk_amazon_market_place_func() {
	//if( strpos( $_SERVER['HTTP_USER_AGENT'], "Google Page Speed Insights" ) == FALSE ) {
	if( spk_bot_detected() ) {
		return '<script src="'.plugin_dir_url( __FILE__ )."js_external/amazon_marketplace.js?ver=".date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."js_external/amazon_marketplace.js" ) ).'"></script>';
	}
}

/* --------------------------------------------------------------------------------------------
 * | Function to hide scripts from bots
 * ----------------------------------------------------------------------------------------- */
function spk_bot_detected() {
	/*$x=0;	

	$agents = array(
				'Google Page Speed Insights', 	// Google
				'Gecko/20100101', 				// GTmetrix
			);

	foreach ($agents as $value) {
		if( strpos( $_SERVER['HTTP_USER_AGENT'], $value ) == FALSE ) {
			$x++;
		}
	}

	if( $x == count( $agents ) ) {
		return TRUE;
	}*/return TRUE;
}

/* --------------------------------------------------------------------------------------------
 * | Signature Shortcode
 * | --------------
 * | THIS SHORTCODE SIMPLY RETURNS THE CURRENT SITE ADDRESS
 * | BEST USED FOR IMAGES STORED IN THE SERVER WHICH CAN'T BE ACCESSED WITHIN WORDPRESS
 * ----------------------------------------------------------------------------------------- */
add_shortcode( 'spk_site_url', 'spk_site_url_func' );
function spk_site_url_func() {
	return site_url();
}

/* --------------------------------------------------------------------------------------------
 * | SET UPLOAD DIRECTORY URL
 * ----------------------------------------------------------------------------------------- */
add_shortcode( 'spk_uploads_dir', 'spk_uploads_dir_func' );
function spk_uploads_dir_func( $atts ) {

    $a = shortcode_atts( array( 
        'type' => 'type',
    ), $atts );

    $uploads = wp_upload_dir();

    if( $a[ 'type' ] && $a[ 'type' ] != 'type' ) {
    	return $uploads[ $a[ 'type' ] ];
    }
}

/* --------------------------------------------------------------------------------------------
 * | UPLOADS LINK IMAGE (ANCHOR)
 * ----------------------------------------------------------------------------------------- */
//add_shortcode( 'spk_uploads_dir_anchor', 'spk_uploads_dir_anchor_func' );
function spk_uploads_dir_anchor_func( $atts, $content = "" ) {

    $a = shortcode_atts( array( 
        'image' 	=> 'image',
        'alt'		=> 'alt',
        'width' 	=> 'width',
        'height'	=> 'height',
        'class'		=> 'class',
    ), $atts );

    $uploads = wp_upload_dir();

	//if( strpos( $_SERVER['HTTP_USER_AGENT'], "Google Page Speed Insights" ) == FALSE ) {
	if( $a[ 'image' ] && $a[ 'image' ] != 'image' ) {
		// <a href="http://cor.smartwebpackages.com/updated/wp-content/uploads/iStock-486259358.jpg"><img src="http://cor.smartwebpackages.com/updated/wp-content/uploads/iStock-486259358-300x200.jpg" alt="" width="300" height="200" class="size-medium wp-image-28002" /></a>
		return '<a href="'.$uploads[ "baseurl" ].'/'.$a[ "image" ].'"><img src="'.$uploads[ "baseurl" ].'/'.$a[ "image" ].'" alt="'.$a[ "alt" ].'" width="'.$a[ "width" ].'" height="'.$a[ "height" ].'"  class="'.$a[ "class" ].'" /></a>';
	} // [spk_uploads_dir_anchor image="iStock-486259358.jpg" alt="" width="300" height="200" class="size-medium wp-image-28002"][/spk_uploads_dir_anchor]

}
