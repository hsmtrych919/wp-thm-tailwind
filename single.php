<?php get_header(); ?>

<?php
if ( is_singular( 'custumpost_staff' ) ) {
  get_template_part('tmp/single', 'staff');
} elseif ( is_singular( 'custumpost_style' ) ) {
  get_template_part('tmp/single', 'style');
} else {
  get_template_part('tmp/single', 'blog');
}
?>


<?php get_footer(); ?>