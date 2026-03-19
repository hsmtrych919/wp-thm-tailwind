<?php get_header(); ?>

<?php
$title = (is_404()) ? '見つかりません' : 'キーワード未入力' ;

?>


<article class="">
<div class="c-headline__outer">
    <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="c-headline__content" >
        <h1 class=""><span class="c-headline__title p-ttl__large"><?php echo $title ?></span></h1>
        <p class="c-headline__lead">404 not found</p>
      </div>
    </div>
  </div>


  </div>

  <div class="l-container-py">
  <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
    <div class="w-full sm:w-8/12 lg:w-7/12 xl:w-6/12">
      <p class="p-typ lg:text-center">
  <?php if ( is_404() ) : ?>
    お探しのページは見つかりませんでした。<br>検索フォームをお試しいただくか、トップページをご覧ください。</p>
  <?php else  : ?>
      キーワードが入力されていませんでした。<br>検索キーワードを入力してお試しください。
      <?php endif; ?>
    </p>

        <div class="mt-4">
          <?php  get_search_form(); ?>
        </div>

    </div>
  </div>


  <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 mt-8 ">
    <div class="p-button__wrap">
<?php
  $args = array(
    'link_slug' => '',
    'className' => 'c-button__clr1',
    'text' => 'トップページへ',
);
component_buttonLink($args);
?>
    </div>
</div>

  </div>
</article>


<article class="mt-7 lg:mt-8">
<?php get_template_part('tmp/content/container', 'feed-post'); ?>
</article>

<?php get_footer(); ?>