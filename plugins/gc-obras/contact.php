<?php
/**
 * WhatsApp settings and contact links for GC Obras.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gc_obras_normalize_whatsapp_number( $value ) {
	if ( ! is_string( $value ) ) {
		return '';
	}
	$value = trim( $value );
	if ( '' === $value || ! preg_match( '/^\+?[0-9\s().-]+$/', $value ) ) {
		return '';
	}
	$digits = preg_replace( '/\D+/', '', $value );
	return preg_match( '/^[0-9]{8,15}$/', $digits ) ? $digits : '';
}

function gc_obras_sanitize_whatsapp_number( $value ) {
	if ( is_string( $value ) && '' === trim( $value ) ) {
		return '';
	}
	$number = gc_obras_normalize_whatsapp_number( $value );
	if ( $number ) {
		return $number;
	}
	add_settings_error(
		'gc_obras_whatsapp_number',
		'invalid_whatsapp_number',
		'Introduce un número internacional de entre 8 y 15 cifras, con su prefijo de país.',
		'error'
	);
	return get_option( 'gc_obras_whatsapp_number', '' );
}

function gc_obras_whatsapp_number() {
	return gc_obras_normalize_whatsapp_number( get_option( 'gc_obras_whatsapp_number', '' ) );
}

function gc_obras_whatsapp_url( $message ) {
	$number = gc_obras_whatsapp_number();
	return $number ? 'https://wa.me/' . $number . '?text=' . rawurlencode( $message ) : '';
}

add_action( 'admin_menu', function () {
	add_submenu_page(
		'edit.php?post_type=gc_work',
		'Contacto',
		'Contacto',
		'manage_options',
		'gc-obras-contacto',
		'gc_obras_contact_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'gc_obras_contacto', 'gc_obras_whatsapp_number', array(
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'gc_obras_sanitize_whatsapp_number',
	) );
	add_settings_section(
		'gc_obras_whatsapp',
		'WhatsApp',
		function () {
			echo '<p>Este número se muestra en la portada y recibe las consultas sobre cada obra.</p>';
		},
		'gc-obras-contacto'
	);
	add_settings_field(
		'gc_obras_whatsapp_number',
		'Número de WhatsApp',
		function () {
			$number = gc_obras_whatsapp_number();
			echo '<input type="tel" class="regular-text" id="gc_obras_whatsapp_number" name="gc_obras_whatsapp_number" value="' . esc_attr( $number ? '+' . $number : '' ) . '" placeholder="+34 600 000 000" autocomplete="tel">';
			echo '<p class="description">Incluye el prefijo internacional. Puedes dejarlo vacío para usar solo el correo.</p>';
		},
		'gc-obras-contacto',
		'gc_obras_whatsapp'
	);
} );

function gc_obras_contact_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>Contacto de Obras</h1>';
	settings_errors( 'gc_obras_whatsapp_number' );
	echo '<form method="post" action="options.php">';
	settings_fields( 'gc_obras_contacto' );
	do_settings_sections( 'gc-obras-contacto' );
	submit_button( 'Guardar contacto' );
	echo '</form></div>';
}

function gc_obras_render_whatsapp_contact() {
	$number = gc_obras_whatsapp_number();
	if ( ! $number ) {
		return '';
	}
	$message = 'Hola, me gustaría consultar sobre las obras de GC Ilustraciones.';
	$url = gc_obras_whatsapp_url( $message );
	$display = '34' === substr( $number, 0, 2 ) && 11 === strlen( $number )
		? '+34 ' . substr( $number, 2, 3 ) . ' ' . substr( $number, 5, 3 ) . ' ' . substr( $number, 8 )
		: '+' . $number;
	return '<a class="gc-contact__link gc-contact__link--whatsapp" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( 'WhatsApp: ' . $display ) . '"><span class="gc-whatsapp-icon" aria-hidden="true"></span><span>' . esc_html( $display ) . '</span></a>';
}

function gc_obras_render_work_contact( $attributes, $content, $block ) {
	$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_queried_object_id();
	if ( ! $post_id || 'gc_work' !== get_post_type( $post_id ) ) {
		return '';
	}
	$number = gc_obras_whatsapp_number();
	if ( ! $number ) {
		return '<a class="gc-button" href="mailto:contacto@gcilustraciones.com">Consultar por correo <span aria-hidden="true">↗</span></a>';
	}
	$title = wp_strip_all_tags( get_the_title( $post_id ) );
	$message = sprintf( 'Hola, me interesa la obra «%s». La vi aquí: %s', $title, get_permalink( $post_id ) );
	return '<a class="gc-button gc-button--whatsapp" href="' . esc_url( gc_obras_whatsapp_url( $message ) ) . '" target="_blank" rel="noopener noreferrer"><span class="gc-whatsapp-icon" aria-hidden="true"></span>Consultar por WhatsApp</a>';
}
