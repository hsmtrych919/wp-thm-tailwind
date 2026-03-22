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

  <div class="headline__outer">
    <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="headline__content" >
        <h1 class=""><span class="headline__title ttl__large"><?php echo $title; ?></span></h1>
        <p class="headline__lead">archives</p>
      </div>
    </div>
  </div>


  <div class="container-py">
    <?php get_template_part('tmp/tmp', 'post'); ?>
  </div>
</article>


<?php get_footer(); ?>