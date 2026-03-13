  <div class="l-row--container c-gutter__post">

    <div class="c-col--12 c-col__md--8 l-blog__main">
<!-- post -->
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part('tmp/archive', 'post'); ?>
    <?php endwhile; ?>
    <?php else : ?>
      <?php get_template_part( 'tmp/archive', 'none' ); ?>
  <?php endif; ?>
<!-- /post -->

    <div class="l-row--container c-gutter__row mt__5">
      <?php fn_pagination($wp_query); ?>
    </div>
    </div>
    <div class="c-col--12 c-col__md--4 l-blog__sidebar">
<?php if( !is_search() ) : ?>
      <?php get_sidebar( $name = 'search' ) ?>
<?php endif; ?>
      <?php get_sidebar( $name = 'latest' ) ?>
      <?php get_sidebar( $name = 'blogs' ) ?>
    </div>

  </div>