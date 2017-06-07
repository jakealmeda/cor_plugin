<?php

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-config.php' );
require_once( '../lib/spk_config.php' );

global $wpdb;

$posts = $wpdb->get_results( "SELECT
    b.meta_key,
    b.meta_value
    FROM ".$wpdb->prefix."posts a, ".$wpdb->prefix."postmeta b
    WHERE
    a.ID = b.post_id and
    a.post_status = 'publish' and
    b.meta_key in ( 'spk_trigger', 'spk_target', 'spk_source', 'spk_source_hide', 'spk_target_hide' )
    ORDER BY a.post_date DESC" );

foreach( $posts as $post ) {

    if( $post->meta_key == "spk_source" ) {
        $spk_source = $post->meta_value;
        $counter++;
    }

    if( $post->meta_key == "spk_source_hide" ) {
        $spk_source_hide = $post->meta_value;
        $counter++;
    }

    if( $post->meta_key == "spk_target" ) {
        $spk_target = $post->meta_value;
        $counter++;
    }

    if( $post->meta_key == "spk_target_hide" ) {
        $spk_target_hide = $post->meta_value;
        $counter++;
    }

    if( $post->meta_key == "spk_trigger" ) {
        $spk_trigger = $post->meta_value;
        $counter++;
    }

    if( $counter == 5 ) {
        echo $spk_source.", ".$spk_source_hide.", ".$spk_target.", ".$spk_target_hide.", ".$spk_trigger."|";
        $counter = 0;
    }

}

// Restore original Post Data
wp_reset_postdata();