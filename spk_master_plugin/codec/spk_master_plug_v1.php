<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* --------------------------------------------------------------------------------------------
 * | YOUTUBE EMBEDS
 * ----------------------------------------------------------------------------------------- */
function su_youtube_advanced_func( $atts ) {

    $a = shortcode_atts( array( 
        'url' => 'url',
        'autohide' => 'autohide',
        'https' => 'https',
        'class' => 'class',
    ), $atts );
    // $a['url'], $a['autohide'], $a['https'], $a['class']
    $vid = explode( "/", $a['url'] );
    $video_id = count($vid) - 1;
    
    $exp_vid = explode( "=", $vid[$video_id] );
    if( count( $exp_vid ) > 1 ) {
       // not using the embed URL
       $youtubeid = $exp_vid[count( $exp_vid ) - 1];
    } else {
       // using the embed URL
       $youtubeid = $vid[$video_id];
    }

    // check if site requested is secured
    if( $a['https'] == 'yes' ) {
        $secured = 'https';
    } else {
        $secured = 'http';
    }

    // download thumbnail
    spk_download_youtube_thumb( $youtubeid );

    // NOTE: browser caching retains the same image even after replacement
    $ytthumb_w_ver = plugins_url( "../images/youtubethumbs/0/".$youtubeid.".jpg", __FILE__ ); //."?".date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."../images/youtubethumbs/0/".$youtubeid.".jpg" ) );
    //                         <!--img src="'.$secured.'://img.youtube.com/vi/'.$youtubeid.'/0.jpg" class="thumbnail" id="thumbnail_'.$youtubeid.'" / -->
    $return = '<div class="module-video" id="'.$youtubeid.'">
                    <div class="video-image" id="video_image_'.$youtubeid.'"><div class="module-wrap">
                        <div class="video-play" id="video_play_'.$youtubeid.'"></div>
                        <img src="'.$ytthumb_w_ver.'" class="thumbnail" id="thumbnail_'.$youtubeid.'" />
                    </div></div>
                </div>';
    
    return $return;
    
}

/* --------------------------------------------------------------------------------------------
 * | DOWNLOAD YOUTUBE THUMBNAIL
 * ----------------------------------------------------------------------------------------- */
function spk_download_youtube_thumb( $youtubeid ) {

    $spk_file_dir = plugin_dir_path( __FILE__ ).'../images/youtubethumbs/0/';
        
    // set filename
    $filename = $spk_file_dir.$youtubeid.'.jpg';

    // set source
    $value = 'https://img.youtube.com/vi/'.$youtubeid.'/0.jpg';
    
    $key = NULL; // not really required but just good to have in place

    if( file_exists( $filename ) ) {

        // validate time stamps
        $start      = date('Y-m-d H:i:s'); 
        $end        = date('Y-m-d H:i:s',filemtime( $filename )); 
        $d_start    = new DateTime($start); 
        $d_end      = new DateTime($end); 
        $diff       = $d_start->diff($d_end);
        //var_dump($diff); echo '<hr>';

        $file_age = 14;

        /* $diff->d for days
         * $diff->h for hours
         * $diff->i for minutes
         */
        if( $diff->d >= $file_age ) {
            //echo $diff->d.' > '.$file_age.' <br />';
            spk_download_external_files_now( $filename, $key, $value );
        }/* else {
            echo $diff->d.' < '.$file_age.' <br />';
        }*/

    } else {
        // file doesn't exists
        //echo "file doesn't exists.";
        spk_download_external_files_now( $filename, $key, $value );
    }

}

/* --------------------------------------------------------------------------------------------
 * | FILTER URL
 * ----------------------------------------------------------------------------------------- */
