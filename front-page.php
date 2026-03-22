<?php get_header(); ?>

<?php // get_template_part('tmp/content/slide', 'front'); ?>


<article class="mt-9 md:mt-10">

<section class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 replace__flex-start js-replace-height">
  <div class="replace__content--left">
    <h2 class="ttl__large">replace__flex-start</h2>
    <div class="replace__pic--right js-replace-pic">
    <img src='<?php echo get_template_directory_uri(); ?>/img/a_.jpg' alt="">
    </div>
    <div class="replace__detail">
      <p class="ttl__small text-clr2 mt-2">ダミーテキストです。ダミーテキストです。</p>
      <p class="typ mt-2">
      この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
    </div>
  </div>
</section>

<section class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 replace__flex-end js-replace-height mt-5 ">
  <div class="replace__content--right">
    <h2 class="ttl__large">replace__flex-end</h2>
    <div class="replace__pic--left js-replace-pic">
    <img src='<?php echo get_template_directory_uri(); ?>/img/a_.jpg' alt="">
    </div>
    <div class="replace__detail">
      <p class="ttl__small text-clr2 mt-2">ダミーテキストです。ダミーテキストです。</p>
      <p class="typ mt-2">
      この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
    </div>
  </div>
</section>

</article>



<article>
  <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
    <div class="w-full md:w-10/12 xl:w-9/12 flex flex-wrap justify-center js-inview__fade--pre">
      <div class="w-full sm:w-7/12 md:w-8/12 sm:pr-gutter-2 md:pr-gutter-3">
        <h2 class="ttl__small ">この文章はダミーテキストです。</h2>
        <p class="typ__xs">.typ__xs / この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
        <p class="mt-2 md:mt-3 typ">.typ / この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
        <p class="mt-2 md:mt-3 typ__s">.typ__s / この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
        <p class="mt-2 md:mt-3 typ__m">.typ__m / この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
        <p class="mt-2 md:mt-3 typ__l">.typ__l / この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
      </div>
      <div class="w-full sm:w-5/12 md:w-4/12">
        <img src='<?php echo get_template_directory_uri(); ?>/img/a_.jpg' alt="">
      </div>
    </div>
  </div>
</article>

<article class="mt-6">
<h1>レイアウト / c-split</h1>
<div class="container-py">

  <div class="split__outer">
    <div class="split__thumbnail--left">
    <img src='<?php echo get_template_directory_uri(); ?>/img/a_.jpg' alt="">
    </div>
    <div class="split__content--right">
      <p class="typ">この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。この文章はダミーテキストです。文字サイズや行間などを確認するために入れています。</p>
    </div>
  </div>

  <div class="split__outer--reverse mt-6">
    <div class="split__thumbnail--right">
    <img src='<?php echo get_template_directory_uri(); ?>/img/a_.jpg' alt="">
    </div>
    <div class="split__content--left ">
    <ul class="bgi_list">
        <li>文字サイズや行間などを確認するために入れています。</li>
        <li>この文章はダミーテキストです<br class="max-md:hidden">文字サイズや行間などを確認するために入れています。</li>
        <li>文字サイズや行間などを確認するために入れています。文字サイズや行間などを確認するために入れています。</li>
        <li>この文章はダミーテキストですこの文章はダミーテキストです。</li>
        <li>この文章はダミーテキストです。</li>
      </ul>
    </div>
  </div>

</div>

  </div>
  </div>
</article>



<article class="test">
<div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
  <div class="w-full sm:w-full md:w-full">
    <ul class="grid gap-x-grid-gutter grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 likepost__list js-fadeup__stagger--list">
<?php
$args = array(
    'link_slug' => 'campaign',
    'image_src' => 'b_01.jpg',
    'lead_text' => 'CAMPAIGN',
    'content' => 'セットメニューがとってもお得な<br class="md:hidden xl:block">キャンペーン実施中！',
);
component_listItemLikepost($args)
?>
<?php
$args = array(
    'link_slug' => 'staff',
    'image_src' => 'b_02.jpg',
    'lead_text' => 'STAFF',
    'content' => '誰からも好かれ、愛される<br class="md:hidden xl:block">ギッシュグループのスタッフ達',
);
component_listItemLikepost($args)
?>
<?php
$args = array(
    'link_slug' => 'recruit',
    'image_src' => 'b_03.jpg',
    'lead_text' => 'RECRUIT',
    'content' => 'あなたの夢が叶うトータル<br class="md:hidden xl:block">ビューティーサロンで働きませんか？',
);
component_listItemLikepost($args)
?>

    </ul>
  </div>
</div>
</article>

<!-- salon -->
<article class="mt-7 md:mt-8 front-salon__outer">
    <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="w-full md:w-full">
      <div class="front-salon__content">
        <span class="front-salon__lead">SALON</span>
        <div class="front-salon__pic">
          <span class="front-salon__pic--ract"></span>
          <img src='<?php echo get_template_directory_uri(); ?>/img/a_01.jpg' alt="ギッシュのサロン">
        </div>
        <h1 class="front-salon__title"><span class="ttl__large">居心地の良い<br class="max-md:hidden">ギッシュのサロン</span></h1>
        <div class="front-salon__detail--wrap">
          <p class="front-salon__detail typ">リゾートをイメージした店舗はとても居心地の良い空間です。日頃の疲れを癒し、キレイにリフレッシュして笑顔でお帰り頂くようおもてなし致します。<br>小牧市・江南市・北名古屋市・名古屋市でネイルサロンも含め8店舗展開しておりますので、ぜひお近くの店舗までお越しください。</p>
          <div class="front-salon__button">
