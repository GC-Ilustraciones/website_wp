<?php
/**
 * Idempotent local sample content for `wp eval-file`.
 */

$gc_host = wp_parse_url( home_url(), PHP_URL_HOST );
if ( ! in_array( $gc_host, array( '127.0.0.1', 'localhost' ), true ) ) {
	WP_CLI::error( 'Este script solo se ejecuta en WordPress local.' );
}

if ( ! post_type_exists( 'gc_work' ) ) {
	WP_CLI::error( 'Activa primero el plugin GC Obras.' );
}

$gc_examples = array(
	array( 'jardin-interior', 'Jardín interior', 'Una composición de color y formas orgánicas para imaginar un lugar propio.' ),
	array( 'entre-lineas', 'Entre líneas', 'Trazos que se cruzan para dejar espacio a lo inesperado.' ),
	array( 'sol-de-tarde', 'Sol de tarde', 'Una exploración sencilla de luz, ritmo y pequeñas pausas.' ),
	array( 'la-forma-del-aire', 'La forma del aire', 'Capas y gestos suaves que guardan el movimiento de un instante.' ),
);

foreach ( $gc_examples as $gc_example ) {
	list( $gc_slug, $gc_title, $gc_description ) = $gc_example;
	if ( get_page_by_path( $gc_slug, OBJECT, 'gc_work' ) ) {
		WP_CLI::log( 'Ya existe: ' . $gc_title );
		continue;
	}
	$gc_id = wp_insert_post( array(
		'post_type'    => 'gc_work',
		'post_status'  => 'publish',
		'post_name'    => $gc_slug,
		'post_title'   => $gc_title,
		'post_excerpt' => $gc_description,
		'post_content' => '<p>' . esc_html( $gc_description ) . '</p><p>Esta ficha contiene datos de muestra. Sustitúyelos por la información de una obra real antes de publicar el sitio.</p>',
	), true );
	if ( is_wp_error( $gc_id ) ) {
		WP_CLI::error( $gc_id->get_error_message() );
	}
	update_post_meta( $gc_id, '_gc_demo', '1' );
	WP_CLI::log( 'Creada: ' . $gc_title );
}
