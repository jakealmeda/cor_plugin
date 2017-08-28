<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// images found in child theme's CSS
function spk_images_in_styles() {
	return array(
		'sprites.png',
		'sprites2x.png',
		'smarterwebpackages-240x60.png',
		'YouTube-icon-full_color.png',
		'pic-subscribe.png',
		'pic-subscribe-324.png',
		'siteheader.jpg',
		'siteheader2x.jpg',
	);
}

// images found in soliloquy's css
function spk_images_in_soliloquy() {
	return array(
		'left.png',
		'left@2x.png',
		'right.png',
		'right@2x.png',
		'circle.png',
		'circle@2x.png',
		'circle-hover.png',
		'circle-hover@2x.png',
		'pause.png',
		'pause@2x.png',
		'play.png',
		'play@2x.png',
		'video.png',
		'video@2x.png',
		'up.png',
		'down.png',
	);
}