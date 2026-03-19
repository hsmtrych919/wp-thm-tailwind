<?php get_header(); ?>

<article class="">
<div class="c-headline__outer">
    <div class="container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="c-headline__content" >
        <h1 class=""><span class="c-headline__title p-ttl__large">ブログ</span></h1>
        <p class="c-headline__lead">blog</p>
      </div>
    </div>
  </div>

  <div class="l-container">
    <?php get_template_part('tmp/tmp', 'post'); ?>
  </div>
</article>

<?php get_footer(); ?>