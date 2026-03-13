<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php
if ( is_page( 'contact' ) ) {
  get_template_part('tmp/page', 'contact');
} elseif ( is_page( 'contact-thk' ) ) {
  get_template_part('tmp/page', 'contact-thk');

} elseif ( is_page( 'salon' ) ) {
  get_template_part('tmp/page', 'salon');
} elseif ( is_page( array('kanosato', 'kanayama', 'nakamura', 'minami-ryugu', 'tajimi', 'konan', 'suzuka', 'nakashidami', 'ryusenji-kita') ) ) {
  get_template_part('tmp/page', 'salon-single');

} elseif ( is_page( 'menu' ) ) {
  get_template_part('tmp/page', 'menu');
} elseif ( is_page( 'promise' ) ) {
  get_template_part('tmp/page', 'promise');
} elseif ( is_page( 'ec-list' ) ) {
  get_template_part('tmp/page', 'ec-list');

} elseif ( is_page( 'recruit' ) ) {
  get_template_part('tmp/page', 'recruit');
} elseif ( is_page( 'recruit/info-guiches' ) ) {
  get_template_part('tmp/page', 'recruit-info');
} elseif ( is_page( 'recruit/info-soho' ) ) {
  get_template_part('tmp/page', 'recruit-info');


} elseif ( is_page( 'privacy-policy' ) ) {
  get_template_part('tmp/page', 'privacy-policy');
} elseif ( is_page( 'sitemap' ) ) {
  get_template_part('tmp/page', 'sitemap');

} else {
  get_template_part('tmp/page', 'slagName');
}
?>

<?php endwhile; endif; ?>


<?php
// if ( !is_page('recruit') && !is_page( 'recruit/info-guiches') && !is_page( 'recruit/info-soho' ) && !is_page('form-contact') && !is_page('form-contact-chk')  ) {
// get_template_part('tmp/content/article', 'more');
// }
?>


<?php get_footer(); ?>