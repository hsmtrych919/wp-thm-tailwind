<div class="l-container-py--blog">
  <div class="l-container mx-auto flex flex-wrap justify-center md:px-gutter-row xl:px-0">

    <div class="w-full md:w-8/12 l-blog__main">
<?php
  if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>
  <article id="post-<?php the_ID(); ?>" <?php post_class( 'p-post-single px-gutter-row md:px-0' ) ?> >
    <div class="p-ttl__post--single">
      <h1 class="p-ttl__post--single p-ttl__ptn-dgnl"><?php the_title(); ?></h1>
    </div>
    <div class="p-post-single__info">
      <p class="p-post-single__date"><time datetime="<?php the_time('y-m-d'); ?>"><?php the_time('Y/m/d') ?></time></p>
      <div class="p-post-single__category--title">カテゴリー :</div>
      <p class="inline-block">
<?php
  $category = get_the_category();
  if ( $category[0] ) {
    echo '<a href="' . get_category_link( $category[0]->term_id ) . '" class="p-post-single__category--button">' . $category[0]->cat_name . '</a>';
    }
  ?>
      </p>
    </div>
    <div class="p-post-single__content"><?php the_content(); ?></div>

  </article>
<?php
  endwhile;
  endif;
?>

<?php //get_template_part( 'tmp/tmp', 'single-author' ); ?>



  <div class="p-related px-gutter-row md:px-0 mt-4 md:mt-5">
  <div class="p-ttl__widget">
    <p class="p-ttl__widget--caption">Related Posts</p>
    <h2 class="p-ttl__widget--bar"><span class="p-ttl__widget">おすすめ関連記事</span></h2>
  </div>
<?php
//カテゴリ情報から半年分の関連記事を5個ランダムに呼び出す
$categories = get_the_category($post->ID);
$category_ID = array();
foreach($categories as $category):
  array_push( $category_ID, $category -> cat_ID);
endforeach ;
$past_month = date('Y-m-d 0:0:0', strtotime('-6 month'));
$args = array(
  'post__not_in' => array($post -> ID),
  'posts_per_page'=> 4,
  'category__in' => $category_ID,
  'orderby' => 'rand',
  'date_query' => array(
        array(
          // 'after' => $past_month,
          'inclusive' => true
        ),
  )
);
$query = new WP_Query($args); ?>

  <?php if( $query -> have_posts() ): ?>
  <?php while ($query -> have_posts()) : $query -> the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'p-related__post-archive' ) ?> >
        <a href="<?php the_permalink(); ?>" class="p-related__post--item">
        <div class="p-related__post--thumbnail">
          <?php fn_thumbnail_square_thumbnail(); ?>
        </div>
        <div class="p-related__post--info">
          <h3 class="p-ttl__post--widget-related">
<?php if( is_mobile()) : ?>
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
<?php else: ?>
  <?php
      if ( empty(get_the_excerpt()) ) {
        echo'【会員限定】'.$post->post_title;
      } else{
        echo $post->post_title;
      }
  ?>
<?php endif; ?>
          </h3>

          <p class="p-related__post--excerpt">
<?php if ( is_mobile() ) : ?>
  <?php
      if ( empty(get_the_excerpt()) ) {
        echo'こちらは会員限定の投稿です。閲覧するためにはログインが必要です。';
      } else{
        echo mb_substr(get_the_excerpt(), 0, 24)." ...";
      }
  ?>
<?php else:?>
  <?php
      if ( empty(get_the_excerpt()) ) {
        echo'こちらは会員限定の投稿です。閲覧するためにはログインが必要です。';
      } else{
        echo mb_substr(get_the_excerpt(), 0, 72)." ...";
      }
  ?>
<?php endif; ?>
          </p>

        </div>
        </a>
      </article>
  <?php endwhile;?>
    <?php else:?>
    <p class="mt-2">関連記事はありませんでした</p>
  <?php endif; wp_reset_postdata(); ?>
  </div>
  <!-- /.relatedWidget -->

    </div>
    <!-- /.l-main-blog -->

    <div class="w-full md:w-4/12 l-blog__sidebar">
      <?php get_sidebar( $name = 'search' ) ?>
      <?php get_sidebar( $name = 'latest' ) ?>
      <?php get_sidebar( $name = 'blogs' ) ?>
    </div>

  </div>
</div>