<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, maximum-scale=1.2, user-scalable=no">
<?php echo get_template_part('tmp/google-tag-manager/tmp', 'gtm-head'); ?>
<meta name="format-detection" content="telephone=no">
<?php wp_head(); ?>
<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" />
<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/favicon.ico">
<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/webclip.png" />
</head>

<?php
// page-ID
global $wp_query;
if ( is_archive() || is_404() || is_search()){
  $page_id = 'page-archive';
}elseif(is_single()){
  $cstmpost_type = get_post_type_object(get_post_type())->name;
  $page_id = 'single-'.$cstmpost_type;
}else{
  $post_obj = $wp_query->get_queried_object();
  $post_slug = $post_obj->post_name;
// $cat_slug = $post_obj->category_nicename;
// $tag_slug = $post_obj->slug;
  $page_id = 'page-'.$post_slug;
} ?>




<?php if ( is_page('form-contact-chk') ) : ?>
<body id="top">
  <?php echo get_template_part('tmp/google-tag-manager/tmp', 'gtm-body'); ?>
  <header class="l-header--static">
    <div class="p-header__row">
      <div class="p-header__logo-block p-header__logo-block--login">
        <h1 class="p-header__logo items-center"><img src='<?php echo get_template_directory_uri(); ?>/img/logo.svg' alt=""></h1>
      </div>
    </div>
  </header>
  <div id="<?php echo esc_html($page_id); ?>" class="l-main__nav-less">

<?php elseif ( is_page('lp') ) : ?>

<?php else : ?>
<body id="top">
  <?php echo get_template_part('tmp/google-tag-manager/tmp', 'gtm-body'); ?>

  <header class="l-header <?= is_page('form-contact') ? 'l-header--absolute' : '' ?> p-header">
    <div class="p-header__row" id="grobal__header">

    <?php if ( is_front_page() ) : ?>
      <h1 class="p-header__logo-block js-initial" id="front-logo">
    <?php else : ?>
      <h1 class="p-header__logo-block">
    <?php endif; ?>
        <a class="p-header__logo" href='<?php echo home_url('/'); ?>'><img src='<?php echo get_template_directory_uri(); ?>/img/logo.svg' alt=""></a>
      </h1>
      <?php get_template_part('tmp/tmp', 'nav-main'); ?>
    </div>
  </header>
  <div id="<?php echo esc_html($page_id); ?>" class="l-main">
<?php endif; ?>