<?php
$args = array(
    'link_slug' => 'salon',
    'className' => 'button__grd',
    'text' => 'サロン一覧はこちら',
);
component_buttonLink($args)
?>
          </div>
        </div>
      </div>

      </div>
    </div>
</article>



<!-- service -->
<article class="front-service__outer">
    <div class="container-width mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0">
      <div class="w-full md:w-10/12">
      <div class="front-service__content">
        <span class="front-service__lead">SERVICE</span>
        <h1 class="front-service__title"><span class="ttl__medium">あなたに満足していただくために </span></h1>
        </div>
      </div>
      <div class="w-full md:w-11/12 xl:w-10/12">
          <ul class="grid gap-x-grid-gutter grid-cols-1 sm:grid-cols-2 md:grid-cols-2 front-service__list js-fadeup__stagger--list">
<?php
$args = array(
    'number' => '01',
    'title' => 'カスタマーズカード',
    'content' => '初回ご来店時に差し上げるカード。初回ご来店から3度目までの技術料金を割引させて頂きます。',
);
// component_listItemService($args);
?>

          </ul>
        </div>

        </div>


      <div class="front-service__pic js-fadeup__once"><img src='<?php echo get_template_directory_uri(); ?>/img/a_02.jpg' alt="ギッシュのサロン"></div>
      <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0  mt-5">
        <div class="button__wrap">
  <?php
  $args = array(
      'link_slug' => 'message#anc_service',
      'className' => 'button__grd',
      'text' => '詳しくはこちら',
  );
  component_buttonLink($args)
  ?>
            </div>
        </div>

    </div>
</article>


<!-- menu -->
<article class="front-menu__outer">
<div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="w-full">
        <span class="front-menu__lead">MENU</span>
        <h1 class="front-menu__title"><span class="ttl__large">ギッシュこだわりの<br class="sm:hidden">メニュー紹介</span></h1>
      </div>
</div>

<div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
  <div class="w-full sm:w-full md:w-full">
    <ul class="grid gap-x-grid-gutter grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 front-menu__list js-fadeup__stagger--list">
<?php
$args = array(
    'image_src' => 'b_04.jpg',
    'lead_text' => 'CUT',
    'content' => '一人ひとりの骨格に合わせて髪の毛一本一本を切るかのごとく綿密に計算されたカットは、再現性が高くお手入れしやすいと好評です。',
);
component_listItemMenuIntroduction($args);
?>
<?php
$args = array(
    'image_src' => 'b_05.jpg',
    'lead_text' => 'COLOR',
    'content' => 'お客様に合うトレンドのカラーをご提案させて頂きます。流行を取り入れたワンランク上のカラーをお楽しみ下さい。',
);
component_listItemMenuIntroduction($args);
?>
<?php
$args = array(
    'image_src' => 'b_06.jpg',
    'lead_text' => 'TREATMENT',
    'content' => '素敵なヘアスタイルは健康な髪があってこそ。お客様の髪の状態、なりたいイメージに合わせて各種取り揃えております。',
);
component_listItemMenuIntroduction($args);
?>

    </ul>
  </div>
</div>

<div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0  mt-5">
        <div class="button__wrap">
  <?php
  $args = array(
      'link_slug' => 'menu',
      'className' => 'button__grd',
      'text' => 'メニューページはこちら',
  );
  component_buttonLink($args)
  ?>
            </div>
        </div>
</article>




<!-- staff -->
<article class="front-staff__outer">
    <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="w-full md:w-full">
      <div class="front-staff__content">
        <span class="front-staff__lead">STAFF</span>
            <div class="front-staff__pic--frame">
              <ul class="front-staff__list">
                <li><img src='<?php echo get_template_directory_uri(); ?>/img/stf_morikawa.jpg' alt="ギッシュのスタッフ"></li>
                <li><img src='<?php echo get_template_directory_uri(); ?>/img/stf_tamura.jpg' alt="ギッシュのスタッフ"></li>
                <li><img src='<?php echo get_template_directory_uri(); ?>/img/stf_aragaki.jpg' alt="ギッシュのスタッフ"></li>
                <li><img src='<?php echo get_template_directory_uri(); ?>/img/stf_ono.jpg' alt="ギッシュのスタッフ"></li>
                <li><img src='<?php echo get_template_directory_uri(); ?>/img/stf_miyachi.jpg' alt="ギッシュのスタッフ"></li>
                <li><img src='<?php echo get_template_directory_uri(); ?>/img/stf_nauchi.jpg' alt="ギッシュのスタッフ"></li>
              </ul>
            </div>
            <h1 class="front-staff__title"><span class="ttl__large">誰からも愛される<br class="max-md:hidden">ギッシュのスタッフ</span></h1>
            <div class="front-staff__detail--wrap">
              <p class="front-staff__detail typ">ギッシュグループはスタッフが伸び伸び活躍できる自主性を尊重した社風です。明るく元気で、オシャレで礼儀正しく、誰からも好かれ、愛され、たくさんの「ありがとう」を言っていただけるスタッフ育成を目指しています。</p>
              <div class="front-staff__button">
            <?php
            $args = array(
                'link_slug' => 'staff',
                'className' => 'button__grd',
                'text' => 'スタッフ一覧はこちら',
            );
            component_buttonLink($args)
            ?>
              </div>
            </div>
      </div>

      </div>
    </div>
</article>






<?php get_footer(); ?>