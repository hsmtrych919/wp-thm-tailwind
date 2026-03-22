<div class="sidebar px-gutter-row md:px-0">
  <div class="ttl__widget">
    <p class="ttl__widget--caption">What's New</p>
    <h2 class="ttl__widget--bar"><span class="ttl__widget">おすすめ新着ブログ</span></h2>
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

  <article id="post-<?php the_ID(); ?>" <?php post_class( 'sidebar__post-archive' ) ?> >
    <a href="<?php the_permalink(); ?>" class="sidebar__post--item">

    <div class="sidebar__post--thumbnail">
      <?php fn_thumbnail_square_thumbnail(); ?>
    </div>

    <div class="sidebar__post--info">
<?php if( is_mobile()) : ?>
      <h3 class="ttl__post--widget">
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
      <p class="sidebar__post--date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      <p class="sidebar__post--excerpt">
<?php
  if ( empty(get_the_excerpt()) ) {
    echo'閲覧するにはログインが必要です。';
  }
?>
      </p>
<?php elseif( wp_is_mobile()) : ?>
      <h3 class="ttl__post--widget">
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
      <p class="sidebar__post--date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      <p class="sidebar__post--excerpt">
<?php
  if ( empty(get_the_excerpt()) ) {
    echo'閲覧するにはログインが必要です。';
  } else{
    echo mb_substr(get_the_excerpt(), 0, 30)."...";
  }
?>
      </p>
<?php else: ?>
      <h3 class="ttl__post--widget">
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
      <p class="sidebar__post--date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      <p class="sidebar__post--excerpt">
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