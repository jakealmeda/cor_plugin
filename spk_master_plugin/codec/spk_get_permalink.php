<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function spk_getpermalink_func( $atts, $content = null ) {
    
    // RETRIEVE ATTRIBUTE(S)
    // ---------------------------------------------------------------------
    $attr = shortcode_atts( array(
            'post' => 'post',
            'class' => 'class',
            'target' => 'target',
        ), $atts );
    
    $thispost = $attr['post'];

    if( is_numeric( $thispost ) ) {
        // ID was used
        // ----------------------------------------
        $arg = $thispost;
        
    } else {

        if( is_page( $thispost ) ) {
            // Title was used
            // ----------------------------------------
            $arg = get_page_by_title( $thispost );
            
        } else {
            // Slug for PAGES
            // ----------------------------------------
            $page = get_page_by_path( $thispost , OBJECT );
            if( isset( $page ) ) {
                $arg = get_page_by_title( $page->post_title );
            }
            
            // Slug or Title for POST
            // ----------------------------------------
            if( !$arg ) {
                
                // check if var has spaces - spaces means, var is a title. Otherwise, its a slug
                $exp_thispost = explode( " ", $thispost );
                if( count( $exp_thispost ) > 1 ) {
                    // title
                    $extra_arg = "post_title = '".$thispost."' ";
                } else {
                    // slug
                    $extra_arg = "post_name = '".$thispost."' ";
                }
                
                global $wpdb;
                $query = $wpdb->get_results( "SELECT id FROM ".$wpdb->prefix."posts WHERE ".$extra_arg, OBJECT );
                $arg = $query[0]->id;
                wp_reset_postdata();
            }
            
        }
    }

    // open the link in a new window
    if( $attr['target'] != 'target' ) {
        $target = 'target="'.$attr['target'].'"';
    }

    // check for contents - this will be used as the display text
    if( $content ) {
        $texts = $content;
    } else {
        $texts = get_the_title( $arg );
    }
    
    // check if there's class
    if( $attr['class'] ) {
        $class = "class='".$attr['class']."' ";
    } else {
        $class = "";
    }

    return '<a href="'.esc_url( get_permalink( $arg ) ).'" '.$class.' '.$target.'>'.$texts.'</a>';

}

if ( !is_admin() ) {

    // register shortcodes
    add_shortcode( 'spk_getpermalink', 'spk_getpermalink_func' );

}