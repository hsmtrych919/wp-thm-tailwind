<article class="">
  <div class="c-headline-pic__outer">
    <div class="l-row--container c-gutter__row justify-start">
      <div class="c-headline-pic__frame js-headline-frame" >
        <h1 class="c-headline-pic__title c-headline-pic__title--long"><span class="c-headline-pic__typ--for-gsap">COMPANY</span></h1>
        <div class="c-headline-pic__pic js-headline-pic" >
        <span class="c-headline-pic__pic--ract"></span>
        <img src='<?php echo get_template_directory_uri(); ?>/img/c_company.jpg' alt="COMPANY">
        </div>
        <div class="c-headline-pic__detail c-headline-pic__typ--for-gsap">
          <p class="c-typ">トータルビューティーサロンを愛知県小牧市・江南市・北名古屋市・名古屋市で8店舗展開中。ギッシュの会社概要と沿革。</p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-7 md:mt-8">
    <div class="l-row--container c-gutter__row p-company__frame">
    <div class="c-col--12 c-col__sm--12 c-col__lg--8">

      <section class="">
      <div class="l-row--container">
          <div class="c-col--12">
            <h2 class="p-salonlist__title "><span class="c-ttl__small"><span class="text-clr1">#</span> 会社概要</span></h2>
          </div>
        </div>
        <div class="l-row--container p-company__content justify-start">
        <div class="c-col--12">
          <dl class="p-company-detail__dl">
      <?php
      $data = array(
      array('dt' => '会社名', 'dd' => '有限会社 ギッシュ'),
      array('dt' => '代表者名', 'dd' => '丹羽 英之'),
      array('dt' => '住所', 'dd' => '小牧市大字北外山字外出2925-1（guiches 北外山店）'),
      array('dt' => 'TEL', 'dd' => '0568-41-1008'),
      array('dt' => '設立', 'dd' => '2003年 6月21日'),
      array('dt' => '資本金', 'dd' => '300万円'),
      array('dt' => '年商', 'dd' => '2億円'),
      array('dt' => '従業員数', 'dd' => '60名'),
      array('dt' => '事業内容', 'dd' => '美容、ネイルサロン、エステサロン、アイラッシュ'),
      array('dt' => '店舗一覧', 'dd' => 'guiches 北外山店、guiches イオン小牧店、guiches 江南店、guiches 桃花台店、guiches 北名古屋店、SOHO new york 小牧店、SOHO new york 名古屋東店、Nail Quick イオン小牧店'),
      );

      foreach ($data as $index => $item) {
      echo '<div class="p-company-detail__dl--row">';
      if ($index === 0) {
          echo '<dt class="p-company-detail__dt--first">' . $item['dt'] . '</dt>';
      } else {
          echo '<dt>' . $item['dt'] . '</dt>';
      }
      echo '<dd>' . $item['dd'] . '</dd>';
      echo '</div>';
      }
      ?>
          </dl>
        </div>
          </div>
      </section>
      <section class="mt-7 md:mt-8">
      <div class="l-row--container ">
          <div class="c-col--12">
            <h2 class="p-salonlist__title "><span class="c-ttl__small"><span class="text-clr1">#</span> 沿革</span></h2>
          </div>
        </div>
        <div class="l-row--container  p-company__content justify-start">
        <div class="c-col--12">
          <dl class="p-company-detail__dl">
      <?php
      $data = array(
      array('dt' => '1991年10月', 'dd' => '1号店OPEN　布袋店'),
      array('dt' => '1992年10月', 'dd' => '1号店改装　布袋店'),
      array('dt' => '1994年4月', 'dd' => '2号店OPEN　師勝店'),
      array('dt' => '1998年3月', 'dd' => '3号店OPEN　桃花台店'),
      array('dt' => '1999年2月', 'dd' => '4号店OPEN　江南店'),
      array('dt' => '2000年12月', 'dd' => '2号店改装　師勝店'),
      array('dt' => '2002年1月', 'dd' => '1号店close　師勝店'),
      array('dt' => '2002年2月', 'dd' => '5号店OPEN　北名古屋店'),
      array('dt' => '2005年7月', 'dd' => '6号店OPEN　小牧イオン店'),
      array('dt' => '2006年5月', 'dd' => '3号店改装　桃花台店'),
      array('dt' => '2006年6月', 'dd' => '2号店close　師勝店'),
      array('dt' => '2006年7月', 'dd' => '5号店改装　北名古屋店'),
      array('dt' => '2007年12月', 'dd' => 'ネイルサロン1号店OPEN　小牧イオン店'),
      array('dt' => '2009年4月', 'dd' => '7号店OPEN　北外山店'),
      );

      foreach ($data as $index => $item) {
      echo '<div class="p-company-detail__dl--row">';
      if ($index === 0) {
          echo '<dt class="p-company-detail__dt--first">' . $item['dt'] . '</dt>';
      } else {
          echo '<dt>' . $item['dt'] . '</dt>';
      }
      echo '<dd>' . $item['dd'] . '</dd>';
      echo '</div>';
      }
      ?>
          </dl>
        </div>
          </div>
      </section>
    </div>
    <div class="c-col__lg--4  c-gutter__md--left hidden lg:block">
      <div class="p-company__thumbnail">
        <img src='<?php echo get_template_directory_uri(); ?>/img/a_03.jpg' alt="COMPANY">

      </div>
      </div>
    </div>
  </div>

</article>

