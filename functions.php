<?php
$imgPath = get_template_directory_uri() . '/img/';
include get_template_directory() . '/functions/salon_info.php';
include get_template_directory() . '/functions/component/item-menu-introduction.php';
include get_template_directory() . '/functions/component/item-menu.php';
include get_template_directory() . '/functions/component/item-likepost.php';
include get_template_directory() . '/functions/component/item-entrystep.php';
include get_template_directory() . '/functions/component/button-link.php';
include get_template_directory() . '/functions/component/button-link-thin.php';
include get_template_directory() . '/functions/component/button-link-external.php';

//開発中 更新通知を非表示
function update_nag_hide()
{
  remove_action('admin_notices', 'update_nag', 3);
  remove_action('admin_notices', 'maintenance_nag', 10);
}
add_action('admin_init', 'update_nag_hide');
//スマートフォンを判別
// 検討保留 Mobile_Detect.php 使用 https://github.com/serbanghita/mobile-detect
function is_mobile()
{
  $useragents = [
    'iPhone', // iPhone
    'Android.*Mobile', // 1.5+ Android *** Only mobile
    'Windows.*Phone', // *** Windows Phone
  ];
  $pattern = '/' . implode('|', $useragents) . '/i';
  return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

//css・js インポート
add_action('wp_enqueue_scripts', 'incFiles');
function incFiles()
{
  //footer script
  if (!is_admin()) {
    // Swiper
    if (is_front_page() || is_page(array( 'suzuka', 'nakashidami', 'ryusenji-kita')) || is_singular( 'arrivals' ) ) {
      wp_enqueue_script('swiper-cdn', 'https://cdn.jsdelivr.net/npm/swiper@7/swiper-bundle.min.js', array(), null, true);
    }
    // gsap
    wp_enqueue_script('gsap-cdn', 'https://cdn.jsdelivr.net/npm/gsap@3.11.4/dist/gsap.min.js', array(), null, true);
    wp_enqueue_script('scrolltrigger-cdn', 'https://cdn.jsdelivr.net/npm/gsap@3.11.4/dist/ScrollTrigger.min.js', array('gsap-cdn'), null, true);
    // yubinbango
    if (is_page('form-contact')) {
      $yubinbangoLibrary =
        'https://yubinbango.github.io/yubinbango/yubinbango.js';
      $yubinbangoLocal = 'wp-content/themes/wp-thm_/js/yubinbango.js';
      $yubinbangoGet = @fopen($yubinbangoLibrary, 'r');
      if ($yubinbangoGet === false) {
        $yubinbangoLibrary = home_url('/') . $yubinbangoLocal;
      }
      wp_enqueue_script('yubinbango', $yubinbangoLibrary, [], '', true);
      wp_enqueue_script('yubinbango');
    }
    if (is_page('form-contact')) {
      wp_register_script(
        'bundle_form',
        get_template_directory_uri() . '/js/bundle_form.js',
        [],
        '',
        true
      );
      wp_enqueue_script('bundle_form');
    }
    // google recaptcha
    if (is_page('form-contact-chk') ) {
      wp_register_script(
        'recaptcha',
        'https://www.google.com/recaptcha/api.js?render=サイトキー',
        [],
        '',
        false
      );
      wp_enqueue_script('recaptcha');
    }

    // メインスクリプト
    wp_register_script(
      'bundle',
      get_template_directory_uri() . '/js/bundle.js',
      [],
      '',
      true
    );
    wp_enqueue_script('bundle');

    // if ( is_page('small-height') ) {
    //   wp_register_script( 'min-height', get_template_directory_uri() . '/js/app-min-height.js', array(), '', false );
    //   wp_enqueue_script( 'min-height' );
    // }
  }
  //CSSの読み込み
  wp_enqueue_style(
    'notosansjp',
    'https://fonts.googleapis.com/earlyaccess/notosansjp.css',
    [],
    null,
    'all'
  );
  wp_enqueue_style( 'lato', 'https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700&display=swap', array(), null, 'all');
  // wp_enqueue_style( 'notoserif', 'https://fonts.googleapis.com/css?family=Noto+Serif+JP:300,400&display=swap&subset=japanese', array(), null, 'all');
  wp_enqueue_style(
    'mainStyle',
    get_template_directory_uri() . '/css/style.css',
    [],
    null,
    'all'
  );
}

// 202104 Gutenberg用CSS不要 WordPress5
add_action('wp_enqueue_scripts', 'dequeue_plugins_style', 9999);
function dequeue_plugins_style()
{
  wp_dequeue_style('wp-block-library');
}

// フォーム用session_start()
add_action('template_redirect', 'init_session_start');
function init_session_start()
{
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_cache_limiter('none'); //history.back()対策 history.back()は要改善
    session_start();
    unset($_SESSION['backpage']);
    $_SESSION['backpage'] = $_SERVER['REQUEST_URI'];
  }
}

