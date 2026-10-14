<?php
/**
 * Title: Cabecera GC Ilustraciones
 * Slug: gc-ilustraciones/header
 * Inserter: no
 */
$gc_home = esc_url( home_url( '/' ) );
$gc_logo = esc_url( get_stylesheet_directory_uri() . '/assets/logo.png' );
?>
<!-- wp:html -->
<header class="gc-site-header">
  <div class="gc-shell gc-site-header__inner">
    <a class="gc-brand" href="<?php echo $gc_home; ?>" aria-label="GC Ilustraciones, inicio"><img src="<?php echo $gc_logo; ?>" width="92" height="92" alt="GC Ilustraciones"></a>
    <nav class="gc-navigation" aria-label="Navegación principal">
      <a href="<?php echo $gc_home; ?>#inicio">Inicio</a>
      <a href="<?php echo $gc_home; ?>#obras">Obras</a>
      <a href="<?php echo $gc_home; ?>#contacto">Contacto</a>
    </nav>
  </div>
</header>
<!-- /wp:html -->
