  <div class="container-width mx-auto flex flex-wrap justify-center md:px-gutter-row xl:px-0">

    <div class="w-full md:w-8/12 blog__main">
<!-- post -->
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part('tmp/archive', 'post'); ?>
    <?php endwhile; ?>
    <?php else : ?>
      <?php get_template_part( 'tmp/archive', 'none' ); ?>
  <?php endif; ?>
<!-- /post -->

    <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 mt-5">
      <?php fn_pagination($wp_query); ?>
    </div>
    </div>
    <div class="w-full md:w-4/12 blog__sidebar">
<?php if( !is_search() ) : ?>
      <?php get_sidebar( $name = 'search' ) ?>
<?php endif; ?>
      <?php get_sidebar( $name = 'latest' ) ?>
      <?php get_sidebar( $name = 'blogs' ) ?>
    </div>

  </div>