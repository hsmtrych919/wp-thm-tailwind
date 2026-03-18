<?php
$queryNewBlog = new WP_Query( array(
    'post_type' => 'post',
    // 'category_name' => 'blog', //特定のカテゴリースラッグを指定
    'posts_per_page' => 5 //取得記事件数
));
?>
<?php if ( $queryNewBlog->have_posts() ) : ?>


<dl id="feed" class="p-post-feed__dl">
<?php while ( $queryNewBlog->have_posts() ) : $queryNewBlog->the_post(); ?>
<?php
//記事タイトルの文字数
$post_title = $post->post_title;
if (!is_mobile()) {
  if(mb_strlen($post_title)>22) {
    $post_title = mb_substr($post_title,0,22).'...';
  }
}
?>
  <div class="p-post-feed__dl--row">
  <dt><?php the_time('Y/m/d') ?></dt>
  <dd>
    <a href="<?php echo get_permalink(); ?>">
<?php
  $days=14; //Newをつける日数
  $today=date_i18n('U'); $entry=get_the_time('U');
  $diff1=date('U',($today - $entry))/86400;
  if ($days > $diff1){
    echo'<span class="new">New!</span>';
  }
?>
      <?php echo $post_title; ?>
    </a>
  </dd>
  </div>
  <?php endwhile; wp_reset_postdata(); ?>

</dl>
<?php endif; ?>