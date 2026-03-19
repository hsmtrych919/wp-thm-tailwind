<?php
global $wp_query;
global $salonInfomation;
$post_obj = $wp_query->get_queried_object();
$post_slug = $post_obj->post_name;
  $data = $salonInfomation[$post_slug];
  $icon_tel = '<svg class="c-button__icon--tel" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"></path></svg>';
?>

<section class="p-salon-info__outer">

<div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
  <div class="w-full">
    <h2 class="p-salonlist__title ">
      <span class="p-ttl__small"><span class="text-clr1">#</span> サロン情報</span>
    </h2>
  </div>
  </div>

  <div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 p-salon-info__content">

    <div class="w-full md:w-full lg:w-7/12">
      <dl class="p-salon-info__dl">
      <div class="p-salon-info__dl--row">
        <dt class="p-salon-info__dt--first">サロン名</dt>
        <dd><?php echo $data['name_ja'] ?></dd>
      </div>
      <div class="p-salon-info__dl--row">
        <dt>住所</dt>
        <dd>
<?php echo $data['address'] ?>
<?php if( isset($data['address_append']) && $data['address_append'] !== '') : ?>
  <p class="text-fz14 2xl:text-fz15 text-gray-700 "><?php echo $data['address_append'] ?></p>
<?php endif; ?>
        </dd>
      </div>
      <div class="p-salon-info__dl--row">
        <dt>TEL</dt>
        <dd>
      <?php if( is_mobile()) : ?>
        <div class="flex flex-wrap justify-center ">
            <div class="p-button__wrap">
              <a href="tel:<?php echo esc_attr($data['tel_href']); ?>" class="c-button c-button__tel">
              <?php echo $icon_tel; ?><span>電話する</span>
            </a>
            </div>
          </div>
      <?php else: ?>
        <span><?php echo esc_attr($data['tel']); ?></span>
      <?php endif; ?>
        </dd>
      </div>
      <div class="p-salon-info__dl--row">
      <dt>営業時間</dt>
      <dd>
<?php if( is_page('kitatoyama')) : ?>
<p>カット受付 9:00～17:00<br>
カラー受付 9:00～17:00<br>
パーマ受付 9:00～17:00<br>
カット＋カラー受付 9:00～16:00<br>
カット＋パーマ受付 9:00～16:00<br>
カット＋カラー＋パーマ受付 9:00～15:00<br>
カット＋アイロンストレート受付 9:00～15:00</p>
<?php elseif( is_page('komaki')) : ?>
<p class="font-medium">月〜土曜日</p>
<p>カット受付 10:00～18:00<br>
カラー受付 10:00～18:00<br>
パーマ受付 10:00～18:00<br>
カット＋カラー受付 10:00～17:00<br>
カット＋パーマ受付 10:00～17:00<br>
カット＋カラー＋パーマ受付 10:00～16:00<br>
カット＋アイロンストレート受付 10:00～16:00</p>
<p class="font-medium mt-1">日曜日</p>
<p>カット受付 9:00～17:00<br>
カラー受付 9:00～17:00<br>
パーマ受付 9:00～17:00<br>
カット＋カラー受付 9:00～16:00<br>
カット＋パーマ受付 9:00～16:00<br>
カット＋カラー＋パーマ受付 9:00～15:00<br>
カット＋アイロンストレート受付 9:00～15:00</p>
<?php elseif( is_page('tokadai')) : ?>
<p>カット受付 9:00～17:00<br>
カラー受付 9:00～17:00<br>
パーマ受付 9:00～17:00<br>
カット＋カラー受付 9:00～16:00<br>
カット＋パーマ受付 9:00～16:00<br>
カット＋カラー＋パーマ受付 9:00～15:00<br>
カット＋アイロンストレート受付 9:00～15:00</p>
<?php elseif( is_page('konan')) : ?>
<p class="font-medium">月〜土曜日</p>
<p>カット受付 10:00～18:00<br>
カラー受付 10:00～18:00<br>
パーマ受付 10:00～18:00<br>
カット＋カラー受付 10:00～17:00<br>
カット＋パーマ受付 10:00～17:00<br>
カット＋カラー＋パーマ受付 10:00～16:00<br>
カット＋アイロンストレート受付 10:00～16:00</p>
<p class="font-medium mt-1">日曜日</p>
<p>カット受付 9:00～17:00<br>
カラー受付 9:00～17:00<br>
パーマ受付 9:00～17:00<br>
カット＋カラー受付 9:00～16:00<br>
カット＋パーマ受付 9:00～16:00<br>
カット＋カラー＋パーマ受付 9:00～15:00<br>
カット＋アイロンストレート受付 9:00～15:00</p>
<?php elseif( is_page('kitanagoya')) : ?>
<p>カット受付 9:00～17:00<br>
カラー受付 9:00～17:00<br>
パーマ受付 9:00～17:00<br>
カット＋カラー受付 9:00～16:00<br>
カット＋パーマ受付 9:00～16:00<br>
カット＋カラー＋パーマ受付 9:00～15:00<br>
カット＋アイロンストレート受付 9:00～15:00</p>
<?php endif; ?>



        <p class="text-fz14 2xl:text-fz15 text-clr3 mt-1">※予約優先</p>
      </dd>
      </div>

      <div class="p-salon-info__dl--row">
        <dt>定休日</dt><dd><?php echo $data['holiday']; ?></dd>
      </div>
      <div class="p-salon-info__dl--row">
        <dt>クレジットカード</dt><dd><?php echo $data['creditcard']?></dd>
      </div>
      <div class="p-salon-info__dl--row">
        <dt>セット面</dt><dd><?php echo $data['seats']; ?></dd>
      </div>
      <div class="p-salon-info__dl--row">
        <dt>駐車場</dt><dd><?php echo $data['parking']; ?></dd>
      </div>
<?php if( is_page('kitatoyama')) : ?>
  <div class="p-salon-info__dl--row">
    <dt>備考</dt><dd><span class="text-clr3">キッズルームあり</span></dd>
  </div>
<?php elseif( is_page('komaki')) : ?>
  <div class="p-salon-info__dl--row">
    <dt>備考</dt><dd><span class="text-clr3">ネイルあり</span></dd>
  </div>
<?php elseif( is_page('tokadai')) : ?>
  <div class="p-salon-info__dl--row">
    <dt>備考</dt><dd><span class="text-clr3">ネイルあり・エステあり・託児所あり</span></dd>
  </div>
<?php endif; ?>


      </dl>

    </div>
    <div class="w-11/12 sm:w-10/12 md:w-11/12 lg:w-5/12 md:pl-gutter-3 p-salon-info__map">
      <div id="c-gmap" class="c-gmap"><iframe src="<?php echo esc_url($data['map_src']) ?>" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""></iframe></div>
      <p class="c-gmap__link--wrap"><a href="<?php echo esc_url($data['map_link']) ?>" target="_blank" rel="noopener noreferrer" class="c-gmap__link">Google Map で開く</a></p>
    </div>


    </div>

<div class="l-container mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 mt-5 md:mt-6">
  <div class="c-button-2columns__frame--button">
    <div class="c-button-2columns__button">
<?php
  $args = array(
    'link_slug' => 'menu',
    'className' => 'c-button__grd',
    'text' => 'メニューページへ',
);
component_buttonLink($args);
?>
    </div>
    <div class="c-button-2columns__button--low">
<?php
  $args = array(
    'link_url' => $data['hotpepper'],
    'className' => 'c-button__clr1--border',
    'text' => 'ご予約はこちら',
);
component_buttonLinkExternal($args);
?>
    </div>
  </div>
</div>

</section>