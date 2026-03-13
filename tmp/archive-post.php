  <article id="post-<?php the_ID(); ?>" <?php post_class( 'p-post__archive' ) ?> >
    <div class="p-post__archive--thumbnail">
      <a href="<?php the_permalink(); ?>" class="c-post__archive--thumbnail">
      <?php fn_thumbnail_rsp_thumbnail(); ?>
      </a>
    </div>
    <div class="p-post__archive--info">
      <h2 class="p-ttl__post--archives">
        <a href="<?php the_permalink(); ?>" class="c-ttl__post--archives c-ttl__ptn-dgnl">
<?php if( is_mobile()) : ?>
  <?php
    if (mb_strlen($post->post_title, 'UTF-8') > 20) {
      $title = mb_substr($post->post_title, 0, 20, 'UTF-8');
      echo $title . '…';
    } else {
      echo $post->post_title;
    }
  ?>
<?php else: ?>
  <?php
      echo $post->post_title;
  ?>
<?php endif; ?>
        </a>
      </h2>
      <div class="p-post__archive--meta">
<?php
$category = get_the_category();
$cat_slug  = $category[0]->category_nicename;
if ( $category[0] ) {
echo '<a href="' . get_category_link( $category[0]->term_id ) . '" class="p-post__archive--button-category ' . $cat_slug . '">' . $category[0]->cat_name . '</a>';
} ?>
        <p class="p-post__archive--date c-post__archive--date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      </div>

<p class="p-post__archive--excerpt">
<?php if ( is_mobile() ) : ?>
  <?php
      echo mb_substr(get_the_excerpt(), 0, 36)." ...";
  ?>
<?php else:?>
  <?php
      echo mb_substr(get_the_excerpt(), 0, 80)." ...";
  ?>
<?php endif; ?>
</p>

  <div class="l-row jc__start--sm mt__2">
      <div class="p-button__wrap--readmore">
<?php
$args = array(
  'link_slug' => get_permalink(),
  'className' => 'c-button__clr1--border',
  'text' => '続きを読む',
);
component_buttonLink_thin($args, true);
?>


      </div>
  </div>

    </div>
  </article>