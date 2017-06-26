<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* ----------------------------------------------------------------------------
 * add function upon initial WP load
 * ------------------------------------------------------------------------- */
//add_action( 'init', 'spk_sessions_func', 1 );
add_action( 'send_headers', 'spk_sessions_func' );
function spk_sessions_func() {

	if( ! is_admin() ) {
		
		if( !isset( $_COOKIE[ 'understandingrelationships' ] ) ) {
			
			setcookie( 'understandingrelationships', 'ur_dot_com_cookie', ( time() + strtotime( '30 minutes' ) ), "/" );

			//echo 'Session: '.$_COOKIE[ 'understandingrelationships' ];

			//* Apply widget area - cswbefore
			add_action( 'genesis_before_content_sidebar_wrap', 'base226_cswbefore' );
			function base226_cswbefore() {
				if (is_front_page()) {
					genesis_widget_area( 'cswbefore-home', array(
						'before' => '<div class="transfer-subscribeto hide-desktop"><div class="widget-wrap">'.spk_get_specific_post_content( '22811' ),
						'after' => '</div></div>',
					) );
				} else {
					genesis_widget_area( 'cswbefore', array(
						'before' => '<div class="transfer-subscribeto hide-desktop"><div class="widget-wrap">'.spk_get_specific_post_content( '22811' ),
						'after' => '</div></div>',
					) );
				}
			}

		}    

	}
}

function spk_get_specific_post_content( $post_id ) {
	return do_shortcode( get_post_field( 'post_content', $post_id, $context = 'display' ) );
}