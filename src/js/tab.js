/**
 * tab_switch タブ切り替え
 * @param {string} classList
 * @param {string} classContent
 */
export function tabSwitch(classList,classContent) {

  document.addEventListener('DOMContentLoaded', function(){
    // タブ リストアイテムの NodeList 取得
    const tabs = document.querySelectorAll(classList);
    // タブ コンテンツの NodeList 取得
    const content = document.querySelectorAll(classContent);

    // Nodelistは配列風オブジェクト。[...Array]で配列に変換
    const tab_nodes = [...tabs];
    const content_nodes = [...content];


    // タブのリストアイテムを取得して addEventListener
    for (let i = 0; i < tabs.length; i++) {
      tab_nodes[i].addEventListener('mousedown', tabSwitch, false);
    }


    // イベント関数
    function tabSwitch(){

      // activeクラスを全削除してから、対象のリストアイテム(this)にクラス追加
      tab_nodes.forEach(function (element) {
        element.classList.remove('js-active');
      });
      this.classList.add('js-active');

      // クラスの全削除、追加をタブコンテンツに対して実行
      content_nodes.forEach(function (element) {
        element.classList.remove('js-active');
      });

      // 対象のリストアイテム(this)の順番を取得して、クラス追加に活用
      const index = tab_nodes.indexOf(this);
      content_nodes[index].classList.add('js-active');

    }

  });

}