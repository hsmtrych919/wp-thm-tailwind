import ScrollHint from 'scroll-hint';

/**
 * コンテンツがはみ出る場合、両橋のシャドウ＋ヒントでスクロールできることを示唆
 * @param {string} elem inner要素クラス名
 */

export function scrollHint(elem) {

  const nodes = document.querySelectorAll(elem);
  nodes.forEach(node => {

    // はみ出る場合は右の影追加
    window.addEventListener('load', () =>{
      /* すべてのリソースの読み込みが完了した時の処理 */
      if (node.scrollWidth - node.clientWidth > 2) {
        node.parentNode.classList.add('js-shadow__after');
      }
    });

    node.addEventListener('scroll', () => {
      const cur = node.scrollLeft;
      if (cur == 0) {
        node.parentNode.classList.remove('js-shadow__before');
      } else {
      // const max = node.scrollWidth - node.parentNode.clientWidth;
        const max = node.scrollWidth - node.clientWidth;
        if ( max - cur <= 1 ) {
          node.parentNode.classList.remove('js-shadow__after');

        } else {
          node.parentNode.classList.add('js-shadow__before');
          node.parentNode.classList.add('js-shadow__after');
        }
      }
    });

  });

  new ScrollHint(elem ,{
    // applyToParents: true, // 親要素内に追加
    offset: 40,
    remainingTime: 4000,
    i18n: {
      scrollable: '横スクロールできます'
    }
  });

}