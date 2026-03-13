<?php global $imgPath; ?>
<div class="swiper-front__outer">

  <div class="swiper swiper-front__container">
    <ul class="swiper-wrapper swiper-front__list">
      <li class="swiper-slide">
<a href='<?= home_url('/salon/komaki'); ?>' class="swiper-front__list--item">
    <img src='<?= $imgPath . (is_mobile() ? 'slide_01.jpg' : 'slide_01pc.jpg'); ?>' alt="" class="skip-lazy">
</a>
      </li>
      <li class="swiper-slide">
<a href='<?= home_url('/salon/tokadai'); ?>' class="swiper-front__list--item">
    <img src='<?= $imgPath . (is_mobile() ? 'slide_02.jpg' : 'slide_02pc.jpg'); ?>' alt="" class="skip-lazy">
</a>
      </li>
      <li class="swiper-slide">
<a href='<?= home_url('/staff'); ?>' class="swiper-front__list--item">
    <img src='<?= $imgPath . (is_mobile() ? 'slide_03.jpg' : 'slide_03pc.jpg'); ?>' alt="" class="skip-lazy">
</a>
      </li>
    </ul>
    <div class="swiper-button-prev swiper-front__prev"></div>
    <div class="swiper-button-next swiper-front__next"></div>
  </div>
  <div class="swiper-pagination swiper-front__pagination"></div>
</div>


