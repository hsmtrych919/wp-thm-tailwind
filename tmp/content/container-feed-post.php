

  <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
    <h2 class="ttl__horizontal"><span class="ttl__horizontal--body">店舗ブログ</span></h2>
  </div>
  <div class="latest-card__container">
    <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="w-full lg:w-10/12">

    <?php get_template_part('tmp/content/feed', 'post-grid'); ?>

      </div>
    </div>
  </div>

  <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0  mt-5">
        <div class="button__wrap">
  <?php
  $args = array(
      'link_slug' => 'blog',
      'className' => 'button__clr1',
      'text' => 'もっと読む',
  );
  component_buttonLink($args)
  ?>
            </div>
        </div>