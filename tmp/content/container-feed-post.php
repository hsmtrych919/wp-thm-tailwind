

  <div class="l-row--container c-gutter__row">
    <h2 class="p-ttl__horizontal"><span class="p-ttl__horizontal--body">店舗ブログ</span></h2>
  </div>
  <div class="p-latest-card__container">
    <div class="l-row--container c-gutter__row">
      <div class="c-col--12 c-col__lg--10">

    <?php get_template_part('tmp/content/feed', 'post-grid'); ?>

      </div>
    </div>
  </div>

  <div class="l-row--container c-gutter__row  mt__5">
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