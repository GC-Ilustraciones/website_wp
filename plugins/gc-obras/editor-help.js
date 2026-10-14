( function ( wp ) {
	var el = wp.element.createElement;

	function ImageHelpPanel() {
		return el(
			wp.editor.PluginDocumentSettingPanel,
			{ name: 'gc-obras-image-help', title: 'Imagen de la obra' },
			el( 'p', null, 'La imagen destacada aparece en la galería de la portada y en la ficha de esta obra. Se elige en «Imagen destacada» (Set featured image), sin insertarla en el texto.' ),
			el( 'p', null, 'Las obras de muestra sin imagen destacada muestran temporalmente una imagen ilustrativa del tema. Al asignar una imagen destacada, esta sustituye a la muestra.' )
		);
	}

	wp.plugins.registerPlugin( 'gc-obras-image-help', { render: ImageHelpPanel } );
} )( window.wp );
