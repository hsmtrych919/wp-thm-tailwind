<div class="p-sidebar px-gutter-row md:px-0">
  <div class="p-ttl__widget">
    <p class="c-ttl__widget--caption">What's New</p>
    <h2 class="c-ttl__widget--bar"><span class="c-ttl__widget">おすすめ新着ブログ</span></h2>
  </div>

<!-- post -->
  <?php
     $query = new WP_Query(array(
         'post_type' => 'post', //投稿 post・カスタム投稿タイプ custom-post-type
         'posts_per_page' => 5, // 表示件数
         'suppress_filters' => true //Groupsプラグイン 会員の非表示フィルターをオフ is_single()で必要
    )); ?>

  <?php if ( $query->have_posts() ) : ?>
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>

  <article id="post-<?php the_ID(); ?>" <?php post_class( 'p-sidebar__post-archive' ) ?> >
    <a href="<?php the_permalink(); ?>" class="c-post__widget--item-latest p-sidebar__post--item">

    <div class="p-sidebar__post--thumbnail c-post__widget--thumbnail">
      <?php fn_thumbnail_square_thumbnail(); ?>
    </div>

    <div class="c-post__widget--info">
<?php if( is_mobile()) : ?>
      <h3 class="p-ttl__post--widget c-ttl__post--widget">
  <?php
    if (mb_strlen($post->post_title, 'UTF-8') > 18) {
      $title = mb_substr($post->post_title, 0, 18, 'UTF-8');
      if ( empty(get_the_excerpt()) ) {
        echo'【会員限定】'.$title.'…';
      } else{
        echo $title . '…';
      }
    } else {
      if ( empty(get_the_excerpt()) ) {
        echo'【会員限定】'.$post->post_title;
      } else{
        echo $post->post_title;
      }
    }
  ?>
      </h3>
      <p class="p-sidebar__post--date c-post__archive--date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      <p class="p-sidebar__post--excerpt">
<?php
  if ( empty(get_the_excerpt()) ) {
    echo'閲覧するにはログインが必要です。';
  }
?>
      </p>
<?php elseif( wp_is_mobile()) : ?>
      <h3 class="p-ttl__post--widget c-ttl__post--widget">
  <?php
    if (mb_strlen($post->post_title, 'UTF-8') > 18) {
      $title = mb_substr($post->post_title, 0, 18, 'UTF-8');
      if ( empty(get_the_excerpt()) ) {
        echo'【会員限定】'.$title.'…';
      } else{
        echo $title . '…';
      }
    } else {
      if ( empty(get_the_excerpt()) ) {
        echo'【会員限定】'.$post->post_title;
      } else{
        echo $post->post_title;
      }
    }
  ?>
      </h3>
      <p class="p-sidebar__post--date c-post__archive--date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      <p class="p-sidebar__post--excerpt">
<?php
  if ( empty(get_the_excerpt()) ) {
    echo'閲覧するにはログインが必要です。';
  } else{
    echo mb_substr(get_the_excerpt(), 0, 30)."...";
  }
?>
      </p>
<?php else: ?>
      <h3 class="p-ttl__post--widget c-ttl__post--widget">
  <?php
    if (mb_strlen($post->post_title, 'UTF-8') > 20) {
      $title = mb_substr($post->post_title, 0, 20, 'UTF-8');
      if ( empty(get_the_excerpt()) ) {
        echo'【会員限定】'.$title.'…';
      } else{
        echo $title . '…';
      }
    } else {
      if ( empty(get_the_excerpt()) ) {
        echo'【会員限定】'.$post->post_title;
      } else{
        echo $post->post_title;
      }
    }
  ?>
      </h3>
      <p class="p-sidebar__post--date c-post__archive--date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      <p class="p-sidebar__post--excerpt">
<?php
  if ( empty(get_the_excerpt()) ) {
    echo'閲覧するにはログインが必要です。';
  } else{
    echo mb_substr(get_the_excerpt(), 0, 24)."...";
  }
?>
      </p>
<?php endif; ?>

    </div>
    </a>
  </article>

    <?php endwhile; ?>
  <?php endif; wp_reset_postdata(); ?>
<!-- /post -->

</div>