
// レスポンシブ対応 サンプルコード
// ブレークポイント設定
import { mediaQueryMd } from './media-query';
// イベントリスナー登録 ブレークポイントが切り替わったタイミングで実行
// addEventListnerの第2引数には関数自体を指定。引数なし
mediaQueryMd.addEventListener('change', listener);
// 初期化
listener(mediaQueryMd);
// イベント関数
function listener(event) {
  if (event.matches) {
    console.log('メディアクエリmdサイズ以上 ブレークポイント用処理');
  } else {
    console.log('メディアクエリmdサイズ以下 ブレークポイント用処理');
  }
}


//スクロール量
//window.scrollYは、IE非対応のため pageYOffsetが無難
scrollCurrent = window.pageYOffset || document.documentElement.scrollTop;



window.addEventListener('load', () =>{
  /* すべてのリソースの読み込みが完了した時の処理 */
});
window.addEventListener('scroll', () =>{
  /* スクロールされた時の処理 */
});
elem.addEventListener('change', () =>{
  /* 値または状態が変更された時の処理。 */
  /* inputタグではblurと同じタイミングで発生。リアルタイム希望の場合は'input'使用 */
});

elem.addEventListener('mouseover', function(){
  /* マウスカーソルが被さった時の処理 */
});
elem.addEventListener('mouseout', function(){
  /* マウスカーソルが離れた時の処理 */
});
elem.addEventListener('click', function(){
  /* クリックされた時の処理 */
});
elem.addEventListener('mousedown', function(){
  /* マウスが押された時の処理。clickイベントよりも速く実行 */
  /* イベントが発生する順番は、 mousedown -> mouseup -> click の順番 */
  // メモ: click イベントとの違いは、 click イベントが完全なクリック操作の後、つまり、同じ要素内でマウスのボタンが押されて離された後で発行されることです。 mousedown はボタンが最初に押された時点で発行されます。
});






const container = document.querySelector('.main-container');
const loading = document.querySelector('.pace');

setTimeout(() => {
  container.style.visibility = 'visible';
  container.style.opacity = 1;
}, 10);



// nodeの更新
foo.insertAdjacentHTML('beforebegin', '<div></div>');
// beforebegin: 指定ノードの前、afterend: 指定ノードの後、afterbegin: 指定ノードの子要素リストの先頭、beforeend: 指定ノードの子要素リストの末尾

// 属性の更新
foo.hasAttribute() foo.getAttribute() foo.setAttribute() foo.removeAttribute()


// 文字列の中で変数を使う場合はバッククウォート＋${変数名}
`<span class="${setClass}"></span>`
submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">テキスト</span>`);



// アニメーションライブラリ anime.js
// overflow,display など数値化できないプロパティはアニメーション不可
// begin: function ,complete: functio を活用
// gsapライブラリはMITライセンスでない。課金サイトでなければ無料のようだが、念のため標準装備からは除外。必要に応じて使用
import anime from 'animejs/lib/anime.es.js';
// 基本
anime({
  targets: hghg,
  translateX: 500,
  rotate: '90deg',
  backgroundColor: '#00f',
  delay: 1000,
  duration: 2000,
  easing: 'easeOutCubic',
begin: function() {
  hghg.style.overflow = 'hidden';
  hghg.style.display = 'block';
},
});

// タイムラインを生成
const tl = anime.timeline();
tl
  .add({
    targets: hghg,
    height: '120px',
  })
  .add({
    targets: hghg,
    rotate: '90deg',
    duration: 3000,
  },0) // 開始タイミングのオフセット（絶対値）
  .add({
    targets: hghg,
    translateX: 500,
  }, '+=1000') //開始タイミングのオフセット（相対値）
  .add({
    targets: hghg,
    rotate: '90deg',
    backgroung: '#00f',
    duration: 500,
  }, '-=1000');



// observer threshold利用

  // observer
  const options = {
    root: null, // ビューポートをルート要素とする
    rootMargin: '300px 0px 0px' ,
    // threshold: 0
    threshold: [0, 0.5, 1]
  };
  // observe初期化
  let observer;
  setObserver(options);

  function setObserver(options) {
    observer = new IntersectionObserver(operateClass, options);
    observer.observe(nodeSlide);
  }

  function operateClass(entries) {
    entries.forEach(entry => {
      const nodes = entry.target;
      if (window.innerWidth > 809) {
        classFuncPc(entry);
      } else {
        console.log('sp');
      }
    });
  }

  function classFuncPc(entry) {
    if (entry.isIntersecting) {
      if (entry.intersectionRatio >= 0.8 ) {
        console.log('pre クラス削除 ', entry.isIntersecting, entry.intersectionRatio);
        console.log('クラス削除 ', entry.isIntersecting, entry.intersectionRatio);
      } else if (entry.intersectionRatio >= 0.25){
        console.log('pre クラス追加  ', entry.isIntersecting, entry.intersectionRatio);
      }
    } else {
      console.log('pre クラスなければ追加 ', entry.isIntersecting, entry.intersectionRatio);
      console.log('クラス追加 ', entry.isIntersecting, entry.intersectionRatio);
    }
  }
