</div>
<!-- /.l-main -->

<?php
// 変数定義
$logo_src = get_template_directory_uri() . '/img/logo.svg';
$message_text = 'guiches は尾張地区に8店舗展開する<br>トータルビューティーサロンです。';
$copy_text = '&copy; ' . date('Y') . ' guiches.';

$logo_element = "<p class='p-footer__logo'><img src='{$logo_src}' alt='guiches ギッシュ'></p>";
$message_element = "<p class='p-footer__message'>{$message_text}</p>";
$copy_element = "<p class='p-footer__copy'>{$copy_text}</p>";

$footer_links = array(
  'salon' => 'サロン',
  'menu' => 'メニュー',
  'champaign' => 'キャンペーン',
  'staff' => 'スタッフ',
  'service' => 'サービス',
  'style' => 'スタイル',
  'company' => '会社概要',
  'form-contact' => 'お問い合わせ',
  'qa' => 'よくあるご質問',
  'recruit' => '採用情報',
  'privacy-policy' => 'プライバシーポリシー',
  'sitemap' => 'サイトマップ',
);

$icon_recruit = '<svg class="c-footernav__button--icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.91-1.91l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.91-1.91l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0116.5 15z"></path></svg>';
$icon_camera = '<svg class="c-footernav__button--icon-insta" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"></path></svg>';
$icon_salon = '<svg class="c-footernav__button--icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M8.128 9.155a3.751 3.751 0 11.713-1.321l1.136.656a.75.75 0 01.222 1.104l-.006.007a.75.75 0 01-1.032.157 1.421 1.421 0 00-.113-.072l-.92-.531zm-4.827-3.53a2.25 2.25 0 013.994 2.063.756.756 0 00-.122.23 2.25 2.25 0 01-3.872-2.293zM13.348 8.272a5.073 5.073 0 00-3.428 3.57c-.101.387-.158.79-.165 1.202a1.415 1.415 0 01-.707 1.201l-.96.554a3.751 3.751 0 10.734 1.309l13.729-7.926a.75.75 0 00-.181-1.374l-.803-.215a5.25 5.25 0 00-2.894.05l-5.325 1.629zm-9.223 7.03a2.25 2.25 0 102.25 3.897 2.25 2.25 0 00-2.25-3.897zM12 12.75a.75.75 0 100-1.5.75.75 0 000 1.5z"></path><path d="M16.372 12.615a.75.75 0 01.75 0l5.43 3.135a.75.75 0 01-.182 1.374l-.802.215a5.25 5.25 0 01-2.894-.051l-5.147-1.574a.75.75 0 01-.156-1.367l3-1.732z"></path></svg>';
$icon_reserve = '<svg class="c-footernav__button--reserve-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"></path></svg>';
$icon_tel = '<svg class="c-footernav__button--tel-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"></path></svg>';

// 条件分岐変数
$isContactPage = is_page('form-contact') || is_page('form-contact-chk') || is_page('form-contact-thk');
$isSalonPage = is_page('salon/kitatoyama') || is_page('salon/komaki') || is_page('salon/tokadai') || is_page('salon/konan') || is_page('salon/kitanagoya');
$isRecruitPage = is_page('recruit') || is_page('recruit/info-guiches') || is_page('recruit/info-soho');

// クラスプレフィックス
if ($isSalonPage) {
  $prefixFooternavItem = '--for-salon';
} elseif($isRecruitPage) {
  $prefixFooternavItem = '--for-recruit';
} else {
  $prefixFooternavItem = '';
}

// サロンページ判定
$currentPage = get_page_uri();
$salonPageSlugs = array(
  'salon/kitatoyama',
  'salon/komaki',
  'salon/tokadai',
  'salon/konan',
  'salon/kitanagoya'
);

?>


<?php if ( $isContactPage ): ?>
  <footer class="l-footer  p-footer">
    <div class="l-row--container c-gutter__row md:justify-between">
      <div class="c-footer__info c-footer__info--alone">
      <?php echo $logo_element . $message_element . $copy_element; ?>
      </div>
    </div>
  </footer>
<?php else : ?>
  <footer class="l-footer  p-footer c-footer--have-footernavi">
    <div class="l-row--container c-gutter__row md:justify-between">
      <div class="c-footer__info">
      <?php echo $logo_element . $message_element . $copy_element; ?>
      </div>

      <ul class="c-footer__links">
      <?php foreach ($footer_links as $url => $label) : ?>
          <li class="c-footer__links--item"><a class="p-footer__links--item" href='<?php echo home_url('/'.$url); ?>'><?php echo $label; ?></a></li>
      <?php endforeach; ?>
        </ul>
    </div>
  </footer>
<?php endif; ?>


<!-- pagetopButton -->
<?php if ( !$isContactPage ) : ?>
<p id="js-fadeButton-pagetop" class="p-footer__fade--button">
  <a href="#top" class="c-footer__fade--button">
  <svg class="c-footer__fade--icon" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"></path></svg>
</a>
</p>
<?php endif; ?>


<!-- reserveButton -->
<?php if ( !$isContactPage && !$isRecruitPage ) : ?>
<button data-micromodal-trigger="modal-salonlist" role="button" id="js-fadeButton-reserve" class="p-footer-fix__reserve gtm-pc-reserve-list">
  <span class="p-footer-fix__reserve--inner">ご予約</span>
</button>
<?php endif; ?>


<!-- .l-footernav -->


<?php if ( !$isContactPage ) : ?>
  <div class="l-footernav">
  <ul class="p-footernav__list">