// titleタグ
add_action('after_setup_theme', 'setup_theme');
function setup_theme()
{
  add_theme_support('title-tag');
}
// titleタグ キャッチフレーズの非表示
add_filter('document_title_parts', 'remove_tagline');
function remove_tagline($title)
{
  if (is_front_page()) {
    unset($title['tagline']);
  }
  return $title;
}
// titleタグ セパレータ
add_filter('document_title_separator', 'custom_title_separator');
function custom_title_separator($sep)
{
  $sep = '|';
  return $sep;
}
//the_excerpt(); の末尾 [...] 文字列の変更・削除
add_filter('excerpt_more', 'custom_excerpt_more');
function custom_excerpt_more($more)
{
  return '';
}

//アップロード画像のリサイズ 20200308
// 202103 EWWWプラグインで設定
// add_action( 'wp_handle_upload', 'otocon_resize_at_upload' );
function otocon_resize_at_upload($file)
{
  if (
    $file['type'] == 'image/jpeg' or
    $file['type'] == 'image/gif' or
    $file['type'] == 'image/png'
  ) {
    $w = 1000;
    $h = 0;
    $image = wp_get_image_editor($file['file']);
    if (!is_wp_error($image)) {
      $size = getimagesize($file['file']);
      if ($size[0] > $w || $size[1] > $h) {
        $image->resize($w, $h, false);
        $final_image = $image->save($file['file']);
      }
    }
  }
  return $file;
}

//アイキャッチ画像
add_action('after_setup_theme', 'add_thumbnail_size');
function add_thumbnail_size()
{
  add_theme_support('post-thumbnails');
  add_image_size('orig_thumbnail', 560, 360, true);
  add_image_size('square_thumbnail', 360, 360, true);
}

//NoImage
function fn_thumbnail_NoImage()
{
  echo '<img src="' .
    get_template_directory_uri() .
    '/img/noimage.png"  width="560" height="360" alt="noimage">';
}
function fn_thumbnail_NoImage_sq()
{
  echo '<img src="' .
    get_template_directory_uri() .
    '/img/noimage_sq.png" class="attachment-square_thumbnail" width="360" height="360" alt="noimage">';
}

//アイキャッチ出力 orig_thumbnailのみ
function fn_thumbnail_orig_thumbnail()
{
  if (has_post_thumbnail()) {
    the_post_thumbnail('orig_thumbnail');
  } else {
    fn_thumbnail_NoImage();
  }
}

//アイキャッチ出力 square_thumbnailのみ
function fn_thumbnail_square_thumbnail()
{
  if (has_post_thumbnail()) {
    the_post_thumbnail('square_thumbnail');
  } else {
    fn_thumbnail_NoImage_sq();
  }
}

//アイキャッチ出力 orig_thumbnail & square_thumbnail
function fn_thumbnail_rsp_thumbnail()
{
  if (is_mobile()) {
    fn_thumbnail_orig_thumbnail();
  } else {
    fn_thumbnail_square_thumbnail();
  }
}

//ページネーション
function post_pagination($use_query = null, $range = 2)
{
    $showitems = $range * 2 + 1;

    global $paged;
    if(empty($paged)) $paged = 1;

    if($use_query === null) {
        global $wp_query;
        $use_query = $wp_query;
    }

    $pages = $use_query->max_num_pages;

    if (!$pages) {
        $pages = 1;
    }
  if (1 != $pages) {
    echo '<ul class="pagenation">';
    for ($i = 1; $i <= $pages; $i++) {
      //1～3ページまでの設定
      if ($paged <= $range) {
        if ($paged == $i) {
          echo '<li class="current">' . $i . '</li>';
        } elseif ($i <= $showitems) {
          echo '<li><a href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
        }
      }
      //最後から2つ前～最後までの設定
      elseif ($paged >= $pages - 1) {
        if ($paged == $i) {
          echo '<li class="current">' . $i . '</li>';
        } elseif ($pages - $showitems < $i) {
          echo '<li><a href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
        }
      }
      //その他
      else {
        if (
          1 != $pages &&
          (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) ||
            $pages <= $showitems)
        ) {
          echo $paged == $i
            ? '<li class="current">' . $i . '</li>'
            : '<li><a href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
        }
      }
    }
    //next icon
    if ($paged < $pages) {
      echo '<li><a href="' . get_pagenum_link($paged + 1) . '">';
      echo '<svg class="pagenation__icon" data-slot="icon" fill="none" stroke-width="1" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"></path></svg>';
      echo '</a></li>';
      if (!is_mobile()) {
        echo '<li><a href="' . get_pagenum_link($pages) . '">' . $pages . '</a></li>';
      }
    }
    echo '</ul>';
  }
}

