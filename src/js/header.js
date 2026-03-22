import anime from 'animejs/lib/anime.es.js';
import { mediaQueryLg, mediaQueryXl } from './media-query';



/**
 * スマホ時ナビゲーション展開
 */
export function navMenuMobile() {
  // オーバーレイ要素作成
  const nodeHeader = document.querySelector('#grobal__header');
  nodeHeader.insertAdjacentHTML('beforeend', '<div class="nav__overlay" id="grobal__nav--overlay"></div>');

  const nodeNav = document.querySelector('#grobal__nav');
  const nodeToolbar = document.querySelector('#grobal__nav--toolbar');
  const nodeToolbarLine = document.querySelector('button .toolbar__line');
  const nodeBtn = document.querySelectorAll('#grobal__nav a[class^="nav__"]');
  const nodeBtnClose = document.querySelector('#grobal__nav--close');
  const nodeOverlay = document.querySelector('#grobal__nav--overlay');
  const nodeBody = document.querySelector('body');

  nodeToolbar.setAttribute('data-navmenu', 'close');

  const triggerArr = [nodeToolbar, ...nodeBtn, nodeBtnClose, nodeOverlay];

  // イベントリスナー登録 ブレークポイントが切り替わったタイミングで関数呼び出し
  mediaQueryLg.addEventListener('change', openFunc );
  // 初期化
  openFunc(mediaQueryLg);


  function openFunc(mediaQuery) {
    triggerArr.forEach(node => {
      if (!mediaQuery.matches) {
        node.addEventListener('mousedown', menuEvent);
      } else {
        node.removeEventListener('mousedown', menuEvent);
      }
    });
  }

  function menuEvent() {
    if (nodeToolbar.getAttribute('data-navmenu') == 'open') {
      // console.log('クローズ処理');
      nodeToolbar.setAttribute('data-navmenu', 'close');
      // animeJs
      const tlClose = anime.timeline();
      tlClose
        .add({
          targets: nodeNav,
          top: '-100px',
          duration: 350,
          easing: 'easeOutCirc',
        }, 0)
        .add({
          targets: nodeNav,
          opacity: 0,
          duration: 250,
          easing: 'easeOutCirc',
          complete: function() {
            nodeNav.style.display = 'none';
          },
        }, 0)
        .add({
          targets: nodeNav,
          height: '0%',
          delay: 250,
          duration: 10,
          easing: 'easeOutCirc',
        }, 0)
        .add({
          targets: nodeOverlay,
          opacity: 0,
          duration: 250,
          easing: 'easeOutCirc',
          complete: function() {
            nodeOverlay.style.display = 'none';
            nodeHeader.classList.remove('js-open');
          },
        }, 0);
      nodeToolbarLine.classList.remove('js-open');
      nodeBody.style.overflow = '';

    } else {
      // console.log('オープン処理');
      nodeToolbar.setAttribute('data-navmenu', 'open');
      // animeJs
      const tlOpen = anime.timeline();
      tlOpen
        .add({
          targets: nodeOverlay,
          opacity: 1,
          duration: 100,
          easing: 'easeOutCirc',
          begin: function() {
            nodeOverlay.style.display = 'block';
          },
        }, 0)
        .add({
          targets: nodeNav,
          height: '100%',
          duration: 0,
          easing: 'easeOutCirc',
        }, 0)
        .add({
          targets: nodeNav,
          top: '0px',
          duration: 350,
          easing: 'easeOutCirc',
        }, 0)
        .add({
          targets: nodeNav,
          opacity: 1,
          duration: 350,
          easing: 'easeOutCirc',
          begin: function() {
            nodeNav.style.display = 'block';
          },
        }, 0);

      nodeHeader.classList.add('js-open');
      nodeToolbarLine.classList.add('js-open');
      nodeBody.style.overflow ='hidden';
    }
  }


}




/**
 * ドロップダウンメニュー。開閉はクラスで管理
 * @param {string} elemHover hover対象要素
 * @param {string} elemDropdown ドロップダウン要素
 */
export function navDropdown(elemHover, elemDropdown){

  const nodesHover = document.querySelector(elemHover);
  const nodesDropdown = document.querySelector(elemDropdown);

  // 初期化
  window.addEventListener('load', () => {
    setDropdown();
  });

  // リサイズ時
  mediaQueryLg.addEventListener('change', (event) => {
    if (event.matches) {
      nodesDropdown.style.display = 'none';
      nodesDropdown.style.opacity = 0;
    } else {
      nodesDropdown.style.display = 'block';
      nodesDropdown.style.opacity = 1;
    }
    setDropdown();
  });

  function setDropdown() {
    // 要素内での遷移に反応させないため mouseover/out でなく mouseenter/leave 使用
    if (mediaQueryLg.matches) {
      nodesHover.addEventListener('mouseenter', addClass);
      nodesHover.addEventListener('mouseleave', removeClass);
    } else {
      nodesHover.removeEventListener('mouseenter', addClass);
      nodesHover.removeEventListener('mouseleave', removeClass);
    }
  }

  function addClass() {
    nodesDropdown.style.display = 'block';
    setTimeout(() => {
      nodesDropdown.classList.add('js-active');
    }, 10);
  }

  function removeClass() {
    nodesDropdown.classList.remove('js-active');
    setTimeout(() => {
      nodesDropdown.style.display = 'none';
    }, 250);
  }
}




/**
 * 上部ヘッダーの収納と再表示
 * @param {number} offsetPc
 * @param {number} offsetSp
 */
export function headerScrolled(offsetPc, offsetSp ) {

  let scrollOffset = 0;
  let scrollCurrent = 0;
  let scrollDiff = 0;
  const scrollDiffLength = 25;
  const offsetPcExtra = 500;
  const nodeHeader = document.querySelector('#grobal__header');
  const nodeNav = document.querySelector('#grobal__nav');

  window.addEventListener('scroll', () => {
    if (nodeNav) scrollFunc();
  });


  function scrollFunc() {
    //window.scrollYは、IE非対応のため pageYOffsetが無難
    scrollCurrent = window.pageYOffset || document.documentElement.scrollTop;
    scrollDiff = scrollOffset - scrollCurrent;

    if (window.innerWidth > 810) {
      // pc
      (scrollCurrent >= offsetPc) ? nodeNav.classList.add('js-scroll--pre') : nodeNav.classList.remove('js-scroll--pre');
      (scrollCurrent >= offsetPc + offsetPcExtra) ? nodeNav.classList.add('js-scroll') : nodeNav.classList.remove('js-scroll');
    } else {
      // sp
      if (scrollCurrent >= offsetSp) {
        if (scrollCurrent > scrollOffset) {
          nodeHeader.classList.add('js-scroll');
          nodeHeader.classList.remove('js-scroll--up');
        } else if (scrollDiff >= scrollDiffLength ) {
          nodeHeader.classList.add('js-scroll--up');
        }
      } else {
        nodeHeader.classList.remove('js-scroll', 'js-scroll--up');
      }
    }
    scrollOffset = scrollCurrent;
  }
}
