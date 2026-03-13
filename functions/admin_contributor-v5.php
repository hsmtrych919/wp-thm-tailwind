<?php
//current_user_can('contributor') 権限の追加
add_action( 'admin_init', 'add_theme_caps' );
function add_theme_caps(){
    $role = get_role( 'contributor' );
    //画像アップロード
    $role->add_cap( 'upload_files' );
    //投稿の公開
    $role->add_cap( 'publish_posts' );
    //投稿の編集
    $role->add_cap( 'edit_published_posts' );
    //投稿の削除
    $role->add_cap( 'delete_published_posts' );
}

//current_user_can('contributor') 投稿者権限による表示設定

// 特定のユーザーID(10)のみカスタム投稿を表示 + ブログ投稿を非表示
add_action('admin_menu', 'customize_menu_for_contributors', 100);
function customize_menu_for_contributors() {
    if (current_user_can('contributor')) {
        if (get_current_user_id() != 10) {
            remove_menu_page('edit.php?post_type=arrivals'); // カスタム投稿メニューを非表示にする
        } else {
            remove_menu_page('edit.php'); // 通常のブログ投稿メニューを非表示にする
        }
    }
}

// ログイン時に寄稿者contributorは ブログ投稿ページへ + 特定のユーザーID(10)のみカスタム投稿ページへ
if (current_user_can('contributor')) {
  add_action('admin_init', 'redirect_login_front_page');
}
function redirect_login_front_page() {
  if (preg_match('/(\/wp-admin\/index.php)/', $_SERVER['SCRIPT_NAME'])) {
      if (get_current_user_id() == 10) {
          $redirect_url = admin_url('edit.php?post_type=arrivals'); // カスタム投稿 'arrivals' の一覧ページにリダイレクト
      } else {
          $redirect_url = admin_url('edit.php'); // 通常の投稿一覧ページにリダイレクト
      }
      wp_redirect($redirect_url);
      exit;
  }
}



// サイドメニューを非表示にする
add_action('admin_menu', 'remove_menus');
function remove_menus () {
  if (current_user_can('contributor')){//権限の指定

    remove_menu_page('index.php');// ダッシュボード
    remove_menu_page('profile.php'); // プロフィール
    remove_menu_page('edit-comments.php'); // コメント
    remove_menu_page('tools.php'); // ツール
    // 202103追記
    remove_menu_page( 'all-in-one-seo-pack/aioseop_class.php' ); // All In One SEO Pack.
    remove_submenu_page( 'tools.php', 'aiosp_import' ); // All In One SEO Pack.
    remove_menu_page( 'backwpup' ); // BackWPup.
    remove_submenu_page( 'upload.php', 'ewww-image-optimizer-bulk' ); // EWWWW.
    remove_submenu_page( 'options-general.php', 'ewww-image-optimizer/ewww-image-optimizer.php' ); // EWWWW.
    remove_menu_page( 'edit.php?post_type=mw-wp-form' ); // MW WP Form.
    remove_menu_page('groups-admin'); // groups
  }
}

//管理バーの表示内容
add_action( 'admin_bar_menu', 'remove_admin_bar_menu', 201 );
function remove_admin_bar_menu( $wp_admin_bar ) {
  if (current_user_can('contributor')){//権限の指定
    $wp_admin_bar->remove_menu('wp-logo'); // WordPressロゴ
    $wp_admin_bar->remove_menu('site-name'); // サイト名
    $wp_admin_bar->remove_menu('view-site'); // サイト名のドロップタウン内のサイトを表示
    $wp_admin_bar->remove_menu('updates'); // 更新
    $wp_admin_bar->remove_menu('comments'); // コメント
    $wp_admin_bar->remove_menu('new-content'); // 新規
    $wp_admin_bar->remove_menu('new-post'); // 新規のドロップタウン内の投稿
    $wp_admin_bar->remove_menu('new-media'); // 新規のドロップタウン内のメディア
    $wp_admin_bar->remove_menu('new-link'); // 新規のドロップタウン内のリンク
    $wp_admin_bar->remove_menu('new-page'); // 新規のドロップタウン内の固定ページ
    $wp_admin_bar->remove_menu('new-user'); // 新規のドロップタウン内のユーザー
    $wp_admin_bar->remove_menu('my-account'); // マイアカウント
    $wp_admin_bar->remove_menu('user-info'); // マイアカウントのドロップタウン内のプロフィール
    $wp_admin_bar->remove_menu('edit-profile'); // マイアカウントのドロップタウン内のプロフィール編集
    $wp_admin_bar->remove_menu('logout'); // マイアカウントのドロップタウン内のログアウト
    $wp_admin_bar->remove_menu('search'); // 検索
  }
}

