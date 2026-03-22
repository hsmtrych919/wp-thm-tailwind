<?php
// ログイン画面カスタマイズ

// ロゴ関連
add_filter( 'login_headerurl', 'custom_login_logo_url' );
function custom_login_logo_url() {
  return get_bloginfo( 'url' );}

add_filter( 'login_headertext', 'custom_login_logo_title' );
function custom_login_logo_title() {
  return get_bloginfo( 'name' );
}

//ログインページのポリシーリンク削除
add_filter( 'the_privacy_policy_link', 'delete_privacy_policy_loginpage', 10, 2 );
function delete_privacy_policy_loginpage( $link, $privacy_policy_url ) {
  if ( 'wp-login.php' === $GLOBALS['pagenow'] ) {
      return null;
  }
}

// エラーメッセージ
function message_login_errors($method) {
  return "<strong>エラー:</strong> ログインできませんでした";
}
add_filter( 'login_errors', 'message_login_errors' );

//ログイン画面 headタグ
add_action( 'login_enqueue_scripts', 'custom_login_style',21 );
function custom_login_style(){
  wp_enqueue_style( 'notosansjp', 'https://fonts.googleapis.com/earlyaccess/notosansjp.css', array(), null, 'all');
  wp_enqueue_style( 'add_style', get_template_directory_uri().'/css/style.css' );
  //echo get_template_part('tmp/google-tag-manager/tmp', 'gtm-head');
}

// bodyタグにクラス追加
// add_filter( 'login_body_class', 'custom_login_body_class');
// function custom_login_body_class($classes) {
//     $classes[] = "l-nav_less";
//     return $classes;
// }


// wp-login.php <div id="login"> の前に出力。gtm、html
add_action( 'login_header', 'custom_login_header');
function custom_login_header() {
  echo get_template_part('tmp/google-tag-manager/tmp', 'gtm-body');
?>
  <header class="header--static">
    <div class="header__row">
      <div class="header__logo-block header__logo-block--login">
        <h1 class="header__logo items-center"><img src='<?php echo get_template_directory_uri(); ?>/img/logo.svg' alt=""></h1>
      </div>
    </div>
  </header>
<div class="main__nav-less">
<div class="ttl__bg-grd--wrap">
  <h1><span class="ttl__bg-grd">ログイン画面</span></h1>
</div>
<?php }


// wp-login.php <div class="clear"> の前に出力。.main__nav-less閉じる
add_action( 'login_footer', 'custom_login_div_close',);
function custom_login_div_close() {
  echo "</div>";
}

// wp-login.php <div class="clear"> の前に出力。footerタグ
add_action( 'login_footer', 'custom_login_footer',21);
function custom_login_footer() {
?>
<footer class="footer">
  <ul class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 footer__list">
  <li class="footer__item-copy--alone">&copy; <?php echo date('Y'); echo " "; bloginfo( 'name' ); ?>.</li>
  </ul>
</footer>
<?php }

// wp-login.php <div class="clear"> の前に出力。jq script。パスワードリセットページではjquery.js出力されないので注意
// add_action( 'login_footer', 'custom_login_script',21);
function custom_login_script() {
?>
<script>
jQuery(function ($) {
  if(!document.URL.match(/lostpassword/)) {
    $('#nav').after('<p id="backhome"><a href="<?php echo home_url(''); ?>" title="ホームへもどる"><i class="fas fa-home"></i> ホームへもどる</a></p>');
    $('#nav').after('<p id="signup"><a href="<?php echo home_url('/members-signup'); ?>" title="会員登録はコチラから">会員登録はコチラから</a></p>');
    $('#nav').after('<p id="lostpassword"><a href="<?php echo wp_login_url(); ?>?action=lostpassword" title="パスワードをお忘れですか？">パスワードをお忘れですか？</a></p>');
  }
});
</script>
<?php } ?>