<!-- instagram -->
  <li class="p-footernav__item<?php echo esc_attr($prefixFooternavItem); ?>">
      <a class="c-footernav__button gtm-footer-insta" href="https://www.instagram.com/guiches_official/?hl=ja" target="_blank" rel="noopener noreferrer">
      <span class="p-footernav__insta--typ">instagram</span>
      <?php echo $icon_camera; ?>
    </a>
  </li>

<?php if ( !$isRecruitPage ) : ?>
    <!-- recruit -->
    <li class="p-footernav__item<?php echo esc_attr($prefixFooternavItem); ?>">
        <a class="c-footernav__button gtm-footer-recruit" href='<?php echo home_url('/recruit');?>' >
      <span>採用情報</span>
      <?php echo $icon_recruit; ?>
      </a>
    </li>
<?php endif; ?>

  <?php if (in_array($currentPage, $salonPageSlugs)) : ?>
    <!-- salon/page -->
    <?php listItemSalon($post->post_name, $icon_reserve) ?>
  <?php else : ?>
    <!-- salon -->
    <li class="p-footernav__item<?php echo esc_attr($prefixFooternavItem); ?>"><a class="c-footernav__button  gtm-footer-salon" href='<?php echo home_url('/salon');?>' >
    <span>サロン一覧</span>
    <?php echo $icon_salon; ?>
    </a></li>

      <?php if ( !$isRecruitPage ) : ?>
        <!-- reserve -->
        <li class="p-footernav__item--reserve"><button data-micromodal-trigger="modal-salonlist" role="button" class="c-footernav__button--reserve gtm-footer-reserve-list"  >
          <div class="c-footernav__button--reserve-inner">
            <?php echo $icon_reserve; ?>
            <span>予約する</span>
          </div>
        </button></li>
      <?php endif; ?>

<?php endif; ?>

  </ul>
</div>

<?php endif; ?>


<!-- micromodal.js -->
<?php if ( !$isContactPage && !$isRecruitPage ) : ?>
<div class="c-micromodal__outer" id="modal-salonlist" aria-hidden="true">
  <div class="c-micromodal__overlay" tabindex="-1" data-micromodal-close>
    <div class="c-micromodal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
      <div class="p-footer-modal__wrap">
          <p class="p-footer-modal__title">ギッシュ各店へのご予約<span class="max-md:hidden">はこちら</span></p>
          <ul class="p-footer-modal__list">
            <li><?php linkReserveSalon('kitatoyama') ?></li>
            <li><?php linkReserveSalon('komaki') ?></li>
            <li><?php linkReserveSalon('tokadai') ?></li>
            <li><?php linkReserveSalon('konan') ?></li>
            <li><?php linkReserveSalon('kitanagoya') ?></li>
          </ul>
      </div>
      <button class="c-micromodal__close" aria-label="Close modal" data-micromodal-close><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
    </div>
  </div>
</div>
<?php endif; ?>
<!-- /micromodal.js -->


<!--script-->
<?php wp_footer(); ?>
</body>
</html>



<?php
// サロンページ li要素の出力
function listItemSalon($salonKey, $icon_reserve) {
  global $salonInfomation;
  $data = $salonInfomation[$salonKey];
  $class_name_reserve = 'gtm-footer-reserve-' . $salonKey;
  $class_name_tel = 'gtm-footer-tel-' . $salonKey;
  $url = $data['hotpepper'];
  $tel = $data['tel_href'];
      echo '<li class="p-footernav__item--for-salon">';
      echo '<a class="sm:hidden c-footernav__button ' . $class_name_reserve . '" href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
      echo '<span>予約する</span>';
      echo '<svg class="c-footernav__button--icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"></path></svg>';
      echo '</a>';
      echo '<a class="hidden sm:block c-footernav__button--reserve ' . $class_name_reserve . '" href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
      echo '<div class="c-footernav__button--reserve-inner">';
      echo $icon_reserve;
      echo '<span>予約する</span>';
      echo '</div>';
      echo '</a>';
      echo '</li>';

      echo '<li class="p-footernav__item--tel">';
      echo '<a class="c-footernav__button--tel ' . $class_name_tel . '" href="tel:' . esc_attr($tel) . '">';
      echo '<div class="c-footernav__button--tel-inner">';
      echo '<svg class="c-footernav__button--tel-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.860 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"></path></svg>';
      echo '<span>電話する</span>';
      echo '</div>';
      echo '</a>';
      echo '</li>';
    }

    // 予約リスト a要素の出力
    function linkReserveSalon($salonKey) {
      global $salonInfomation;
      $data = $salonInfomation[$salonKey];
      // 追加のクラスを生成
      $additionalClass = 'gtm-modal-htpr-' . $salonKey;
      $className = 'c-button__clr1--border ' . $additionalClass;
      $args = array(
        'link_url' => $data['hotpepper'],
        'className' => $className,
        'text' => $data['name'],
      );
      component_buttonLinkExternal($args);
    }

    // 電話リスト a要素の出力
    // function linkTelSalon($salonKey) {
    //   global $salonInfomation;
    //   $data = $salonInfomation[$salonKey];
    //   $salonName = $data['name'];
    //   $tel = $data['tel_href'];
    //   echo '<a href="tel:'.esc_attr($tel).'" class="c-button c-button__tel">'.$salonName;
    //   echo '<svg class="c-button__icon--tel" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"></path></svg>';
    //   echo '</a>';
    // }
?>