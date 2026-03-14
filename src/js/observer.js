

/**
 * Intersection Observer 要素を監視して、指定領域に入ったら処理を実行
 * @param {string} elemMonitor 監視要素
 * @param {string} classNameAdd 追加クラス名 .なし
 * @param {string} offset 単位をつけて cssショートハンド形式で記載。bottom はマイナスが標準
 * @param {boolean} isOnce 追加クラスをそのままにする場合 true
 * @param {boolean} isReverse クラス管理を逆（削除>追加）にする場合 true
 */

export function observer(elemMonitor, classNameAdd, offset, isOnce, isReverse){
  if (!isOnce) isOnce = false;
  if (!isReverse) isReverse = false;

  // 交差を監視する要素
  const nodes = document.querySelectorAll(elemMonitor);
  const options = {
    root: null, // ビューポートをルート要素とする
    rootMargin: offset,
    threshold: 0
  };
  // nodeごとにobserve実行
  const observer = new IntersectionObserver(operateClass, options);
  nodes.forEach(node => { observer.observe(node); });

  /**
* 交差したときに呼び出す関数
* @param entries
* entries は コールバック関数から受け取る引数。オブジェクトの配列
* isIntersecting 監視対象の要素が指定領域に入ったかを判定するプロパティ
**/
  function operateClass(entries) {
    entries.forEach(entry => {
      const nodes = entry.target;
      if (entry.isIntersecting) {
        (!isReverse) ? nodes.classList.add(classNameAdd) : nodes.classList.remove(classNameAdd);
        // console.log('isIntersecting', entry.isIntersecting);
      } else {
        if (!isOnce) {
          (!isReverse) ? nodes.classList.remove(classNameAdd) : nodes.classList.add(classNameAdd);
        }
        // console.log('isIntersecting', entry.isIntersecting);
      }
    });
  }
}



/**
 * Intersection Observer 交差判定要素と操作要素が別の場合
 * @param {string} elemMonitor 監視要素
 * @param {string} elemOperation 操作要素
 * @param {string} classNameAdd 追加クラス名 .なし
 * @param {string} offset 単位をつけて cssショートハンド形式で記載。bottom はマイナスが標準
 * @param {boolean} isOnce 追加クラスをそのままにする場合 true
 * @param {boolean} isReverse クラス管理を逆（削除>追加）にする場合 true
 */

export function observerDistant(elemMonitor, elemOperation, classNameAdd, offset, isOnce, isReverse){
  if (!isOnce) isOnce = false;
  if (!isReverse) isReverse = false;

  // 交差を監視する要素
  const nodes = document.querySelectorAll(elemMonitor);
  const options = {
    root: null, // ビューポートをルート要素とする
    rootMargin: offset,
    threshold: 0
  };
  const observer = new IntersectionObserver(operateClass, options);
  nodes.forEach(node => { observer.observe(node); });

  /**
* 交差したときに呼び出す関数
* @param entries
**/

  function operateClass(entries) {
    entries.forEach(entry => {
      const nodes = document.querySelector(elemOperation);
      if (nodes) {
        if (entry.isIntersecting) {
          (!isReverse) ? nodes.classList.add(classNameAdd) : nodes.classList.remove(classNameAdd);
          // console.log('isIntersecting', entry.isIntersecting);
        } else {
          if (!isOnce) {
            (!isReverse) ? nodes.classList.remove(classNameAdd) : nodes.classList.add(classNameAdd);
          }
          // console.log('isIntersecting', entry.isIntersecting);
        }
      }
    });
  }
}



/**
 * Intersection Observer viewportを拡張して、scrolltopを再現
 * @param {string} elemMonitor 監視要素
 * @param {string} className 追加クラス名 .なし
 * @param {string} offset 単位pxをつけて topにあたる1つだけ指定。
 * @param {boolean} isOnce 追加クラスをそのままにする場合 true
 * @param {boolean} isReverse クラス管理を逆（削除>追加）にする場合 true
 */
