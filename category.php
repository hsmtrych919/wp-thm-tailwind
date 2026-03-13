<?php get_header(); ?>

<?php
$category = get_the_category();
$cat = $category[0];
$queried_object = get_queried_object();
$cat_name = $queried_object -> name;
$cat_slug = $queried_object -> slug;
// $term_parent = $queried_object->parent;
// ターム（カテゴリ）の親子判別
// if ( $term_parent ) {
//   $cat_parent = get_category($cat->parent);
  // echo '子ターム';
  // echo $cat_parent->cat_name;
// }
?>

<article class="">

<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row">
      <div class="c-headline__content" >
        <h1 class=""><span class="c-headline__title c-ttl__large"><?php echo $cat_name; ?></span></h1>
        <p class="c-headline__lead"><?php echo $cat_slug ?></p>
      </div>
    </div>
  </div>

  <div class="l-container">
    <?php get_template_part('tmp/tmp', 'post'); ?>
  </div>
</article>


<?php get_footer(); ?>