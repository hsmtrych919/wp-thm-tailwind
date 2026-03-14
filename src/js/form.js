/**
 * パラメータ取得。渡す値に含まれるスペースは「%20」に変換
 * サンプルkeyリスト
 * 送信内容 > sendtype
 * メニュー > menu
 * 方法 > howto
 */
const paramObj = {};
const param = window.location.search.substring(1).split('&');
for (let i = 0; param[i]; i++) {
  const paramVal = param[i].split('=');
  paramObj[paramVal[0]]=decodeURIComponent(paramVal[1]);
}


/**
 * ロード時もしくは変更操作にあわせて、送信内容のチェックと表示コンテンツの切り替え
 * 送信内容のparamKeyは sendtype
 * @param {string} typePrimary input value
 * @param {string} typeSecondary input value
 */
export function switchSendType(typePrimary, typeSecondary) {
  // ロード時
  window.addEventListener('load', () =>{
    const nodeInput = document.querySelectorAll('input[name=input_sendtype]');
    // sendtypeパラメータなければ typePrimary をチェック
    // sendtypeパラメータあれば、該当する項目をチェック
    if ( !paramObj.sendtype ) {
      // console.log('sendtypeなし / ', typePrimary, 'をチェック');
      nodeInput.forEach(node => {
        if (node.value === typePrimary) {
          node.checked = true;
          switchFunc(node.value);
        }
      });
    } else {
      // console.log('sendtypeあり');
      for (let i = 0; i < nodeInput.length; i++) {
        if (paramObj.sendtype === nodeInput[i].value) {
          nodeInput[i].checked = true;
          switchFunc(nodeInput[i].value);
          break;
        }
      }
    }
  });

  // 変更操作時 のコンテンツ切り替え
  const nodeInputAfter = document.querySelectorAll('input[name=input_sendtype]');
  nodeInputAfter.forEach(node => {
    node.addEventListener('change', (event) =>{
      // console.log('change', event.target);
      switchFunc(event.target.value);
    });
  });

  /**
 * 表示コンテンツの切り替え
 * @param {string} value
 */
  function switchFunc(value) {
    const nodePrimary = document.querySelectorAll('.js-switch__primary');
    const nodeSecondary = document.querySelectorAll('.js-switch__secondary');
    if (value === typePrimary) {
      nodePrimary.forEach(node => { node.style.display = ''; });
      nodeSecondary.forEach(node => { node.style.display = 'none'; });
    } else if (value === typeSecondary){
      nodePrimary.forEach(node => { node.style.display = 'none'; });
      nodeSecondary.forEach(node => { node.style.display = ''; });
    }
  }
}



/**
 * パラメータからチェックボックス操作
 * ラジオボタンと querySelectorAll の指定が違う
 * @param {string} inputName input name属性
 * @param {string} paramName パラメータ名。変数で扱いたいのでブラケット記法[]でプロパティにアクセス
 */
export function checkboxByParam(inputName, paramName) {
  const nodeInput = document.querySelectorAll('input[name="'+inputName+'[]"]');
  nodeInput.forEach(node => {
    if (paramObj[paramName] === node.value) {
      node.checked = true;
    }
  });
}


/**
 * パラメータからラジオボタン操作
 * @param {string} inputName input name属性
 * @param {string} paramName パラメータ名。変数で扱いたいのでブラケット記法[]でプロパティにアクセス
 */
export function radioByParam(inputName, paramName) {
  const nodeInput = document.querySelectorAll('input[name="'+inputName+'"]');
  nodeInput.forEach(node => {
    if (paramObj[paramName] === node.value) {
      node.checked = true;
    }
  });
}
