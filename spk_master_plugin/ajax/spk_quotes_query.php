<?php

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-config.php' );
require_once( '../lib/spk_config.php' );

global $wpdb;

$this_id = $_REQUEST['current_id'];

// define array
$t_ids = array();

// query all posts except for the one already displayed

$query = "SELECT id FROM ".$wpdb->prefix."posts WHERE post_status = 'publish' and post_type = 'ur_quote' and id <> '".$this_id."'";
$posts = $wpdb->get_results( $query, OBJECT );

foreach( $posts as $post ) {
    // put all IDs in an array
    $t_ids[] = $post->id;
}

// reset default post
wp_reset_postdata();

// get random post id
$pid = $t_ids[ array_rand($t_ids) ];

// get and display the post_content
$query2 = "SELECT id, post_content FROM ".$wpdb->prefix."posts WHERE post_status = 'publish' and post_type = 'ur_quote' and id = '".$pid."'";
$posts2 = $wpdb->get_results( $query2, OBJECT );

echo $posts2[0]->post_content.'<br />By: '.get_the_terms( $posts2[0]->id, 'quoter' )[0]->name.' | '.$pid;

// reset default post
wp_reset_postdata();