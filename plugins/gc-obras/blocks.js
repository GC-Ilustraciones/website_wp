(function (blocks, element) {
  var createElement = element.createElement;
  blocks.registerBlockType('gc-obras/gallery', {
    title: 'Galería de obras',
    icon: 'format-gallery',
    category: 'widgets',
    edit: function () {
      return createElement('p', { className: 'gc-editor-placeholder' }, 'La galería mostrará las obras publicadas.');
    },
    save: function () { return null; }
  });
  blocks.registerBlockType('gc-obras/visual', {
    title: 'Imagen de la obra',
    icon: 'format-image',
    category: 'widgets',
    edit: function () {
      return createElement('p', { className: 'gc-editor-placeholder' }, 'La imagen destacada aparecerá en la ficha de la obra.');
    },
    save: function () { return null; }
  });
})(window.wp.blocks, window.wp.element);
