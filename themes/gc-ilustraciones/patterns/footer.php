<?php
/**
 * Title: Pie GC Ilustraciones
 * Slug: gc-ilustraciones/footer
 * Inserter: no
 */
$gc_privacy_link = get_the_privacy_policy_link();
?>
<!-- wp:html -->
<footer class="gc-site-footer"><div class="gc-shell gc-site-footer__inner">© <?php echo esc_html( gmdate( 'Y' ) ); ?> GC Ilustraciones · Valencia, España<?php if ( $gc_privacy_link ) : ?> <span aria-hidden="true">·</span> <?php echo wp_kses_post( $gc_privacy_link ); ?><?php endif; ?></div></footer>
<!-- /wp:html -->
