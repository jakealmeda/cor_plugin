<?php

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-config.php' );
require_once( '../lib/spk_config.php' );

$taxes = get_post_taxonomies( $_REQUEST[ 'pid' ] );
if( is_array( $taxes ) ) {

	foreach( $taxes as $val ) {
		$taxy .= '<a class="onmouseover" id="db_cfields_'.$val.'">{@'.$val.'}</a><br />';
	}

	if( !$taxy )
		$taxy = 'No Taxonomy';

	echo $taxy;
}