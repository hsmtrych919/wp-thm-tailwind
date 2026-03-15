<?php get_header(); ?>

<?php
    if ( is_year() ) {
      $title = get_the_time('Y年');
    } elseif ( is_month() ){
      $title = get_the_time('Y年m月');
    } else {
      $title = get_the_title();
    }
?>

<article class="">

  <div class="c-headline__outer">
    <div class="container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="c-headline__content" >
        <h1 class=""><span class="c-headline__title c-ttl__large"><?php echo $title; ?></span></h1>
        <p class="c-headline__lead">archives</p>
      </div>
    </div>
  </div>


  <div class="l-container">
    <?php get_template_part('tmp/tmp', 'post'); ?>
  </div>
</article>


<?php get_footer(); ?>