export function observerFromTop(elemMonitor, className, offset, isOnce, isReverse){
  if (!isOnce) isOnce = false;
  if (!isReverse) isReverse = false;

  // clientHeight正しく取得するためにロード後実行
  window.addEventListener('load', () =>{

    const nodes = document.querySelectorAll(elemMonitor);
    const options = {
      root: null,
      rootMargin: offset + ' 0px ' + document.body.clientHeight * 1.2 + 'px',
      // offset＋bodyのheightでviewportを拡張。viewportから出たタイミングで isIntersecting が false
      threshold: 1
    };
    const observer = new IntersectionObserver(operateClass, options);
    observer.observe(document.body);

    function operateClass(entries) {
      entries.forEach(entry => {

        if (!entry.isIntersecting ) {
          nodes.forEach(node => {
            (!isReverse) ? node.classList.add(className) : node.classList.remove(className);
          });

          // console.log('isIntersecting', entry.isIntersecting);
        } else {
          if (!isOnce) {
            nodes.forEach(node => {
              (!isReverse) ? node.classList.remove(className) : node.classList.add(className);
            });
          }
          // console.log('isIntersecting', entry.isIntersecting);
        }
      });
    }

  });
}



// /**
//  * optionのrootMarginを複数渡し、メディアクエリで切り替えるVer。%指定での実装で十分かと思うが window.matchMedia の実用も兼ねて作成
//  * 内容はコンテンツのフェードイン。クラス名は関数内で定義済み。
//  * breakpoint追加する場合はオプション、rootMarginの引数、イベントリスナーも追加。サイズは任意
//  * @param {string} offsetDefault
//  * @param {string} offsetmediaQueryMd
//  * @param {boolean} isOnce
//  */

// import { mediaQueryMd, mediaQueryXl } from './media-query';

// export function observerFade( offsetDefault, offsetmediaQueryMd, isOnce){
//   if (!isOnce) isOnce = false;

//   // 交差を監視する要素
//   const nodes = document.querySelectorAll('.js-inview__fade--pre');
//   const options = {
//     root: null, // ビューポートをルート要素とする
//     threshold: 0,
//     rootMargin: offsetDefault,
//     responsive: [
//       {
//         breakpoint: 811, //ブレイクポイント min-width
//         rootMargin: offsetmediaQueryMd,
//       },
//       // {
//       //   breakpoint: 1366,
//       //   rootMargin: 'offsetmediaQueryXl',
//       // }
//     ]
//   };

//   // observe初期化
//   let observer;
//   setObserver(options);

//   // メディアクエリが変化したら observeのリセットと再実行
//   mediaQueryMd.addEventListener('change', () => {
//     nodes.forEach(node => { observer.unobserve(node); });
//     setObserver(options);
//   });

//   // breakpoint追加する場合はリスナー追加
//   // mediaQueryXl.addEventListener('change', () => {
//   //   nodes.forEach(node => { observer.unobserve(node); });
//   //   setObserver(options);
//   // });


//   /**
//  * observer 定義関数
//  * @param {string} options オプション変数
//  */
//   function setObserver(options) {
//     const getOptions = getOptionsFunc(options);
//     // console.log('設定オプション getOptions', getOptions[0]);
//     observer = new IntersectionObserver(operateClass, getOptions[0]);
//     nodes.forEach(node => { observer.observe(node); });
//   }

//   /**
//  * ブレイクポイントに応じてオプション取得（rootMargin上書き）
//  * @param {string} options オプション変数
//  * @returns
//  */
//   function getOptionsFunc(options) {
//     let root = options.root;
//     let rootMargin = options.rootMargin;
//     let threshold = options.threshold;

//     const responsive = options.responsive;
//     // ウィンドウの幅とブレイクポイントを比較してrootMargin 上書き
//     for (let i = 0; i < responsive.length; i++) {
//       if (window.innerWidth >= responsive[i].breakpoint) {
//         rootMargin = responsive[i].rootMargin;
//       }
//     }
//     //IntersectionObserverのオプションを返す
//     // console.log('オプション取得関数 rootMargin', rootMargin);
//     return [
//       {
//         root: root,
//         rootMargin: rootMargin,
//         threshold: threshold
//       },
//     ];
//   }

//   /**
// * 交差したときに呼び出す関数
// * @param entries
// **/
//   function operateClass(entries) {
//     entries.forEach(entry => {
//       const nodes = entry.target;
//       if (entry.isIntersecting) {
//         nodes.classList.add('js-inview__fade');
//       } else {
//         if (!isOnce) {
//           nodes.classList.remove('js-inview__fade');
//         }
//       }
//     });
//   }
// }
