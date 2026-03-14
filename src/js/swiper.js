
// CDN経由でSwiperを利用するため、インポートを削除
// import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';
// Swiper.use([Navigation, Pagination, Autoplay]);

// CDN利用によるリントエラー対策として グローバル変数を宣言
/* global Swiper */

/**
 * サムネイル付きメインスライド
 * @param {string} container スライドのクラス名
 * @param {string} containerThumbnail サムネイルのクラス名
 * @param {string} pagination ページネーションのクラス名
 * @param {string} next 進むボタンのクラス名
 * @param {string} prev 戻るボタンのクラス名
 */
export function swiperFrontWithThumbnail(container,containerThumbnail,pagination,next,prev){
  // サムネイル
  const optionThumbnail = {
    slidesPerView: 3,
    spaceBetween: 16,
    watchSlidesProgress: true,
    clickable: true,
  };
  const swiperThumbs = new Swiper(containerThumbnail, optionThumbnail);

  // メイン
  const option = {
    loop: true,
    autoplay: {
      delay: 2000, // 次のスライドに切り替わるまでの時間（ミリ秒）
      disableOnInteraction: false, // ユーザー操作後に自動再生を止める
    },
    speed: 400,
    slidesPerView: 1,
    spaceBetween: 0,
    loopAdditionalSlides: 1,
    pagination: {
      el: pagination,
      clickable: true,
      type: 'bullets' // bullets || fraction || progressbar
    },
    navigation: {
      nextEl: next,
      prevEl: prev,
    },
    thumbs: {
      swiper: swiperThumbs,
    },
    breakpoints: {
      // 576: {
      // },
      811: {
        pagination: {
          el: null,
        }
      },
      // 1025: {
      // }
      1366: {
        centeredSlides: true,
        slidesPerView: 1.15,
        pagination: {
          el: null,
        }
      }
    }
  };
  const mainSwiper = new Swiper(container, option);

  // サムネイルクリック時のイベントリスナーを追加
  const thumbnails = document.querySelectorAll(`${containerThumbnail} .swiper-slide`);
  thumbnails.forEach((thumb, index) => {
    thumb.addEventListener('click', () => {
      mainSwiper.slideToLoop(index);
    });
  });

  return { mainSwiper, swiperThumbs };
}

/**
 * 生体情報一覧のスライド
 * @param {string} container スライドのクラス名
 * @param {string} pagination ページネーションのクラス名
 * @param {string} next 進むボタンのクラス名
 * @param {string} prev 戻るボタンのクラス名
 */

export function swiperFrontArrivals(container, next){
  const option = {
    loop: true,
    autoplay: {
      delay: 3500, // 次のスライドに切り替わるまでの時間（ミリ秒）
      disableOnInteraction: false, // ユーザー操作後に自動再生を止める
    },
    speed: 400,
    slidesPerView: 1.35,
    spaceBetween: 16,
    navigation: {
      nextEl: next,
    },
    breakpoints: {
      0: {
        // navigation: false,
        // autoplay:false,
      },
      576: {
        slidesPerView: 3.35,
        // navigation: false,
        // autoplay:false,
      },
      // 811: {
      //   slidesPerView: 3.35,
      // },
      1025: {
        spaceBetween: 20,
        slidesPerView: 3.5,
        // navigation: false,
        // loop: true,
      },
      1280: {
        spaceBetween: 20,
        slidesPerView: 4.3,
        // navigation: false,
        // loop: true,
      }
    },
  };
  // new Swiper(container, option);
  return new Swiper(container, option);
}



/**
 * 生体情報シングルのスライド
 * @param {string} container スライドのクラス名
 * @param {string} pagination ページネーションのクラス名
 * @param {string} next 進むボタンのクラス名
 * @param {string} prev 戻るボタンのクラス名
 */
export function swiperSingleArrivals(container,pagination,next,prev){
  const option = {
    loop: true,
    autoplay: {
      delay: 2000, // 次のスライドに切り替わるまでの時間（ミリ秒）
    },
    speed: 400,
    slidesPerView: 1,
    spaceBetween: 0,
    pagination: {
      el: pagination,
      clickable: true,
      type: 'bullets'
    },
    navigation: {
      nextEl: next,
      prevEl: prev,
    },
  };
  // new Swiper(container, option);
  return new Swiper(container, option);
}



/**
 * 基本的なスライド
 * @param {string} container スライドのクラス名
 * @param {string} pagination ページネーションのクラス名
 * @param {string} next 進むボタンのクラス名
 * @param {string} prev 戻るボタンのクラス名
 */

export function swiperSlideBasic(container,pagination,next,prev){
  const option = {
    // loop: true,
    // autoplay: true,
    // speed: 400,
    slidesPerView: 1,
    spaceBetween: 0,
    // pagination
    pagination: {
      el: pagination,
      clickable: true,
      type: 'bullets' // bullets || fraction || progressbar
    },
    // Navigation arrows
    navigation: {
      nextEl: next,
      prevEl: prev,
    },
  };
  new Swiper(container, option);
}

