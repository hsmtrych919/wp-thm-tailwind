import { mediaQuerySm } from './media-query';

export function setMinHeight(elemCheckClass, elemAddstyleClass){

  const elemCheckNodes = document.querySelectorAll(elemCheckClass);
  const elemAddstyleNodes = document.querySelectorAll(elemAddstyleClass);

  function addStyle() {
    elemCheckNodes.forEach((elemCheckNode, index) => {
      const elemCheckHeight = elemCheckNode.clientHeight;
      elemAddstyleNodes[index].style.minHeight = `${elemCheckHeight}px`;
    });
  }

  // 初期化
  window.addEventListener('load', () => {
    if (mediaQuerySm.matches) {
      addStyle();
    }
  });

  // リサイズ時
  window.addEventListener('resize', () => {
    if (mediaQuerySm.matches) {
      addStyle();
    }
  });

}
