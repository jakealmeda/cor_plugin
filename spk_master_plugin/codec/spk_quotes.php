<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* --------------------------------------------------------------------------------------------
 * | Register Custom Taxonomy
 * ----------------------------------------------------------------------------------------- */
add_action( 'init', 'ur_quote_taxonomy_func');
function ur_quote_taxonomy_func() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Quoter', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Quoter', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Quoters', 'textdomain' ),
        'all_items'         => __( 'All Quoters', 'textdomain' ),
        'parent_item'       => __( 'Parent Quoter', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Quoter:', 'textdomain' ),
        'edit_item'         => __( 'Edit Quoter', 'textdomain' ),
        'update_item'       => __( 'Update Quoter', 'textdomain' ),
        'add_new_item'      => __( 'Add New Quoter', 'textdomain' ),
        'new_item_name'     => __( 'New Quoter Name', 'textdomain' ),
        'menu_name'         => __( 'Quoter', 'textdomain' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
    );

    register_taxonomy( 'quoter', 'ur_quote', $args );

}

/* --------------------------------------------------------------------------------------------
 * | Register Custom Post Type
 * ----------------------------------------------------------------------------------------- */
add_action( 'init', 'ur_quote' );
function ur_quote() {
    register_post_type( 'ur_quote',
        array(
            'labels' => array(
                'name' => __( 'Quotes' ),
                'singular_name' => __( 'Quote' ),
                'add_new' => __( 'Add New' ),
                'add_new_item' => __( 'Add New Quote' ),
                'edit_item' => __( 'Edit' ),
                'new_item' => __( 'Add New' ),
                'view_item' => __( 'View' ),
                'search_items' => __( 'Search' ),
                'not_found' => __( 'No Quote found' ),
                'not_found_in_trash' => __( 'No Quotes found in trash' )
            ),

            'public'                => true,
            //'supports'            => array( 'title', 'editor', 'thumbnail', 'comments' ),
            'supports'              => array( 'title', 'editor' ),
            'capability_type'       => 'post',
            'rewrite'               => array("slug" => "quote"), // Permalinks format
            'menu_position'         => 70,
            'menu_icon'             => 'dashicons-palmtree',
            'taxonomies'            => array( 'quoter' ),
            'register_meta_box_cb'  => 'spk_custom_meta_box',
            
        )
    );
}

/* --------------------------------------------------------------------------------------------
 * | Switch Metaboxes
 * ----------------------------------------------------------------------------------------- */
add_action('add_meta_boxes', 'spk_custom_meta_box');
function spk_custom_meta_box() {

    remove_meta_box( 'quoterdiv', 'ur_quote', 'side' );

    add_meta_box( 'quoterdiv-types', 'Quoter', 'quoter_meta_box', 'ur_quote', 'side' );

}

/* --------------------------------------------------------------------------------------------
 * | Display Taxonomy in dropdown format
 * ----------------------------------------------------------------------------------------- */
function quoter_meta_box($post) {

    $tax_name = 'quoter';
    $taxonomy = get_taxonomy($tax_name);

    ?>

    <div class="tagsdiv" id="<?php echo $tax_name; ?>">
        <div class="jaxtag">
        <p class="howto">Select Quoter</p>
        <?php 
        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), 'types_noncename' );
        $type_IDs = wp_get_object_terms( $post->ID, 'quoter', array('fields' => 'ids') );
        wp_dropdown_categories('taxonomy=quoter&hide_empty=0&orderby=name&name=quoter&show_option_none=Select Quoter&selected='.$type_IDs[0]);
        ?>
        </div>
    </div>

    <?php
}

/* --------------------------------------------------------------------------------------------
 * | When the post is saved, saves our custom taxonomy
 * ----------------------------------------------------------------------------------------- */
add_action( 'save_post', 'types_save_postdata' );
function types_save_postdata( $post_id ) {
    // verify if this is an auto save routine. 
    // If it is our form has not been submitted, so we dont want to do anything
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) 
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['types_noncename'], plugin_basename( __FILE__ ) ) )
        return;

    // Check permissions
    if ( !current_user_can( 'edit_post', $post->ID ) )
        return $post->ID;

    // OK, we're authenticated: we need to find and save the data

    $type_ID = $_POST['quoter'];

    $type = ( $type_ID > 0 ) ? get_term( $type_ID, 'quoter' )->slug : NULL;

    wp_set_object_terms( $post_id , $type, 'quoter' );

}