//ページネーションfn
function fn_pagination($use_query = null){
  if (function_exists('post_pagination')) {
    post_pagination($use_query);
  }
}

// URLのパラメータ値を取得
function fn_get_param($name)
{
  // パラメータの値を取得
  $val = isset($_GET[$name]) && $_GET[$name] != '' ? $_GET[$name] : '';
  // エスケープ処理
  $val = htmlspecialchars($val, ENT_QUOTES);
  // $valを戻り値として設定
  return $val;
}

//$public_query_vars にキーを追加
add_filter('query_vars', 'add_meta_query_vars');
function add_meta_query_vars($public_query_vars)
{
  $public_query_vars[] = 'id';
  return $public_query_vars;
}

//wp_list_categories設定
add_filter('wp_list_categories', 'my_list_categories', 10, 2);
function my_list_categories($output, $args)
{
  $output = preg_replace('/<\/a>\s*\(([\d,]+)\)/', ' <span>($1)</span></a>', $output);
  return preg_replace('/\sclass="(.+?)"/', '', $output);
}

//投稿表示件数をページ毎に設定
add_filter('pre_get_posts', 'custom_modify_query');
function custom_modify_query($query) {
  if (!is_admin() && $query->is_main_query()) {
    // ブログトップページ
    if (is_home()) {
      $query->set('posts_per_page', 6);
    }
    // カテゴリー category.php
    elseif (is_category()) {
      $query->set('posts_per_page', 6);
    }
    // 日付 archive.php
    elseif (is_date()) {
      $query->set('posts_per_page', 6);
    }
    // 作成者 archive.php
    // elseif( is_author() ) {
    //   $query->set('posts_per_page', 6);
    // }
    //検索 search.php
    elseif (is_search()) {
      if (isset($_GET['post_type']) && $_GET['post_type'] == 'カスタム投稿名') {
          $query->set('post_type', 'カスタム投稿名');
          $query->set('posts_per_page', 1); // カスタム投稿名の検索結果
      } else {
          $query->set('post_type', 'post');
          $query->set('posts_per_page', 6); // 通常の検索結果
      }
  }
    //カスタム投稿  archive-XXX.php のメインループ
    // elseif( is_post_type_archive( 'post_XXX' ) ) {
    //   $query->set('posts_per_page', 5);
    // }

    // ターム別 taxonomy-XXX.php のメインループ
    // elseif( is_tax('XXX') ) {
    //   $query->set('posts_per_page', 5);
    // }
  }
  return $query;
}

// 時間でNewを付ける
function fn_echo_new_hour() {
  $hours = 10;
  $today = date_i18n('U');
  $entry = get_the_time('U');
  $passed = date('U', $today - $entry) / 3600;
  if ($hours > $passed) {
    echo '<span>New</span>';
  }
}


//検索フォーム html5サポート
add_theme_support('html5', ['search-form']);