//投稿ページの不要な機能を削除
if (current_user_can('contributor')){
  add_action( 'admin_menu' , 'remove_extra_meta_boxes' );
  function remove_extra_meta_boxes() {
    // remove_meta_box( 'categorydiv','post','side'); /* 投稿のカテゴリーフィールド */
    remove_meta_box( 'postcustom' , 'post' , 'normal' ); /* 投稿のカスタムフィールド */
    remove_meta_box( 'postcustom' , 'page' , 'normal' ); /* 固定ページのカスタムフィールド */
    remove_meta_box( 'postexcerpt' , 'post' , 'normal' ); /* 投稿の抜粋 */
    remove_meta_box( 'postexcerpt' , 'page' , 'normal' ); /* 固定ページの抜粋 */
    remove_meta_box( 'commentsdiv' , 'post' , 'normal' ); /* 投稿のコメント */
    remove_meta_box( 'commentsdiv' , 'page' , 'normal' ); /* 固定ページのコメント */
    remove_meta_box( 'tagsdiv-post_tag' , 'post' , 'side' ); /* 投稿のタグ */
    remove_meta_box( 'tagsdiv-post_tag' , 'page' , 'side' ); /* 固定ページのタグ */
    remove_meta_box( 'trackbacksdiv' , 'post' , 'normal' ); /* 投稿のトラックバック */
    remove_meta_box( 'trackbacksdiv' , 'page' , 'normal' ); /* 固定ページのトラックバック */
    remove_meta_box( 'commentstatusdiv' , 'post' , 'normal' ); /* 投稿のディスカッション */
    remove_meta_box( 'commentstatusdiv' , 'page' , 'normal' ); /* ページのディスカッション */
    remove_meta_box( 'slugdiv','post','normal'); /* 投稿のスラッグ */
    remove_meta_box( 'slugdiv','page','normal'); /* 固定ページのスラッグ */
    remove_meta_box( 'authordiv','post','normal' ); /* 投稿の作成者 */
    remove_meta_box( 'authordiv','page','normal' ); /* 固定ページの作成者 */
    remove_meta_box( 'revisionsdiv','post','normal' ); /* 投稿のリビジョン */
    remove_meta_box( 'revisionsdiv','page','normal' ); /* 固定ページのリビジョン */
    remove_meta_box( 'pageparentdiv','page','side'); /* 固定ページのページ属性 */
    remove_meta_box('formatdiv', 'post', 'normal');/*投稿フォーマット*/
    //remove_meta_box('postimagediv', 'page', 'normal'); /*アイキャッチ画像*/
    remove_meta_box('formatdiv', 'page', 'normal'); /*ページ属性*/
  }
}

// 更新系非表示
if (!current_user_can('administrator')) {
  function hidden_transient_update_core($method) {
    return "return null;";
  }
  add_filter('pre_site_transient_update_core', 'hidden_transient_update_core');
}

// ダッシュボードウィジェット非表示
add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets');
function example_remove_dashboard_widgets() {
if (current_user_can('contributor')){
 global $wp_meta_boxes;
 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 現在の状況
 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿
 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
 }
 }

//表記変更
if (current_user_can('contributor')){
  add_filter(  'gettext',  'change_side_text'  );
  add_filter(  'ngettext',  'change_side_text'  );
  function change_side_text( $translated ) {
      $translated = str_ireplace(  'ダッシュボード',  '管理画面',  $translated );
      $translated = str_ireplace(  'メディア',  '画像',  $translated );
      return $translated;
  }
}
//表記変更　投稿>ブログ
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );
function change_post_menu_label() {
  if (current_user_can('contributor')){
  global $menu;
  global $submenu;
  $menu[5][0] = 'ブログ';
  $submenu['edit.php'][5][0] = 'ブログ一覧';
  $submenu['edit.php'][10][0] = '新しいブログ';
  // $submenu['edit.php'][16][0] = 'タグ';
  //echo ";
  }
}
function change_post_object_label() {
  if (current_user_can('contributor')){
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'ブログ';
  $labels->singular_name = 'ブログ';
  $labels->add_new = _x('追加', 'ブログ');
  $labels->add_new_item = 'ブログの新規追加';
  $labels->edit_item = 'ブログの編集';
  $labels->new_item = '新規ブログ';
  $labels->view_item = 'ブログを表示';
  $labels->search_items = 'ブログを検索';
  $labels->not_found = '記事が見つかりませんでした';
  $labels->not_found_in_trash = 'ゴミ箱に記事は見つかりませんでした';
  }
}

