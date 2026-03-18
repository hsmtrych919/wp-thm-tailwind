

    <ul class="grid gap-x-grid-gutter grid-cols-1 sm:grid-cols-2">

<!-- post -->
<?php
$query = new WP_Query(array(
  'post_type' => 'post', //投稿 post・カスタム投稿タイプ custom-post-type
  'posts_per_page' => 6, // 表示件数
)); ?>

<?php if ($query->have_posts()) : ?>
<?php while ($query->have_posts()) : $query->the_post(); ?>

    <li id="post-<?php the_ID(); ?>" <?php post_class('p-latest-card__archive') ?>>
      <a href="<?php the_permalink(); ?>" class="p-latest-card__item">
        <div class="p-latest-card__thumbnail"><?php fn_thumbnail_square_thumbnail(); ?></div>
        <div class="p-latest-card__info">
<?php if (is_mobile()) : ?>
          <h3 class="p-ttl__post--widget-grid">
  <?php
    if (mb_strlen($post->post_title, 'UTF-8') > 18) {
      $title = mb_substr($post->post_title, 0, 18, 'UTF-8');
      echo $title . '…';
    } else {
      echo $post->post_title;
    }
  ?>
          </h3>
          <p class="p-latest-card__date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
          <p class="p-latest-card__excerpt"><?php echo mb_substr(get_the_excerpt(), 0, 28)." ..."; ?></p>

<?php else : ?>
          <h3 class="p-ttl__post--widget-grid">
  <?php
    if (mb_strlen($post->post_title, 'UTF-8') > 16) {
      $title = mb_substr($post->post_title, 0, 16, 'UTF-8');
      echo $title . '…';
    } else {
      echo $post->post_title;
    }
  ?>
          </h3>
          <p class="p-latest-card__date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
          <p class="p-latest-card__excerpt"><?php echo mb_substr(get_the_excerpt(), 0, 48)." ...";  ?></p>
<?php endif; ?>

        </div>
      </a>
    </li>
<?php endwhile; ?>
<?php endif; wp_reset_postdata(); ?>
    <!-- /post -->
    </ul>
