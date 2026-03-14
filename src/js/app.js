import { mediaQuerySm, mediaQueryMd, mediaQueryLg, mediaQueryXl, mediaQueryXxl } from './media-query';
import { navDropdown, navMenuMobile } from './header';
import { observer, observerDistant, observerFromTop } from './observer';
import { newsTicker, setWidth_ticker } from './news-ticker';
import { swiperFrontWithThumbnail, swiperFrontArrivals, swiperSingleArrivals } from './swiper';
import { smooth_scroll } from './smooth-scroll';
import { modal_footer, modal_form_notice } from './micromodal';
import { fixedButton, fadeUpContent, staggerFadeupForList, staggerFadeupForFrontStaffList, scrubForFrontPic, ractForHeadlinePic, fadeForSalon } from './gsap';


// 関数同士の干渉を防ぐために、初期化関数を使って実装
class AppInitializer {
  constructor() {
    this.initializationQueue = [];
  }

  addInitializer(name, initFunction) {
    this.initializationQueue.push({ name, initFunction });
  }

  async initialize() {
    for (const { name, initFunction } of this.initializationQueue) {
      try {
        await initFunction();
        // console.log(`${name} initialized successfully`);
      } catch (error) {
        // console.error(`Error initializing ${name}:`, error);
      }
    }
  }
}
const appInitializer = new AppInitializer();


// 各機能の初期化関数を追加

// リンク無効化
appInitializer.addInitializer('Link Ignore', async () => {
  const ignoreLinks = document.querySelectorAll('a.ignore');
  ignoreLinks.forEach(link => {
    link.addEventListener('click', function(event) {
      event.preventDefault();
    });
  });
});

// ドロップダウン
appInitializer.addInitializer('Navigation', async () => {
  navDropdown('.p-nav__item.js-nav--dropdown', '.js-nav--dropdown .p-nav__list--children');
  navMenuMobile();
});

// Observer
appInitializer.addInitializer('Observer', async () => {
  observerFromTop('#front-logo', 'js-initial', '10px', false, true);
  if (mediaQueryLg.matches) {
    observerFromTop('#grobal__header', 'js-scroll', '150px', false, false);
  } else {
    observerFromTop('#grobal__header', 'js-scroll', '30px', false, false);
    observerFromTop('#js-news-ticker', 'js-scroll', '50px', false, false);
  }
});

// ニュースティッカー
appInitializer.addInitializer('News Ticker', async () => {
  newsTicker();
  setWidth_ticker();
});

// Smooth Scroll
appInitializer.addInitializer('Smooth Scroll', async () => {
  smooth_scroll();
});

// swiper
let swipers = [];

function initializeSwipers() {
  console.log('initializeSwipers');
  // 既存のSwiperインスタンスを破棄
  swipers.forEach(swiper => swiper.destroy(true, true));
  swipers = [];

  // 新しいSwiperインスタンスを作成
  swipers.push(swiperFrontWithThumbnail('.swiper-front__container', '.swiper-front__thumbnail', '.swiper-front__pagination', '.swiper-front__next', '.swiper-front__prev'));
  swipers.push(swiperFrontArrivals('.swiper-arrivals-archive__container', '.swiper-arrivals-archive__next'));
  swipers.push(swiperSingleArrivals('.swiper-single-arrivals__container', '.swiper-single-arrivals__pagination', '.swiper-single-arrivals__next', '.swiper-single-arrivals__prev'));
}

appInitializer.addInitializer('Swiper', async () => {
  initializeSwipers();
});

// micromodal
appInitializer.addInitializer('Micromodal', async () => {
  modal_footer();
  modal_form_notice();
});

// gsap
appInitializer.addInitializer('GSAP', async () => {
  fixedButton('#js-fadeButton-pagetop');
});

// DOMContentLoadedイベントで初期化
document.addEventListener('DOMContentLoaded', () => {
  appInitializer.initialize();
});


// swiper リサイズイベント
[mediaQuerySm, mediaQueryMd, mediaQueryLg, mediaQueryXl, mediaQueryXxl].forEach(mq => {
  mq.addEventListener('change', initializeSwipers);
});