// カスタム投稿 の検索フォーム
// add_filter('template_include', 'custom_search_template');
function custom_search_template($template) {
    if (is_search() && isset($_GET['post_type']) && $_GET['post_type'] == 'カスタム投稿名') {
        $new_template = locate_template(array('search-カスタム投稿名.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    }
    return $template;
}

//検索フォーム 空白検索
add_action('template_redirect', 'search_redirect');
function search_redirect()
{
  if (isset($_GET['s']) && empty($_GET['s'])) {
    include TEMPLATEPATH . '/404.php';
    exit();
  }
}

//20170706追記 youtubeレスポンシブ
add_filter('the_content', 'iframe_in_youtube');
function iframe_in_youtube($the_content)
{
  if (is_singular()) {
    $the_content = preg_replace(
      '/<iframe/i',
      '<div class="youtube"><iframe',
      $the_content
    );
    $the_content = preg_replace(
      '/<\/iframe>/i',
      '</iframe></div>',
      $the_content
    );
  }
  return $the_content;
}

// 20210225 投稿 見出しタグにクラス追加
add_filter('the_content', 'add_ttl_class_filter');
function add_ttl_class_filter($content)
{
  $class_h2 = 'ttl__post--single-h2';
  $class_h3 = 'ttl__post--single-h3';
  // クラス設定されていたもの
  $content = preg_replace(
    '/(<h2.*? class=".*?)(".*?>)/',
    '$1 ' . $class_h2 . '$2',
    $content
  );
  $content = preg_replace(
    '/(<h3.*? class=".*?)(".*?>)/',
    '$1 ' . $class_h3 . '$2',
    $content
  );
  // クラス設定なかったもの
  $content = preg_replace(
    '/(<h2.*?)>/',
    '$1 class="' . $class_h2 . '" >',
    $content
  );
  $content = preg_replace(
    '/(<h3.*?)>/',
    '$1 class="' . $class_h3 . '" >',
    $content
  );

  return $content;
}


// 202307 v5.9以降インラインで挿入されるスタイル無効
add_action( 'wp_enqueue_scripts', 'remove_global_styles' );
function remove_global_styles(){
    wp_dequeue_style( 'global-styles' );
}


// header 不要タグ 絵文字
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
// header 不要タグ その他
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
//author情報
add_image_size('cstm_author_thumbnail', 360, 360, true);
// add_image_size( 'cstm_author_thumbnail_sq_small', 120, 120 , true);

function fn_thumbnail_NoAuthor() {
  echo '<img src="' .
    get_template_directory_uri() .
    '/img/noimage_sq.png"  alt="author" class="rounded-circle">';
}



// カスタム投稿 設定はプラグインacf利用 寄稿者権限で投稿画面表示確認

// カスタム投稿 post_XXXX のパーマリンク設定をpost_idに変更
add_filter('post_type_link', 'custom_post_link', 1, 2);
function custom_post_link($link, $post) {
  // カスタム投稿名が"XXX"の投稿のパーマリンクを「/XXX/投稿ID/」の形に書き換え
  if($post -> post_type === 'post_XXXX') {
    return home_url('/post_XXXX/'.$post->ID);
  } else {
    return $link;
  }
}
//書き換えたパーマリンクに対応したリライトルールを追加
add_filter('rewrite_rules_array', 'custom_post_link_rewrite');
function custom_post_link_rewrite($rules) {
  $rewrite_rules = array(
    'post_XXXX/([0-9]+)/?$' => 'index.php?post_type=post_XXXX&p=$matches[1]',
  );
  return $rewrite_rules + $rules;
}

// タイトル入力欄のプレースホルダーを変更 -> acf投稿タイプ / 高度な設定 / ラベルで設定可能
// add_filter( 'enter_title_here', 'custom_post_title_placeholder', 10, 2 );
function custom_post_title_placeholder( $title_placeholder, $post ) {
  if ( 'post_XXXX' === $post->post_type ) {
      $title_placeholder = '「さん」をつけて記入してください。例) F・Xさん';
  }
  return $title_placeholder;
}

// ターム選択で親タームを自動選択
add_action('admin_enqueue_scripts', 'custom_post_XXXX_admin_scripts');
function custom_post_XXXX_admin_scripts($hook) {
  global $post_type;
  if (($hook == 'post.php' || $hook == 'post-new.php') && $post_type == 'post_XXXX') {
      // カスタム投稿タイプ「post_XXXX」の投稿画面にスクリプトを追加
      wp_enqueue_script('custom_post_XXXX_admin_scripts', get_template_directory_uri() . '/js/post_XXXX-check_term.js', array('jquery'), '1.0', true);
  }
}

?>
<?php
//管理画面
get_template_part('functions/admin_contributor');
//管理バー非表示
add_filter('show_admin_bar', '__return_false');
add_action('admin_print_styles-profile.php', 'disable_admin_bar_prefs');
function disable_admin_bar_prefs() {
  ?>
  <style type="text/css">
    .show-admin-bar {display:none;}
  </style><?php
}
// 管理バーにログアウトを追加
add_action('wp_before_admin_bar_render', 'add_new_item_in_admin_bar');
function add_new_item_in_admin_bar(){
  global $wp_admin_bar;
  $wp_admin_bar->add_menu([
    'id' => 'new_item_in_admin_bar',
    'title' => __('&emsp;ログアウト&emsp;'),
    'href' => wp_logout_url(),
  ]);
}
// フッターWordPressリンクを非表示に
add_filter('admin_footer_text', 'custom_admin_footer');
function custom_admin_footer(){
  echo '';
}

//投稿画面書体
add_editor_style('css/editor-style.css');

// mailtrap メールテスト。 https://mailtrap.io/ で確認 複数アドレスは確認不可
// add_action('phpmailer_init', 'mailtrap');
function mailtrap($phpmailer)
{
  $phpmailer->isSMTP();
  $phpmailer->Host = 'smtp.mailtrap.io';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 2525;
  $phpmailer->Username = '691c131dd5ae75';
  $phpmailer->Password = '259fbf6b69343f';
}



// プラグイン AIOSEO の<script type="application/ld+json" class="aioseo-schema">停止
add_filter( 'aioseo_schema_disable', 'aioseo_disable_schema' );
function aioseo_disable_schema( $disabled ) {
  return true;
}


// ログイン画面
get_template_part('functions/admin_login');
// wp_mail ログ出力
get_template_part('functions/log_wpmail');
?>