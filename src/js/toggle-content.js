import anime from 'animejs/lib/anime.es.js';

/**
 * クリックで開閉するトグルコンテンツ
 * @param {string} elemTitle タイトル部分のクラス名
 * @param {string} elemContent 表示を切り替えるコンテンツのクラス名
 */

export function toggleContent(elemTitle, elemContent) {
  const nodeTitle = document.querySelectorAll(elemTitle);
  const nodeContent = document.querySelectorAll(elemContent);
  let contentHeight = [];
  // animate.jsで付け足すので規定値取得
  const valuePadding = window.getComputedStyle(document.querySelector(elemContent)).getPropertyValue('padding');
  const ms = 300;

  // css へ
  // nodeContent.forEach(node => { node.style.opacity = 0; });

  window.addEventListener('load', () => {
    nodeContent.forEach(node => {
      contentHeight.push (node.clientHeight); // 高さを取得して格納
      node.style.visibility = 'hidden';
      node.style.padding = '0px';
      node.style.height = '0px'; // 高さ0で閉じる
    });
  });

  // nodeContent[i]と連動させるためにfor文で作成
  for (let i = 0; i < nodeTitle.length; i++) {
    nodeTitle[i].index = i;
    nodeTitle[i].addEventListener('mousedown',toggleControl);
    // nodeTitle[i].removeEventListener('mousedown',toggleControl(i, nodeTitle, nodeContent, contentHeight,ms), false)
  }

  function toggleControl(){
    // console.log(this.index,nodeContent[this.index], contentHeight[this.index]);
    const index = this.index;
    nodeTitle[this.index].parentElement.classList.toggle('js-active'); //親要素へのクラス追加
    //heightスタイルを判断して開閉
    if (nodeContent[index].style.height !== '0px') {
      const tl = anime.timeline();
      tl
        .add({
          targets: nodeContent[index],
          height: '0px',
          duration: ms * 0.3,
          easing: 'easeOutCirc',
          begin: function() {
            nodeContent[index].style.overflow = 'hidden';
          },
          complete: function() {
            nodeContent[index].style.overflow = '';
            nodeContent[index].style.visibility = 'hidden';
            nodeContent[index].style.padding = '0px';
          },
        })
        .add({
          targets: nodeContent[index],
          opacity: 0,
          duration: ms * 0.5,
          easing: 'easeOutCirc',
        }, 0);
    } else {
      anime({
        targets: nodeContent[index],
        opacity: 1,
        height: `${contentHeight[index]}px`,
        duration: ms,
        easing: 'easeOutCirc',
        delay: 10,
        begin: function() {
          nodeContent[index].style.overflow = 'hidden';
          nodeContent[index].style.visibility = 'visible';
          nodeContent[index].style.padding = valuePadding;
        },
        complete: function() {
          nodeContent[index].style.overflow = '';
          // nodeContent[index].style.height = ''; // 事前取得値に誤差がでる場合は高さ指定を外す
        },
      });
    }
  }

}