function spk_url_origin( $s, $use_forwarded_host = false ) {
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function spk_full_url( $s, $use_forwarded_host = false ) {
    return spk_url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

/* --------------------------------------------------------------------------------------------
 * | SOCIAL TOOLBAR
 * ----------------------------------------------------------------------------------------- */
function swps_st_externals() {

    $absolute_url = spk_full_url( $_SERVER );

    // check which page we're at
    if ( is_front_page() ) {
        $share_text = 'Succeed more. Learn More. Grow more. Life is... relationships. — '.get_bloginfo( 'description' );
    } else {
        $share_text = get_the_title( get_the_ID() );
    }

    // How to Support My Work
    $howtosupportmywork = '<div class="wrap">
                                <div>
                                    <h5 class="space-bottom-half">How To Support My Work</h5>
                                    <a class="item-pic pic-paypal" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LKGTSSLYJ93J6"></a>
                                    <div class="fontsize-xxsml space-bottom-half">This is a member supported site. You tip your favorite bartender, right? How about a buck, $2, $3, $5, maybe $10? Whatever YOU feel its worth, every time you feel I have given you a good tip, new knowledge or helpful insight. Please feel free to donate any amount you think is equal to the value you received from my eBook & Home Study Course (audio lessons), articles, emails, videos, newsletters, etc.</div>
                                    <a class="button space-bottom-half" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LKGTSSLYJ93J6"><h5>DONATE VIA PAYPAL</h5></a>
                                    <div class="fontsize-xxsml space-bottom-half">Just click the "Donate" button above to enter your donation/gratuity. Thanks in advance for your support! From my heart to yours, Corey Wayne.</div>
                                </div>
                            </div>';
    
    $sharesocialmedia = '<div class="wrap">
                            <div><h6 class="space-bottom-half">Share Page on Social Media:</h6></div>
                            <span><div><a class="item-icon-bar icon-twitter" href="https://twitter.com/share?url='.$absolute_url.'&text='.$share_text.'" target="_blank"></a></div></span>
                            <span><div><a class="item-icon-bar icon-facebook" href="https://www.facebook.com/sharer/sharer.php?u='.$absolute_url.'" target="_blank"></a></div></span>
                            <span><div><a class="item-icon-bar icon-googleplus" href="https://plus.google.com/share?url='.$absolute_url.'" target="_blank"></a></div></span>
                            <span><div><a class="addthis_button_compact"></a></div></span>
                         </div>'; // <img src="http://s7.addthis.com/static/btn/sm-plus.gif" width="16" height="16" border="0" alt="Share" />

    $followcorey = '<span><a class="item-icon icon-twitter" href="https://www.twitter.com/CoachCoreyWayne"></a></span>
                    <span><a class="item-icon icon-facebook" href="https://www.facebook.com/Coach.Corey.Wayne"></a></span>
                    <span><a class="item-icon icon-linkedin" href="https://www.linkedin.com/in/coachcoreywayne"></a></span>
                    <span><a class="item-icon icon-youtube" href="https://www.youtube.com/user/coachcoreywayne?sub_confirmation=1"></a></span>
                    <span><a class="item-icon icon-googleplus" href="https://plus.google.com/+CoachCoreyWaynesOfficial/posts"></a></span>
                    <span><a class="item-icon icon-instagram" href="https://instagram.com/CoachCoreyWayne/"></a></span>
                    <span><a class="item-icon icon-pinterest" href="https://www.pinterest.com/coachcoreywayne/"></a></span>
                    <span><a class="item-icon icon-medium" href="https://medium.com/@coachcoreywayne"></a></span>';

    $media_bd = '<a href="'.get_permalink( spk_master_get_post_id( 'free-ebook' ) ).'"><h5 class="space-bottom-half">Free eBook &amp; Online Audio Program Access</h5></a>
                 <a href="https://amzn.to/1rlT7vd"><h5 class="space-bottom-half">Book - Audio Version Free $0.00 with an Audible.com Membership</h5></a>
                 <a href="https://amzn.to/1rlT7vd"><h5 class="space-bottom-half">Book - Audio Version $19.95 from Amazon.com</h5></a>
                 <a href="https://geo.itunes.apple.com/us/audiobook/how-to-be-a-3-man-unabridged/id1106013146?at=1l3vuUo&mt=3"><h5 class="space-bottom-half">Book - Audio Version $19.95 from iTunes</h5></a>
                 <a href="https://www.amazon.com/Winning-Heart-Woman-Your-Dreams-ebook/dp/B004QOBAPK/ref=as_sl_pc_ss_til?tag=understand0d4-20&amp;linkCode=w01&amp;linkId=7AK7WO7PJQCR2MPY&amp;creativeASIN=B004QOBAPK"><h5 class="space-bottom-half">Book - Kindle Version $9.99</h5></a>
                 <a href="https://www.amazon.com/Winning-Heart-Woman-Your-Dreams/dp/0692552669/ref=as_sl_pc_ss_til?tag=understand0d4-20&amp;linkCode=w01&amp;linkId=XKGGODHAZJIG4FEC&amp;creativeASIN=0692552669"><h5 class="space-bottom-half">Book - Paperback Version $29.99</h5></a>
                 <a href="https://geo.itunes.apple.com/us/book/how-to-be-3-man-winning-heart/id948035350?mt=11&uo=6&at=1l3vuUo"><h5 class="space-bottom-half">Book - iBook Version $9.99</h5></a>';
    
    ?>

    <!-- DESKTOP VERSION - DONATE / SUPPORT -->
    <div class="st_desktop_donate_pop">
        <?php echo $howtosupportmywork; ?>
    </div>

    <!-- DESKTOP VERSION - BOOK -->
    <div class="st_desktop_book_pop"><div class="wrap">
        <div class="media">
        <div class="media-img"><a class="item-pic pic-ebook" href="<?php echo get_permalink( spk_master_get_post_id( 'free-ebook' ) ); ?>"></a></div>
        <div class="media-bd"><?php echo $media_bd; ?></div>
        </div>
    </div></div>

    <!-- DESKTOP VERSION - PRODUCTS -->
    <div class="st_desktop_products_pop">
        <div class="wrap">
            <div class="media">
            <div class="media-img"><a class="item-icon icon-phonecoaching" href="<?php echo get_permalink( spk_master_get_post_id( 'phone-coaching' ) ); ?>"></a></div>
            <div class="media-bd"><a href="<?php echo get_permalink( spk_master_get_post_id( 'phone-coaching' ) ); ?>"><h5>1 Hour Phone/Skype Coaching Session</h5></a></div>
            </div>
            <div class="divider-line clearfix space-vertical-half"></div>
            <div class="media">
            <div class="media-img"><a class="item-icon icon-emailcoaching" href="<?php echo get_permalink( spk_master_get_post_id( 'email-coaching' ) ); ?>"></a></div>
            <div class="media-bd"><a href="<?php echo get_permalink( spk_master_get_post_id( 'email-coaching' ) ); ?>"><h5>Emergency Email Response Coaching</h5></a></div>
            </div>
            <div class="divider-line clearfix space-vertical-half"></div>
            <div class="media">
            <div class="media-img"><a class="item-icon icon-amazon" href="<?php echo get_permalink( '22910' ); ?>"></a></div>
            <div class="media-bd"><a href="<?php echo get_permalink( '22910' ); ?>"><h5>Self-Help Products, Books, Supplements, Etc. I Recommend</h5></a></div>
            </div>
        </div>
    </div>

    <!-- DESKTOP VERSION | PARENT -->
    <div class="st_desktop" id="st_desktop">
        <div class="wrap">

        <div class="st_desktop_follow"><?php echo $followcorey; ?></div>
            
        <div class="st_desktop_donate">
            <div class="st-desktop-cta-donate"></div>
        </div>

        <div class="st_desktop_book">
            <div class="st-desktop-cta-book"></div>
        </div>

        <div class="st_desktop_products">
            <div class="st-desktop-cta-product"></div>
        </div>

        <div class="st_desktop_coaching">
        </div>

        <div class="st_desktop_share"><?php echo $sharesocialmedia; ?></div>

        </div>
    </div>

    <!-- COMPACT VERSION - FOLLOW -->
    <div class="st_mobile_follow_2">
        <div class="wrap"><?php echo $followcorey; ?></div>
    </div>

    <!-- COMPACT VERSION - DONATE -->
    <div class="st_mobile_donate_2">
        <?php echo $howtosupportmywork; ?>
    </div>

    <!-- COMPACT VERSION - PRODUCTS -->
    <div class="st_mobile_buybook_2">
        <div class="wrap">
            <div class="media">
            <div class="media-img media-right"><a class="item-icon icon-phonecoaching" href="<?php echo get_permalink( spk_master_get_post_id( 'phone-coaching' ) ); ?>"></a></div>
            <div class="media-bd"><a href="<?php echo get_permalink( spk_master_get_post_id( 'phone-coaching' ) ); ?>"><h5>1 Hour Phone/Skype Coaching Session</h5></a></div>
            </div>
            <div class="divider-line clearfix space-vertical-half"></div>
            <div class="media">
            <div class="media-img media-right"><a class="item-icon icon-emailcoaching" href="<?php echo get_permalink( spk_master_get_post_id( 'email-coaching' ) ); ?>"></a></div>
            <div class="media-bd"><a href="<?php echo get_permalink( spk_master_get_post_id( 'email-coaching' ) ); ?>"><h5>Emergency Email Response Coaching</h5></a></div>
            </div>
            <div class="divider-line clearfix space-vertical-half"></div>
            <div class="media">
            <div class="media-img media-right"><a class="item-icon icon-amazon" href="<?php echo get_permalink( '22910' ); ?>"></a></div>
            <div class="media-bd"><a href="<?php echo get_permalink( '22910' ); ?>"><h5>Self-Help Products, Books, Supplements, Etc. I Recommend</h5></a></div>
            </div>
            <div class="divider-line clearfix space-vertical-half"></div>
            <div class="media">
            <div class="media-img media-right"><a class="item-pic pic-ebook" href="<?php echo get_permalink( spk_master_get_post_id( 'free-ebook' ) ); ?>"></a></div>
            <div class="media-bd"><?php echo $media_bd; ?></div>
            </div>
        </div>
    </div>

    <!-- COMPACT VERSION - SHARE -->
    <div class="st_mobile_share_2"><?php echo $sharesocialmedia; ?></div>

    <!-- COMPACT VERSION | PARENT -->
    <div class="st_mobile" id="st_mobile">
        <div class="st_mobile_follow">FOLLOW</div>
        <div class="st_mobile_donate">DONATE</div>
        <div class="st_mobile_buybook">PRODUCTS</div>
        <div class="st_mobile_share">SHARE</div>
    </div>

    <!-- SHOW or HIDE BUTTON -->
    <div class="showhide">
        <div class="showhide_icon"><img id="showhide_icon" src="<?php echo plugins_url( '../images/arrow.png', __FILE__ ); ?>" alt="top" class="rotate180" /></div>
    </div>

    <?php
    // .'#ver='.date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ ).'../images/arrow.png' ) )
}

/* --------------------------------------------------------------------------------------------
 * | GET POST ID
 * ----------------------------------------------------------------------------------------- */
function spk_master_get_post_id( $slug ) {
    global $wpdb;
    $query = $wpdb->get_results( "SELECT id FROM ".$wpdb->prefix."posts WHERE post_name='".$slug."' ", OBJECT );
    return $query[0]->id;
    wp_reset_postdata();
}

/* --------------------------------------------------------------------------------------------
 * | DYNAMIC DIV TRANSFERS
 * ----------------------------------------------------------------------------------------- */
function spk_transferdiv() {
    register_post_type( 'transferdivs',
        array(
            'labels' => array(
                'name' => __( 'TransferDIVs' ),
                'singular_name' => __( 'TransferDIV' ),
                'add_new' => __( 'Add New' ),
                'add_new_item' => __( 'Add New TransferDIV' ),
                'edit_item' => __( 'Edit' ),
                'new_item' => __( 'Add New' ),
                'view_item' => __( 'View' ),
                'search_items' => __( 'Search' ),
                'not_found' => __( 'No TransferDIV found' ),
                'not_found_in_trash' => __( 'No TransferDIV found in trash' )
            ),

            'public' => true,
            //'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
            'supports' => array( 'title' ),
            'capability_type' => 'post',
            'rewrite' => array("slug" => "transferdiv"), // Permalinks format
            'menu_position' => 5,
            'register_meta_box_cb' => 'add_transferdiv_metaboxes'
        )
    );
}

add_action( 'init', 'spk_transferdiv' );

/* --------------------------------------------------------------------------------------------
 * | Add Metabox
 * ----------------------------------------------------------------------------------------- */
function add_transferdiv_metaboxes() {
    add_meta_box( 'spk_transferdivs', 'Transfer Details', 'spk_transferdivz', 'TransferDIVs', 'normal', 'default' );
}

add_action( 'add_meta_boxes', 'add_transferdiv_metaboxes' );

/* --------------------------------------------------------------------------------------------
 * | Metabox Data | Callback function
 * ----------------------------------------------------------------------------------------- */
function spk_transferdivz() {
    global $post;

    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    // Validate check boxes - SOURCE
    if( get_post_meta($post->ID, 'spk_source_hide', true) == 'checked' ) {
        $spk_source_hide_yradio = 'checked="checked"';
        $spk_source_hide_nradio = '';
    } else {
        $spk_source_hide_yradio = '';
        $spk_source_hide_nradio = 'checked="checked"';
    }

    // Validate check boxes - TARGET
    if( get_post_meta($post->ID, 'spk_target_hide', true) == 'checked' ) {
        $spk_target_hide_yradio = 'checked="checked"';
        $spk_target_hide_nradio = '';
    } else {
        $spk_target_hide_yradio = '';
        $spk_target_hide_nradio = 'checked="checked"';
    }

    // Echo out the field
    echo '<table class="spk_cpt_table">
        <tr>
            <td style="padding:5px;"><strong>Source</strong></td>
            <td style="padding:5px;"><input type="text" name="spk_source" value="'.get_post_meta($post->ID, 'spk_source', true).'" class="spk_cpt_fields" /></td>
            <td style="padding:5px;">&nbsp;</td>
            <td style="padding:5px;">Hide <strong>source</strong> container after transfer? <input type="radio" name="spk_source_hide" value="checked" '.$spk_source_hide_yradio.' /> Yes | <input type="radio" name="spk_source_hide" value="no" '.$spk_source_hide_nradio.' /> No
        </tr>
        <tr>
            <td style="padding:5px;">&nbsp;</td>
            <td style="padding:5px;">Note: add the symbols hash (#) for ID or dot (.) if class.</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding:5px;"><strong>Target</strong></td>
            <td style="padding:5px;"><input type="text" name="spk_target" value="'.get_post_meta($post->ID, 'spk_target', true).'" class="spk_cpt_fields" /></td>
            <td style="padding:5px;">&nbsp;</td>
            <td style="padding:5px;">Hide <strong>target</strong> container after transfer? <input type="radio" name="spk_target_hide" value="checked" '.$spk_target_hide_yradio.' /> Yes | <input type="radio" name="spk_target_hide" value="no" '.$spk_target_hide_nradio.' /> No
        </tr>
        <tr>
            <td style="padding:5px;">&nbsp;</td>
            <td style="padding:5px;">Note: add the symbols hash (#) for ID or dot (.) if class.</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding:5px;"><strong>Trigger</strong></td>
            <td style="padding:5px;"><input type="number" name="spk_trigger" value="'.get_post_meta($post->ID, 'spk_trigger', true).'" class="spk_cpt_fields" /></td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding:5px;">&nbsp;</td>
            <td style="padding:5px;" colspan="3">Indicate the screen\'s width at which the transition will happen. Note: numbers only please.</td>
        </tr>
    </table>';
}

/* --------------------------------------------------------------------------------------------
 * | Save the Metabox Data
 * | @param int $post_id The post ID.
 * | @param post $post The post object.
 * | @param bool $update Whether this is an existing post being updated or not.
 * ----------------------------------------------------------------------------------------- */
add_action( 'save_post', 'spk_save_transferdiv_meta', 10, 3 );
function spk_save_transferdiv_meta( $post_id, $post, $update ) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);

    // If this isn't a 'transferdiv' post, don't update it.
    if ( "transferdivs" != $post_type ) return;

    // - Pick up all meta field values
    $transfer_meta['spk_source'] = $_POST['spk_source'];
    $transfer_meta['spk_source_hide'] = $_POST['spk_source_hide'];
    $transfer_meta['spk_target'] = $_POST['spk_target'];
    $transfer_meta['spk_target_hide'] = $_POST['spk_target_hide'];
    $transfer_meta['spk_trigger'] = $_POST['spk_trigger'];

    foreach ($transfer_meta as $key => $value) {
        if( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if( !$value ) delete_post_meta($post->ID, $key); // Delete if blank
    }

}

/* --------------------------------------------------------------------------------------------
 * | ENQUEUE ADDTHIS SCRIPTS | Async load external js
 * ----------------------------------------------------------------------------------------- */
function spk_theme_skrips() {
    wp_register_script( 'spk_add_this_external', site_url().'/wp-content/plugins/spk_master_plugin/js_external/addthis.js', NULL, date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ )."../js_external/addthis.js" ) ), TRUE );
    wp_enqueue_script( 'spk_add_this_external' );
}
add_action( 'wp_enqueue_scripts', 'spk_theme_skrips');

