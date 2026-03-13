<?php get_header(); ?>

<?php
$title = (is_404()) ? '見つかりません' : 'キーワード未入力' ;

?>


<article class="">
<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row">
      <div class="c-headline__content" >
        <h1 class=""><span class="c-headline__title c-ttl__large"><?php echo $title ?></span></h1>
        <p class="c-headline__lead">404 not fount</p>
      </div>
    </div>
  </div>


  </div>

  <div class="l-container">
  <div class="l-row--container c-gutter__row">
    <div class="c-col--12 c-col__sm--8 c-col__lg--7 c-col__xl--6">
      <p class="c-typ tac__lg">
  <?php if ( is_404() ) : ?>
    お探しのページは見つかりませんでした。<br>検索フォームをお試しいただくか、トップページをご覧ください。</p>
  <?php else  : ?>
      キーワードが入力されていませんでした。<br>検索キーワードを入力してお試しください。
      <?php endif; ?>
    </p>

        <div class="mt__4">
          <?php  get_search_form(); ?>
        </div>

    </div>
  </div>


  <div class="l-row--container c-gutter__row mt__8 ">
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


<article class="mt__7 mt__8--lg">
<?php get_template_part('tmp/content/container', 'feed-post'); ?>
</article>

<?php get_footer(); ?>