//投稿画面レイアウト固定
add_action( 'admin_head-post.php', 'my_disable_metabox_sortables_script' );
add_action( 'admin_head-post-new.php', 'my_disable_metabox_sortables_script' );
function my_disable_metabox_sortables_script() {
  wp_enqueue_script( 'disable-metabox-sortables', get_template_directory_uri() . '/js/disable-metabox-sortables.js', array( 'postbox' ) );
}

//投稿リストから「すべて」などを消す
add_filter( 'manage_posts_columns', 'custom_columns' );
function custom_columns($columns) {
if (!current_user_can('level_10')) {
echo '<style type="text/css">li.all,li.publish,li.pending {display:none;}</style>';
}
return $columns;
}

//他の ユーザーの投稿を見れないようにする
add_action( 'pre_get_posts', 'exclude_other_posts' );
function exclude_other_posts( $wp_query ) {
  if ( is_admin() && ! current_user_can( 'manage_options' ) && $wp_query->is_main_query() && ! $wp_query->get( 'author' ) ) {
    $wp_query->set( 'author', get_current_user_id() );
  }
}

// メディアの抽出条件にログインユーザーの絞り込み条件を追加する
function display_only_self_uploaded_medias( $wp_query ) {
    if ( is_admin() && ( $wp_query->is_main_query() || ( defined( 'DOING_QUERY_ATTACHMENT' ) && DOING_QUERY_ATTACHMENT ) ) && $wp_query->get( 'post_type' ) == 'attachment' ) {
        $user = wp_get_current_user();
        $wp_query->set( 'author', $user->ID );
    }
}
function define_doing_query_attachment_const() {
    if ( ! defined( 'DOING_QUERY_ATTACHMENT' ) ) {
        define( 'DOING_QUERY_ATTACHMENT', true );
    }
}
wp_get_current_user();
if(current_user_can('contributor')){
add_action( 'pre_get_posts', 'display_only_self_uploaded_medias' );
add_action( 'wp_ajax_query-attachments', 'define_doing_query_attachment_const', 0 );
}

//ビジュアルエディタ フォーマットの管理
add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );
function custom_editor_settings( $initArray ){
    $initArray['block_formats'] = "見出し1=h2; 見出し2=h3; 段落=p;";
    return $initArray;
}

//追加スタイル
add_action('admin_head', 'my_admin_head');
function my_admin_head(){
  if (current_user_can('contributor')){
    echo '<style type="text/css">#screen-options-link-wrap{display:none;}</style>';//表示オプション非表示
    echo '<style type="text/css">#tagsdiv-post_tag{display:none;}</style>';//タグ設定非表示
    echo '<style type="text/css">#submitpost .aioseo-score-settings{display:none;}</style>';//aioseoスコア非表示
    echo '<style type="text/css">#aioseo-settings{display:none;}</style>';//aioseo設定非表示
    echo '<style type="text/css">.aioseo-review-plugin-cta{display:none;}</style>';//aioseo設定非表示
    echo '<style type="text/css">#um-admin-restrict-content{display:none;}</style>';//UM設定非表示
    echo '<style type="text/css">#contextual-help-link-wrap{display:none;}</style>';//ヘルプ非表示
    echo '<style type="text/css">#wp-admin-bar-new_item_in_admin_bar{float:right !important;display:block !important;}</style>';//ログアウト レイアウト
    echo '<style type="text/css">#wp-admin-bar-site-name:hover .ab-item {background:inherit !important;color:#eee !important;} #wp-admin-bar-site-name:hover .ab-item:before{color:#a0a5aa !important;}</style>';//ログイン情報
    //echo '<style type="text/css">.wp-switch-editor.switch-html{display:none !important;}</style>';//投稿 テキストedit非表示
    echo '<style type="text/css">#category-tabs .hide-if-no-js{display:none !important;}</style>';//投稿 カテゴリー良く使うもの非表示
    echo '<style type="text/css">.form-table .user-rich-editing-wrap, .form-table .user-comment-shortcuts-wrap, .user-admin-color-wrap, show-admin-bar, .user-language-wrap, .user-admin-bar-front-wrap, .user-pass1-wrap, .user-first-name-wrap, .user-last-name-wrap, .user-nickname-wrap, .user-display-name-wrap, .user-user-login-wrap, .user-sessions-wrap, .user-email-wrap, .user-url-wrap, .user-description-wrap {display:none !important;}</style>';//個人設定 一部非表示
    echo '<style type="text/css">#profile-page.wrap #your-profile h2:nth-of-type(n+1) {display:none !important;}</style>';
    echo '<style type="text/css">#footer-upgrade {display:none !important;}</style>';//右下バージョン非表示
    echo '<style type="text/css">.misc-pub-section.misc-pub-post-status, .misc-pub-section.curtime.misc-pub-curtime {display:none !important;}</style>';//投稿パネル 一部非表示
    // Ver.5
    echo '<style type="text/css">.components-popover__content > div.components-menu-group:last-of-type {display:none !important;}</style>';//表示オプション非表示
  }
}

