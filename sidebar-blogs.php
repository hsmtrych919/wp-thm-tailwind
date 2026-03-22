<div class="sidebar px-gutter-row md:px-0">
  <div class="ttl__widget">
    <p class="ttl__widget--caption">Category</p>
    <h2 class="ttl__widget--bar"><span class="ttl__widget">ブログカテゴリー</span></h2>
  </div>
  <ul class="archive__list">




    <?php
      $args = array (
          'title_li' => '',
          'show_count' => true,
        );
      wp_list_categories( $args );
     ?>

  </ul>
</div>
<div class="sidebar px-gutter-row md:px-0">
  <div class="ttl__widget">
    <p class="ttl__widget--caption">Monthly Archives</p>
    <h2 class="ttl__widget--bar"><span class="ttl__widget">月別記事</span></h2>
  </div>

    <?php if( !wp_is_mobile() ) : ?>
      <ul class="archive__list">
        <?php
          $args = array (
              'type' => 'monthly',
              'show_count' => true,
              'limit' => 6,
            );
          wp_get_archives( $args );
         ?>
      </ul>
    <?php endif; ?>

  <!-- wp_get_archives -->
  <ul class="mt-1.25 md:mt-2">
<select name="archive-dropdown" style="width: 100%;" onChange='document.location.href=this.options[this.selectedIndex].value;'>
<option value=""><?php echo esc_attr(__('月を選択', 'text-domain')); ?></option>
<?php wp_get_archives('type=monthly&format=option&show_post_count=1'); ?>
</select>
  </ul>

</div>