/* --------------------------------------------------------------------------------------------
 * | Edit CPT column(s)
 * ----------------------------------------------------------------------------------------- */
add_filter( 'manage_edit-ur_quote_columns', 'my_edit_ur_quote_columns' ) ;
function my_edit_ur_quote_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),
        'quoter' => __( 'Quoter' ),
        'quote' => __( 'Quote' ),
        'date' => __( 'Date' )
    );

    return $columns;
}

/* --------------------------------------------------------------------------------------------
 * | Add content to the CPT column(s)
 * ----------------------------------------------------------------------------------------- */
add_action( 'manage_ur_quote_posts_custom_column', 'my_manage_ur_quote_columns', 10, 2 );
function my_manage_ur_quote_columns( $column, $post_id ) {
    global $post;

    switch( $column ) {

        // If displaying the 'quoter' column
        case 'quoter' :

            // Get the post meta
            $quoter = get_the_terms( $post->id, 'quoter' )[0]->name;

            // If no quote is found, output a default message
            if ( empty( $quoter ) )
                echo __( 'Unknown' );

            // Show quote
            else
                echo __( $quoter );

            break;

        // If displaying the 'quote' column
        case 'quote' :

            // Get the post meta
            $quote = $post->post_content;

            // If no quote is found, output a default message
            if ( empty( $quote ) )
                echo __( 'Unknown' );

            // Show quote
            else
                echo wp_trim_words( $quote, $num_words = 40, '..' );

            break;
    }
}

/* --------------------------------------------------------------------------------------------
 * | Adjust column widths
 * ----------------------------------------------------------------------------------------- */
add_action('admin_head', 'hidey_admin_head');
function hidey_admin_head() {

    $screen = get_current_screen();
    
    if( 'ur_quote' == $screen->post_type ) {
        echo '<style type="text/css">';
        echo '.column-quote { width:50% !important; overflow:hidden }';
        echo '</style>';
    }
    /*
    echo '<style type="text/css">';
    echo '.column-date { display: none }';
    echo '.column-tags { display: none }';
    echo '.column-author { width:30px !important; overflow:hidden }';
    echo '.column-categories { width:30px !important; overflow:hidden }';
    echo '.column-title a { font-size:30px !important }';
    echo '</style>';
    */
}

/* --------------------------------------------------------------------------------------------
 * | Add shortcode function
 * ----------------------------------------------------------------------------------------- */
function ur_quote_func() {

    // define array
    $t_ids = array();

    // query all posts except for the one already displayed
    global $wpdb;
    $query = "SELECT id FROM ".$wpdb->prefix."posts WHERE post_status = 'publish' and post_type = 'ur_quote'";
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
    $query2 = "SELECT id, post_content FROM ".$wpdb->prefix."posts
                WHERE post_status = 'publish' and post_type = 'ur_quote' and id = '".$pid."'";
    $posts2 = $wpdb->get_results( $query2, OBJECT );
    
    return '<span id="quote_content">'.$posts2[0]->post_content.'<br />By: '.get_the_terms( $posts2[0]->id, 'quoter' )[0]->name.'</span><input type="hidden" value="'.$pid.'" id="ur_quote_pid" />';

    // reset default post
    wp_reset_postdata();

}

/* --------------------------------------------------------------------------------------------
 * | Enqueue scripts
 * ----------------------------------------------------------------------------------------- */
function ur_quote_enqueue_our_scripts() {
    // last arg is true - will be placed before </body>
    //wp_enqueue_script( 'ur_quote_js', plugin_dir_url( __FILE__ ).'../js/spk_asset_quotes_min.js', NULL, NULL, TRUE );
    wp_register_script( 'ur_quote_js', plugins_url( '../js/spk_asset_quotes_min.js', __FILE__ ), NULL, '1.0', TRUE );
     
    // Localize the script with new data
    $translation_array = array(
        'spk_quotes_ajax' => plugin_dir_url( __FILE__ ).'../ajax/spk_quotes_query.php',
    );
    wp_localize_script( 'ur_quote_js', 'spk_quoters', $translation_array );
     
    // Enqueued script with localized data.
    wp_enqueue_script( 'ur_quote_js' );
}


if ( !is_admin() ) {
    // register shortcode
    add_shortcode( 'ur_quote', 'ur_quote_func' );

    // JS | last arg is true - will be placed before </body>
    //add_action( 'wp_enqueue_scripts', 'ur_quote_enqueue_our_scripts' );
    add_action( 'wp_footer', 'ur_quote_enqueue_our_scripts', 5 );
}
