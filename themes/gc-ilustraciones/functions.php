<?php
/**
 * GC Ilustraciones child theme.
 */

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'gc-ilustraciones',
		get_stylesheet_directory_uri() . '/assets/site.css',
		array(),
		filemtime( get_stylesheet_directory() . '/assets/site.css' )
	);
} );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'post-thumbnails' );
} );
