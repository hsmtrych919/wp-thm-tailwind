<?php ob_start(); ?>
<div class="toolbar" id="grobal__nav--toolbar">
    <button type="button" class="toolbar__button"><span class="toolbar__line"></span></button>
    <span class="toolbar__subtitle">MENU</span>
  </div>


  <nav class="nav" id="grobal__nav">
    <ul class="nav__list">
      <li class="nav__item lg:hidden"><a class="nav__button" href='<?php echo home_url(''); ?>'>ホーム</a></li>
      <li class="nav__item"><a class="nav__button" href='<?php echo home_url('/message'); ?>'>メッセージ</a></li>
      <li class="nav__item nav__item--salon js-nav--dropdown" ><a class="nav__button" href='<?php echo home_url('/salon'); ?>'>サロン
<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="nav__item--salon-icon">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
</svg>
    </a>
        <ul class="nav__list--children">
          <li class="nav__item"><a class="nav__button--children" href='<?php echo home_url('/salon/kitatoyama'); ?>'>guiches 北外山店</a></li>
          <li class="nav__item"><a class="nav__button--children" href='<?php echo home_url('/salon/komaki'); ?>'>guiches イオン小牧店</a></li>
          <li class="nav__item"><a class="nav__button--children" href='<?php echo home_url('/salon/tokadai'); ?>'>guiches 桃花台店</a></li>
          <li class="nav__item"><a class="nav__button--children" href='<?php echo home_url('/salon/konan'); ?>'>guiches 江南店</a></li>
          <li class="nav__item"><a class="nav__button--children" href='<?php echo home_url('/salon/kitanagoya'); ?>'>guiches 北名古屋店</a></li>
        </ul>
      </li>
      <li class="nav__item"><a class="nav__button" href='<?php echo home_url('/menu'); ?>'>メニュー</a></li>
      <li class="nav__item lg:hidden"><a class="nav__button" href='<?php echo home_url('/campaign'); ?>'>キャンペーン</a></li>
      <li class="nav__item"><a class="nav__button" href='<?php echo home_url('/staff'); ?>'>スタッフ</a></li>
      <li class="nav__item"><a class="nav__button" href='<?php echo home_url('/style'); ?>'>スタイル</a></li>
      <li class="nav__item"><a class="nav__button" href='<?php echo home_url('/company'); ?>'>会社概要</a></li>
      <li class="nav__item"><a class="nav__button" href='<?php echo home_url('/form-contact'); ?>'>お問い合わせ</a></li>
      <li class="nav__item nav__item--recruit"><a class="nav__button nav__button--recruit" href='<?php echo home_url('/recruit'); ?>'>採用情報</a></li>
      <li class="nav__item--close" id="grobal__nav--close">閉じる</li>
    </ul>
  </nav>