<?php
global $wp_query;
global $salonInfomation;
$post_obj = $wp_query->get_queried_object();
$post_slug = $post_obj->post_name;
  $data = $salonInfomation[$post_slug];
  $icon_tel = '<svg class="c-button__icon--tel" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"></path></svg>';
?>

<section class="p-salon-info__outer">

<div class="l-row--container c-gutter__row">
  <div class="c-col--12">
    <h2 class="p-salonlist__title ">
    <span class="c-ttl__small"><span class="clr__1">#</span> サロン情報</h2>
  </div>
  </div><!-- /.l-row -->

  <div class="l-row--container c-gutter__row p-salon-info__content">

    <div class="c-col--12 c-col__md--12 c-col__lg--7">
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
  <p class="fz__14 clr__gray "><?php echo $data['address_append'] ?></p>
<?php endif; ?>
        </dd>
      </div>
      <div class="p-salon-info__dl--row">
        <dt>TEL</dt>
        <dd>
      <?php if( is_mobile()) : ?>
        <div class="l-row ">
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
<p class="fw__500">月〜土曜日</p>
<p>カット受付 10:00～18:00<br>
カラー受付 10:00～18:00<br>
パーマ受付 10:00～18:00<br>
カット＋カラー受付 10:00～17:00<br>
カット＋パーマ受付 10:00～17:00<br>
カット＋カラー＋パーマ受付 10:00～16:00<br>
カット＋アイロンストレート受付 10:00～16:00</p>
<p class="fw__500 mt__1">日曜日</p>
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
<p class="fw__500">月〜土曜日</p>
<p>カット受付 10:00～18:00<br>
カラー受付 10:00～18:00<br>
パーマ受付 10:00～18:00<br>
カット＋カラー受付 10:00～17:00<br>
カット＋パーマ受付 10:00～17:00<br>
カット＋カラー＋パーマ受付 10:00～16:00<br>
カット＋アイロンストレート受付 10:00～16:00</p>
<p class="fw__500 mt__1">日曜日</p>
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



        <p class="fz__14 clr__3 mt__1">※予約優先</p>
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
    <dt>備考</dt><dd><span class="clr__3">キッズルームあり</span></dd>
  </div>
<?php elseif( is_page('komaki')) : ?>
  <div class="p-salon-info__dl--row">
    <dt>備考</dt><dd><span class="clr__3">ネイルあり</span></dd>
  </div>
<?php elseif( is_page('tokadai')) : ?>
  <div class="p-salon-info__dl--row">
    <dt>備考</dt><dd><span class="clr__3">ネイルあり・エステあり・託児所あり</span></dd>
  </div>
<?php endif; ?>


      </dl>

    </div>
    <div class="c-col--11 c-col__sm--10 c-col__md--11 c-col__lg--5 c-gutter__md--left p-salon-info__map">
      <div id="c-gmap" class="c-gmap"><iframe src="<?php echo esc_url($data['map_src']) ?>" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""></iframe></div>
      <p class="c-gmap__link--wrap"><a href="<?php echo esc_url($data['map_link']) ?>" target="_blank" rel=”noopener noreferrer” class="c-gmap__link">Google Map で開く</a></p>
    </div>


    </div>

<div class="l-row--container c-gutter__row mt__5 mt__6--md">
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