//左上ログイン情報からaタグ削除
add_action('wp_before_admin_bar_render', 'add_login_name');
function add_login_name() {
  if (current_user_can('contributor')){
    global $wp_admin_bar;
    $user = wp_get_current_user();
    $name = $user->get('display_name');//ユーザー名
    //$name = get_bloginfo('name');//サイト名
    $wp_admin_bar->add_menu(array(
    'id' => 'site-name',
    'title' => $name,
    'href' => false
    ));
  }
}

//カテゴリ選択のラジオボタン化 v5
add_action( 'admin_footer-post-new.php', 'change_category_to_radio' );
add_action( 'admin_footer-post.php', 'change_category_to_radio' );
function change_category_to_radio() {
echo "<script type='text/javascript'>
jQuery(window).on('load',function(){

  setTimeout(function(){
      var checkLists = $('.editor-post-taxonomies__hierarchical-terms-list');
      var cnt = 0;
      checkLists.each(function(){
          var check = $(this).find('input');
          var input = $('<input>');
          input.attr({
              type: 'radio',
              id: check.attr('id'),
              name: check.attr('name'),
              value: check.val()
          });
          if($(this).hasClass('popular-category') && cnt === 0){
             input.prop('checked', true);
             cnt++
          }
          input.insertBefore(check);
          check.remove();
      });
  },2000);

$(document).on('click', '.components-panel__body-toggle', function(){
  setTimeout(function(){
      var checkLists = $('.editor-post-taxonomies__hierarchical-terms-list');
      var cnt = 0;
      checkLists.each(function(){
          var check = $(this).find('input');
          var input = $('<input>');
          input.attr({
              type: 'radio',
              id: check.attr('id'),
              name: check.attr('name'),
              value: check.val()
          });
          if($(this).hasClass('popular-category') && cnt === 0){
             input.prop('checked', true);
             cnt++
          }
          input.insertBefore(check);
          check.remove();
      });
  },1000);
});
});
</script>";
}


//投稿画面からAll in One SEO 欄を消す
add_action( 'admin_menu' , 'remove_aiosp_meta_box', 1000 );
function remove_aiosp_meta_box() {
  if ( ! current_user_can( 'administrator' ) ) {
    remove_meta_box( 'aiosp', 'post', 'advanced' );
  }
}


//投稿一覧のAll in One SEO関連タイトルを非表示にする
add_filter( 'manage_edit-post_columns', 'my_custom_manage_aio_columns', 11 );
add_filter( 'manage_edit-page_columns', 'my_custom_manage_aio_columns', 11 );
function my_custom_manage_aio_columns($columns) {
  unset( $columns['seotitle'] );      // SEO title
  unset( $columns['seokeywords'] );   // SEO keyword
  unset( $columns['seodesc'] );       // SEO descript
  unset( $columns['se-actions'] );    // SEO action
  unset( $columns['aioseo-details'] );    // AIOSEO
  return $columns;
}

// 20171030フィルタの登録
add_filter('content_save_pre','test_save_pre');

function test_save_pre($content){
    global $allowedposttags;

    // iframeとiframeで使える属性を指定する
    $allowedposttags['iframe'] = array('class' => array () , 'src'=>array() , 'width'=>array(),
    'height'=>array() , 'frameborder' => array() , 'scrolling'=>array(),'marginheight'=>array(),
    'marginwidth'=>array());

    return $content;
}

?>