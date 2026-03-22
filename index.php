<?php get_header(); ?>

<article class="">
<div class="headline__outer">
    <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="headline__content" >
        <h1 class=""><span class="headline__title ttl__large">ブログ</span></h1>
        <p class="headline__lead">blog</p>
      </div>
    </div>
  </div>

  <div class="container-py">
    <?php get_template_part('tmp/tmp', 'post'); ?>
  </div>
</article>

<?php get_footer(); ?>