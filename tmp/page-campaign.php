<?php
$period = '2024/2/29（火）まで';
$notice_all = '<p class="text-fz14 2xl:text-fz15 text-gray-700 mt-1">※価格は全て税込表示です。<br>※カラー・パーマ・トリートメントはセミロング各+¥550 ロング各+¥1,100<br>※他の割引・特典との併用不可</p>';
?>

<article class="">
  <div class="c-headline-pic__outer">
    <div class="l-container mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0">
      <div class="c-headline-pic__frame js-headline-frame" >
        <h1 class="c-headline-pic__title c-headline-pic__title--long"><span class="c-headline-pic__typ--for-gsap">CAMPAIGN</span></h1>
        <div class="c-headline-pic__pic js-headline-pic" >
        <span class="c-headline-pic__pic--ract"></span>
        <img src='<?php echo get_template_directory_uri(); ?>/img/c_campaign.jpg' alt="メセージ">
        </div>
        <div class="c-headline-pic__detail c-headline-pic__typ--for-gsap">
          <p class="p-typ">セットメニューがとってもお得なキャンペーン実施中！</p>
        </div>
      </div>
    </div>
  </div>

  <section class=" mt-6">
      <div class="l-container mx-auto flex flex-wrap justify-between px-gutter-row xl:px-0">
        <div class="p-campaign__content">

        <div class="flex flex-wrap justify-center">
          <div class="w-full">
            <p class=""><span class="p-ttl__small text-clr3"><span class="text-clr1">#</span> スペシャルチョイスメニュー</span></p>
          </div>
        </div>
            <div class="flex flex-wrap justify-start mt-2">
        <ul class="p-campaign__list">
            <li>
              <div class="p-campaign__item">
              <p class="p-campaign__period"><?php echo $period?></p>
                  <div class="p-campaign__title">
                    <span class="p-campaign__title--caption">初めて or 1年以上ぶりのお客様限定</span>
                  カット＋スペシャルチョイスメニュー（お好きなものいくつでもOK）</div>
                  <div class="p-campaign__detail">
                    <p>セットメニューにするととってもお得！小顔効果と再現性の高い技術でお客様の「なりたい想い」を叶えます。</p>
                  </div>
                  <p class="p-campaign__price--normal">【通常価格¥4,730＋追加メニュー価格】</p>
                  <p class="p-campaign__price--frame">
                  <span class="p-campaign__price--caption">キャンペーン価格</span>
                    <br class="lg:hidden">
                    <span class="p-campaign__price">¥3,311 ＋ <br class="sm:hidden">追加メニュー30%OFF価格</span>
                  </p>
                  <p class="text-fz14 2xl:text-fz15 text-gray-700 mt-1">例）カット＋グレイカラーの場合、通常価格10,010円が7,007円。<br>カット＋縮毛矯正の場合、通常価格22,550円が15,785円。</p>
                  <?php echo wp_kses_post($notice_all)?>
              </div>
          </li>
          </ul>
            </div>
        <div class="flex flex-wrap justify-center mt-5">
          <div class="w-full">
            <p class=""><span class="p-ttl__small text-clr3"><span class="text-clr1">#</span> 至高シリーズ</span></p>
          </div>
        </div>
        <div class="flex flex-wrap justify-start mt-2">
        <ul class="p-campaign__list">
        <?php
$campaigns = array(
  array(
    'title' => '至高パーマコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋パーマ＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '18,700',
    'price' => '14,740',
  ),
  array(
    'title' => '至高グレイカラーコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋イルミナグレイカラー＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '20,130',
    'price' => '16,170',
  ),
  array(
    'title' => '至高ファッションカラーコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋イルミナファッションカラー＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '20,740',
    'price' => '16,830',
  ),
  array(
    'title' => '至高ストカールコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋ストカール＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '35,420',
    'price' => '30,580',
  ),
  array(
    'title' => '至高ミラクルベールコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋ミラクルベール＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '21,890',
    'price' => '17,710',
  ),
  array(
    'title' => '至高グレイカラー&パーマコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋イルミナグレイカラー＋パーマ＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '26,180',
    'price' => '22,000',
  ),
  array(
    'title' => '至高ファッションカラー&パーマコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋イルミナファッションカラー＋パーマ＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '26,840',
    'price' => '22,660',
  ),
  array(
    'title' => '至高デジタルカールコース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋デジタルカール＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '27,060',
    'price' => '22,660',
  ),
  array(
    'title' => '至高縮毛矯正コース',
    'content' => '炭酸シャンプーとファイバーグルーがついてくる特別なコース。',
    'detail' => 'スペシャルカット＋縮毛矯正＋プレミアムトリートメント＋炭酸シャンプー＋ファイバーグルー',
    'price_normal' => '30,470',
    'price' => '25,850',
  ),
);

component_listItemCampaignShikou($campaigns, $period,$notice_all)
?>
          </ul>
            </div>

        </div>
        <div class="p-campaign__content--reserve">
          <div class="p-campaign__reserve">
            <p class="p-typ text-gray-700">ご予約はこちら</p>
            <ul class="p-campaign__reserve--list">
<?php
global $salonInfomation;
foreach ($salonInfomation as $slug => $data) {
  $args = array(
    'link_url' => $data['hotpepper'],
    'className' => 'c-button__clr1--border',
    'text' => $data['name'],
  );
  echo '<li>';
  component_buttonLinkExternal($args);
  echo '</li>';
}
?>
            </ul>




          </div>
        </div>
      </div>
  </section>

</article>