/* --------------------------------------------------------------------------------------------
 * | ENQUEUE SCRIPTS
 * ----------------------------------------------------------------------------------------- */
function spk_mpv_one_enqueue_our_scripts() {
    // last arg is true - will be placed before </body>
    //wp_enqueue_script( 'spk_master_plugins_v1_js', plugins_url( '../js/spk_asset_master_plug_v1_min.js', __FILE__ ), NULL, NULL, true );
    wp_register_script( 'spk_master_plugins_v1_js', plugins_url( '../js/spk_asset_master_plug_v1_min.js', __FILE__ ), NULL, '1.0', TRUE );
     
    // Localize the script with new data
    $translation_array = array(
        'spk_master_one_ajax' => plugin_dir_url( __FILE__ ).'../ajax/spk_master_plug_v1_ajax.php',
    );
    wp_localize_script( 'spk_master_plugins_v1_js', 'spk_master_one', $translation_array );
     
    // Enqueued script with localized data.
    wp_enqueue_script( 'spk_master_plugins_v1_js' );
}

/* --------------------------------------------------------------------------------------------
 * | EXECUTE
 * ----------------------------------------------------------------------------------------- */
if ( !is_admin() ) {

    // SHORTCODE - YOUTUBE EMBEDS
    add_shortcode( 'su_youtube_advanced', 'su_youtube_advanced_func' );

    // ENQUEUE SCRIPTS
    //add_action( 'wp_enqueue_scripts', 'spk_mpv_one_enqueue_our_scripts' );
    add_action( 'wp_footer', 'spk_mpv_one_enqueue_our_scripts', 5 );

    // ADD HTML & JAVASCRIPTS TO THE FOOTER FOR THE SOCIAL TOOLBAR
    // wp_footer | genesis_before_footer
    add_action( 'wp_footer', 'swps_st_externals', 1 );

}
