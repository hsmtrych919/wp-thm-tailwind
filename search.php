<?php get_header(); ?>

<article class="l-container__search">
  <div class="p-ttl__search c-gutter__row">
    <p class="c-ttl__search--caption">検索キーワード</p>
    <h1 class="c-ttl__search"><span><?php the_search_query(); ?></span></h1>
  </div>
  <div class="l-container">
<?php if ( have_posts() ) : ?>
    <?php get_template_part('tmp/tmp', 'post'); ?>
<?php else : ?>
  <div class="l-row--container c-gutter__row">
    <?php get_template_part( 'tmp/archive', 'none' ); ?>
  </div>
<?php endif; ?>
  </div>

</article>

<?php if ( !have_posts() ) : ?>
<?php get_template_part('tmp/tmp', 'post-feed_article'); ?>

<?php endif; ?>


<?php get_footer(); ?>