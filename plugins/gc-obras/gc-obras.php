<?php
/**
 * Plugin Name: GC Obras
 * Description: Obras editables y galería para GC Ilustraciones.
 * Version: 0.2.2
 * Requires at least: 6.7
 * Requires PHP: 8.0
 * Text Domain: gc-obras
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/contact.php';

function gc_obras_register_type() {
	register_post_type( 'gc_work', array(
		'labels' => array(
			'name'          => 'Obras',
			'singular_name' => 'Obra',
			'add_new_item'  => 'Añadir obra',
			'edit_item'     => 'Editar obra',
		),
		'public'       => true,
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'obra' ),
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-art',
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	) );
}
add_action( 'init', 'gc_obras_register_type' );

register_activation_hook( __FILE__, function () {
	gc_obras_register_type();
	flush_rewrite_rules();
} );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

add_action( 'enqueue_block_editor_assets', function () {
	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base || 'gc_work' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_script(
		'gc-obras-editor-help',
		plugins_url( 'editor-help.js', __FILE__ ),
		array( 'wp-plugins', 'wp-editor', 'wp-element' ),
		'0.1.1',
		true
	);
} );

function gc_obras_visual( $post_id ) {
	$thumbnail = get_the_post_thumbnail(
		$post_id,
		'large',
		array( 'loading' => 'lazy' )
	);
	if ( $thumbnail ) {
		return '<span class="gc-work-visual">' . $thumbnail . '</span>';
	}
	if ( get_post_meta( $post_id, '_gc_demo', true ) ) {
		return '<span class="gc-work-visual gc-work-visual--demo gc-work-visual--' . absint( $post_id % 4 ) . '" aria-label="Imagen de muestra"></span>';
	}
	return '<span class="gc-work-visual">Imagen pendiente</span>';
}

function gc_obras_render_gallery() {
	$works = new WP_Query( array(
		'post_type'           => 'gc_work',
		'post_status'         => 'publish',
		'posts_per_page'      => 12,
		'orderby'             => 'menu_order title',
		'order'               => 'ASC',
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	) );
	if ( ! $works->have_posts() ) {
		return '<p class="gc-gallery-empty">Pronto compartiremos las primeras obras.</p>';
	}

	$has_demo = false;
	foreach ( $works->posts as $work ) {
		if ( get_post_meta( $work->ID, '_gc_demo', true ) ) {
			$has_demo = true;
			break;
		}
	}
	$output = $has_demo
		? '<p class="gc-sample-note">Contenido de muestra para evaluar el diseño. Las imágenes y las fichas se sustituirán por obras reales.</p>'
		: '';
	$output .= '<div class="gc-gallery">';
	while ( $works->have_posts() ) {
		$works->the_post();
		$post_id = get_the_ID();
		$title   = get_the_title();
		$excerpt = get_the_excerpt();
		$output .= '<article class="gc-work-card"><a class="gc-work-card__link" href="' . esc_url( get_permalink() ) . '">';
		$output .= gc_obras_visual( $post_id );
		$output .= '<h3 class="gc-work-card__title">' . esc_html( $title ) . '</h3></a>';
		if ( $excerpt ) {
			$output .= '<p class="gc-work-card__excerpt">' . esc_html( $excerpt ) . '</p>';
		}
		$output .= '</article>';
	}
	wp_reset_postdata();
	return $output . '</div>';
}

function gc_obras_render_visual() {
	$post_id = get_queried_object_id();
	return $post_id && get_post_type( $post_id ) === 'gc_work'
		? gc_obras_visual( $post_id )
		: '';
}

add_action( 'init', function () {
	wp_register_script(
		'gc-obras-blocks',
		plugins_url( 'blocks.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element' ),
		'0.2.1',
		true
	);
	register_block_type( 'gc-obras/gallery', array(
		'api_version'     => 3,
		'editor_script'   => 'gc-obras-blocks',
		'render_callback' => 'gc_obras_render_gallery',
	) );
	register_block_type( 'gc-obras/visual', array(
		'api_version'     => 3,
		'editor_script'   => 'gc-obras-blocks',
		'render_callback' => 'gc_obras_render_visual',
	) );
	register_block_type( 'gc-obras/email-contact', array(
		'api_version'     => 3,
		'editor_script'   => 'gc-obras-blocks',
		'render_callback' => 'gc_obras_render_email_contact',
	) );
	register_block_type( 'gc-obras/whatsapp-contact', array(
		'api_version'     => 3,
		'editor_script'   => 'gc-obras-blocks',
		'render_callback' => 'gc_obras_render_whatsapp_contact',
	) );
	register_block_type( 'gc-obras/work-contact', array(
		'api_version'     => 3,
		'editor_script'   => 'gc-obras-blocks',
		'uses_context'    => array( 'postId' ),
		'render_callback' => 'gc_obras_render_work_contact',
	) );
} );
