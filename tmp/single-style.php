
<?php
  $style_img01 = get_field('style_img01');
  $style_img02 = get_field('style_img02');
  $style_img03 = get_field('style_img03');
  $thumbnail01 = wp_get_attachment_image( $style_img01, 'full' );
  if (!empty($style_img02)) {
    $thumbnail02 = wp_get_attachment_image( $style_img02, 'full' );
  }
  if (!empty($style_img03)) {
    $thumbnail03 = wp_get_attachment_image( $style_img03, 'full' );
  }
?>


<article class="" id="post-<?php the_ID(); ?>">
<div class="c-headline__outer">
    <div class="container mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0">
      <div class="c-headline__frame" >
        <h1 class="c-headline__title"><span class="">STYLE</span></h1>
        <div class="c-headline__detail">
    <p class="">
    <span class="c-ttl__xsmall text-clr3"><span class="text-clr1">#</span> <?php the_title(); ?></span></p>
        </div>

      </div>
    </div>
  </div>

  <div class="p-style__content">
    <div class="container mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0">
      <div class="p-style__frame">

      <div class="p-style__pic">
<?php if ( $style_img02 || $style_img03 ) : ?>
  <!-- swiper -->
    <div class="swiper swiper-style__container">
      <!-- slide -->
    <div class="swiper-wrapper">
      <div class="swiper-slide"><?php echo $thumbnail01 ?></div>
      <?php if( $style_img02 ){echo '<div class="swiper-slide">'.$thumbnail02.'</div>';} ?>
      <?php if( $style_img03 ){echo '<div class="swiper-slide">'.$thumbnail03.'</div>';} ?>
    </div>
    <!-- option -->
    <div class="swiper-button-prev swiper-style__button-prev"></div>
    <div class="swiper-button-next swiper-style__button-next"></div>
    <div class="swiper-pagination swiper-style__pagination"></div>
  </div>
<?php else : ?>
    <div class="p-style-pic__box">
      <p class="p-style-pic__single"><?php echo $thumbnail01 ?></p>
    </div>
<?php endif; ?>
      </div>



      <div class="p-style__detail">

    <p class="p-style__category--title">カテゴリー：</p>

<?php
  $terms = get_the_terms($post->ID, 'style_category');
  if($terms):
    echo '<ul class="p-style__category--list">';
    foreach($terms as $term):
      $term_name = $term->name;
      $term_link = get_term_link( $term );
      echo '<li><a href="'.$term_link.'" class="p-style__category--button">';
      echo $term_name;
      echo '<svg class="p-style__category--button-icon" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path></svg>';
      echo '</a></li>';
    endforeach;
    echo '</ul>';
  endif;
?>
        <p class="p-style__comment"><?php the_field( 'style_comment' ); ?></p>

      </div>

      </div>
      </div>
    </div>




</article>


<?php get_template_part('tmp/content/article', 'more'); ?>

