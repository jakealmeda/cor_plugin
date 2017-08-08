<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* --------------------------------------------------------------------------------------------
 * | GET POST CONTENT
 * ----------------------------------------------------------------------------------------- */
function su_post_func( $atts ) {

    $a = shortcode_atts( array( 
        'field' => 'field',
        'post_id' => 'post_id',
    ), $atts );

    $field =  $a['field'];

    $post_data = get_post( $a['post_id'] );
    if ($post_data) {
        return apply_filters( 'the_content', $post_data->$field );
    } else {
        return false;
    }

}

if ( !is_admin() ) {

    // SHORTCODE - GET POST CONTENT
    add_shortcode( 'su_post', 'su_post_func' );

}
