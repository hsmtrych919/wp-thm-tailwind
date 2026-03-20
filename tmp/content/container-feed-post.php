

  <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
    <h2 class="p-ttl__horizontal"><span class="p-ttl__horizontal--body">店舗ブログ</span></h2>
  </div>
  <div class="p-latest-card__container">
    <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="w-full lg:w-10/12">

    <?php get_template_part('tmp/content/feed', 'post-grid'); ?>

      </div>
    </div>
  </div>

  <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0  mt-5">
        <div class="p-button__wrap">
  <?php
  $args = array(
      'link_slug' => 'blog',
      'className' => 'c-button__clr1',
      'text' => 'もっと読む',
  );
  component_buttonLink($args)
  ?>
            </div>
        </div>