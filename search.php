<?php get_header(); ?>

<article class="l-container-py--search">
  <div class="p-ttl__search px-gutter-row xl:px-0">
    <p class="p-ttl__search--caption">検索キーワード</p>
    <h1 class="p-ttl__search"><span><?php the_search_query(); ?></span></h1>
  </div>
  <div class="l-container-py">
<?php if ( have_posts() ) : ?>
    <?php get_template_part('tmp/tmp', 'post'); ?>
<?php else : ?>
  <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
    <?php get_template_part( 'tmp/archive', 'none' ); ?>
  </div>
<?php endif; ?>
  </div>

</article>

<?php if ( !have_posts() ) : ?>
<?php get_template_part('tmp/tmp', 'post-feed_article'); ?>

<?php endif; ?>


<?php get_footer(); ?>