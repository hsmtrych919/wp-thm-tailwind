/**
 * export function お問い合わせ 未入力のバリデート
 * @param {string} name input#idName
 * @param {string} postalcode input#idName
 * @param {string} tel input#idName
 * @param {string} mail input#idName
 * @param {string} inquiry textarea#idName
 * @param {string} submitParent サブミット用input#idName の親要素
 */
export function inputValidationInquiry(name, tel, mail, inquiry, submitParent) {
  blurNameFunc(name, submitParent);
  // blurPostalcodeFunc(postalcode, submitParent);
  blurTelFunc(tel, submitParent);
  blurMailFunc(mail, submitParent);
  blurInquiryFunc(inquiry, 'input[name=input_sendtype]', 'お問い合わせ', submitParent);
}

/**
 * export function 予約 未入力のバリデート
 * @param {string} name input#idName
 * @param {string} tel input#idName
 * @param {string} mail input#idName
 * @param {string} submitParent サブミット用input#idName の親要素
 */
export function inputValidationBooking(name, tel, mail, submitParent) {
  blurNameFunc(name, submitParent);
  blurTelFunc(tel, submitParent);
  blurMailFunc(mail, submitParent);
}



// 関数定義

/**
 * 入力時チェック 名前
 * @param {string} params 名前用input
 * @param {string} parent submitボタンの親要素node
 */
function blurNameFunc(params, parent) {
  // input
  document.querySelector(params).addEventListener('input', (event) =>{
    removeErrorInput(event.target.previousElementSibling);
    removeErrorSubmit(errorMessageClassBySubmit('name'), parent);
  });
  // blur
  document.querySelector(params).addEventListener('blur', (event) =>{
    if (event.target.value !== '') {
      // value あり
      removeErrorInput(event.target.previousElementSibling);
    } else {
      // value なし
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; お名前を入力してください。</span>');
    }
  });
}

/**
 * 入力時チェック 郵便番号
 * @param {string} params 郵便番号用input
 * @param {string} parent submitボタンの親要素node
 */
function blurPostalcodeFunc(params, parent) {
  const regex = /^[0-9０-９]/;
  // input
  document.querySelector(params).addEventListener('input', (event) =>{
    if (event.target.value.match(regex)) {
      // 正規表現 パス
      removeErrorInput(event.target.previousElementSibling);
    } else {
      // 正規表現 アウト
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; 郵便番号を正しく入力してください。</span>');
    }
    removeErrorSubmit(errorMessageClassBySubmit('postalcode'), parent);
    removeErrorSubmit(errorMessageClassBySubmit('address'), parent);
  });
  // blur
  document.querySelector(params).addEventListener('blur', (event) =>{
    if (event.target.value !== '' && event.target.value.match(regex)) {
      // value あり && 正規表現 パス
      removeErrorInput(event.target.previousElementSibling);
    } else if (event.target.value !== '') {
      // value あり && 正規表現 アウト
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; 郵便番号を正しく入力してください。</span>');
    } else {
      // value なし
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; 郵便番号を入力してください。</span>');
    }
  });
}

/**
 * 入力時チェック 電話番号
 * @param {string} params 電話番号用input
 * @param {string} parent submitボタンの親要素node
 */
function blurTelFunc(params, parent) {
  const regex = /^[0-9０-９]/;
  // input
  document.querySelector(params).addEventListener('input', (event) =>{
    if (event.target.value.match(regex)) {
      // 正規表現 パス
      removeErrorInput(event.target.previousElementSibling);
    } else {
      // 正規表現 アウト
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; 電話番号を正しく入力してください。</span>');
    }
    removeErrorSubmit(errorMessageClassBySubmit('tel'), parent);
  });
  // blur
  document.querySelector(params).addEventListener('blur', (event) =>{
    if (event.target.value !== '' && event.target.value.match(regex)) {
      // value あり && 正規表現 パス
      removeErrorInput(event.target.previousElementSibling);
    } else if (event.target.value !== '') {
      // value あり && 正規表現 アウト
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; 電話番号を正しく入力してください。</span>');
    } else {
      // value なし
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; 電話番号を入力してください。</span>');
    }
  });
}

/**
 * 入力時チェック メール
 * @param {string} params メール用input
 * @param {string} parent submitボタンの親要素node
 */
function blurMailFunc(params, parent) {
  const regex = /[!#-9A-~]+@+[a-z0-9]+.+[^.]$/i;
  // input
  document.querySelector(params).addEventListener('input', (event) =>{
    if (event.target.value.match(regex)) {
      // 正規表現 パス
      removeErrorInput(event.target.previousElementSibling);
    } else {
      // 正規表現 アウト
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; メールアドレスを正しく入力してください。</span>');
    }
    removeErrorSubmit(errorMessageClassBySubmit('mail'), parent);
  });
  // blur
  document.querySelector(params).addEventListener('blur', (event) =>{
    if (event.target.value !== '' && event.target.value.match(regex)) {
      // value あり && 正規表現 パス
      removeErrorInput(event.target.previousElementSibling);
    } else if (event.target.value !== '') {
      // value あり && 正規表現 アウト
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; メールアドレスを正しく入力してください。</span>');
    } else {
      // value なし
      removeErrorInput(event.target.previousElementSibling);
      event.target.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; メールアドレスを入力してください。</span>');
    }
  });
}

/**
 * 入力時チェック お問い合わせ
 * 送信内容 input[name=input_sendtype] が変化した場合はパス
 * @param {string} params お問い合わせ用textarea
 * @param {string} sendtypeSelector 判定したいname属性付きセレクター
 * @param {string} sendtypeValue 一致させたいvalue
 * @param {string} parent submitボタンの親要素node
 */
function blurInquiryFunc(params, sendtypeSelector, sendtypeValue, parent) {
// 送信内容（お問い合わせ）の判定処理
  const nodeSendtype = document.querySelectorAll(sendtypeSelector);
  let flagIinquiry = true;
  const stateSendtype = () => {
    for (let i = 0; i < nodeSendtype.length; i++) {
      if (nodeSendtype[i].checked === true && nodeSendtype[i].value === sendtypeValue) {
        flagIinquiry = true;
        break;
      } else {
        flagIinquiry = false;
      }
    }
  };

  // 初回実行
  stateSendtype();
  if (flagIinquiry) {
    // input
    document.querySelector(params).addEventListener('input', (event) =>{
      removeErrorInput(event.target.previousElementSibling);
      removeErrorSubmit(errorMessageClassBySubmit('inquiry'), parent);
    });
    // blur
    document.querySelector(params).addEventListener('blur', errorMessage);
  }
  // 判定内容が変化した場合
  nodeSendtype.forEach(node => {
    node.addEventListener('change', () =>{
      stateSendtype();

      // エラーメッセージあった場合は削除
      removeErrorInput(document.querySelector(params).previousElementSibling);

      if (flagIinquiry) {
        document.querySelector(params).addEventListener('blur', errorMessage);
      } else {
        document.querySelector(params).removeEventListener('blur', errorMessage);
        removeErrorSubmit(errorMessageClassBySubmit('inquiry'), parent);
      }
    });
  });

  function errorMessage() {
    if (this.value !== '') {
      // value あり
      removeErrorInput(this.previousElementSibling);
    } else {
      // value なし
      removeErrorInput(this.previousElementSibling);
      this.insertAdjacentHTML('beforebegin', '<span class="js-error">&#8251; お問い合わせ内容を入力してください。</span>');
    }
  }
}

/**
 * 入力時エラーメッセージ削除
 * @param {string} elem event.target.previousElementSibling を渡す
 */
function removeErrorInput(elem) {
  if (elem && elem.classList.contains('js-error')) elem.remove();
}





/**
 * export function お問い合わせ サブミット時のバリデート。
 * mouseDown だとエラーあっても先に進めてしまうのでclickでイベント登録
 * @param {string} form form#idName
 * @param {string} name input#idName
 * @param {string} postalcode input#idName
 * @param {string} address input#idName
 * @param {string} tel input#idName
 * @param {string} mail input#idName
 * @param {string} inquiry textarea#idName
 * @param {string} submit サブミット用input#idName
 * @param {string} submitParent サブミット用input#idName の親要素
 */
export function submitValidationInquiry( form, name, tel, mail, inquiry, submit, submitParent ) {
  // フラグ
  let flagName = false;
  // let flagPostalcode = false;
  // let flagAddress = false;
  let flagTel = false;
  let flagMail = false;
  let flagInquiry = false;
  // node
  const nodeForm = document.querySelector(form);
  const nodeName = document.querySelector(name);
  // const nodePostalcode = document.querySelector(postalcode);
  // const nodeAddress = document.querySelector(address);
  const nodeTel = document.querySelector(tel);
  const nodeMail = document.querySelector(mail);
  const nodeInquiry = document.querySelector(inquiry);
  const nodeSubmit = document.querySelector(submit);

  nodeSubmit.addEventListener('click', (event) =>{
    event.preventDefault();
    // 入力チェック
    flagName = checkNameFunc(nodeName, nodeSubmit, submitParent);
    // flagPostalcode = checkPostalcodeFunc(nodePostalcode, nodeSubmit, submitParent);
    // flagAddress = checkAddressFunc(nodeAddress, nodeSubmit, submitParent);
    flagTel = checkTelFunc(nodeTel, nodeSubmit, submitParent);
    flagMail = checkMailFunc(nodeMail, nodeSubmit, submitParent);
    // 送信内容「お問合せ」の判定処理 要確認
    flagInquiry = checkInquiryFunc(nodeInquiry, 'input[name=input_sendtype]', 'お問い合わせ', nodeSubmit, submitParent);

    // if ( flagName && flagPostalcode && flagAddress && flagTel && flagMail && flagInquiry ) {
    if ( flagName && flagTel && flagMail && flagInquiry ) {
      nodeForm.submit();
    }
  });
}


/**
 * export function 予約 サブミット時のバリデート
 * mouseDown だとエラーあっても先に進めてしまうのでclickでイベント登録
 * @param {string} form form#idName
 * @param {string} name input#idName
 * @param {string} tel input#idName
 * @param {string} mail input#idName
 * @param {string} submit サブミット用input#idName
 * @param {string} submitParent サブミット用input#idName の親要素
 */
export function submitValidationBooking( form, name, tel, mail, submit, submitParent ) {
  // フラグ
  let flagName = false;
  let flagTel = false;
  let flagMail = false;
  // node
  const nodeForm = document.querySelector(form);
  const nodeName = document.querySelector(name);
  const nodeTel = document.querySelector(tel);
  const nodeMail = document.querySelector(mail);
  const nodeSubmit = document.querySelector(submit);

  nodeSubmit.addEventListener('click', (event) =>{
    event.preventDefault();
    // 入力チェック
    flagName = checkNameFunc(nodeName, nodeSubmit, submitParent);
    flagTel = checkTelFunc(nodeTel, nodeSubmit, submitParent);
    flagMail = checkMailFunc(nodeMail, nodeSubmit, submitParent);

    if ( flagName && flagTel && flagMail ) {
      nodeForm.submit();
    }
  });
}


// 関数定義 実行順に記述

/**
 * submit時の入力チェック
 * @param {string} node チェック対象のnode
 * @param {string} submit submitボタンのnode
 * @param {string} parent submitボタンの親要素node
 * @returns
 */

function checkNameFunc(node, submit, parent) {
  const setClass = errorMessageClassBySubmit('name');
  if (node.value !== '') {
    removeErrorSubmit(setClass, parent);
    return true;
  } else {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; お名前を入力してください。</span>`);
    return false;
  }
}

function checkPostalcodeFunc(node, submit, parent) {
  const setClass = errorMessageClassBySubmit('postalcode');
  const regex = /^[0-9０-９]/;
  if (node.value !== '' && node.value.match(regex)) {
    removeErrorSubmit(setClass, parent);
    return true;
  } else if (node.value !== '') {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; 郵便番号を正しく入力してください。</span>`);
    return false;
  } else {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; 郵便番号を入力してください。</span>`);
    return false;
  }
}

function checkAddressFunc(node, submit, parent) {
  const setClass = errorMessageClassBySubmit('address');
  if (node.value !== '') {
    removeErrorSubmit(setClass, parent);
    return true;
  } else {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; 市区町村を入力してください。</span>`);
    return false;
  }
}

function checkTelFunc(node, submit, parent) {
  const setClass = errorMessageClassBySubmit('tel');
  const regex = /^[0-9０-９]/;
  if (node.value !== '' && node.value.match(regex)) {
    removeErrorSubmit(setClass, parent);
    return true;
  } else if (node.value !== '') {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; 電話番号を正しく入力してください。</span>`);
    return false;
  } else {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; 電話番号を入力してください。</span>`);
    return false;
  }
}

function checkMailFunc(node, submit, parent) {
  const setClass = errorMessageClassBySubmit('mail');
  const regex = /[!#-9A-~]+@+[a-z0-9]+.+[^.]$/i;
  if (node.value !== '' && node.value.match(regex)) {
    removeErrorSubmit(setClass, parent);
    return true;
  } else if (node.value !== '') {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; メールアドレスを正しく入力してください。</span>`);
    return false;
  } else {
    removeErrorSubmit(setClass, parent);
    submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; メールアドレスを入力してください。</span>`);
    return false;
  }
}

/**
 * お問い合わせのチェック
 * 送信内容 input[name=input_sendtype] が変化した場合はパス
 * @param {string} node チェック対象のnode
 * @param {string} sendtypeSelector 判定したいname属性付きセレクター
 * @param {string} sendtypeValue 一致させたいvalue
 * @param {string} submit submitボタンのnode
 * @param {string} parent submitボタンの親要素node
 * @returns
 */
function checkInquiryFunc(node, sendtypeSelector, sendtypeValue, submit, parent) {
  const setClass = errorMessageClassBySubmit('inquiry');
  // 送信内容（お問い合わせ）の判定処理
  const nodeSendtype = document.querySelectorAll(sendtypeSelector);
  let flagIinquiry = true;
  for (let i = 0; i < nodeSendtype.length; i++) {
    if (nodeSendtype[i].checked === true && nodeSendtype[i].value === sendtypeValue) {
      flagIinquiry = true;
      break;
    } else {
      flagIinquiry = false;
    }
  }

  if ( flagIinquiry ) {
    if (node.value !== '') {
      removeErrorSubmit(setClass, parent);
      return true;
    } else {
      removeErrorSubmit(setClass, parent);
      submit.insertAdjacentHTML('beforebegin', `<span class="${setClass}">&#8251; お問い合わせ内容を入力してください。</span>`);
      return false;
    }
  } else {
    removeErrorSubmit(setClass, parent);
    return true;
  }
}



/**
 * submit時エラーメッセージ削除
 * @param {string} removeClass メッセージspanタグのクラス名
 * @param {string} selector submitボタンの親要素node
 */
function removeErrorSubmit(removeClass, selector) {
  const nodeGroup = document.querySelector(selector);
  // .children ではHTMLCollection（配列ではない）で取得されるので配列化
  const nodeGroupArr = [...nodeGroup.children];

  nodeGroupArr.forEach(elem => {
    if (elem.classList.value === removeClass) elem.remove();
    // if (elem.classList.value === removeClass){
    //   console.log(elem.classList.value, '削除');
    // }
  });
}

/**
 * サブミット時のエラーメッセージnodeに付与するクラス
 * @param {string} params name・postalcode・address・tel・mail・inquiry いずれか記入
 * @returns
 */
function errorMessageClassBySubmit(params) {
  if (params == 'name') {
    return 'js-error_submit name';
  } else if (params == 'postalcode'){
    return 'js-error_submit postalcode';
  } else if (params == 'address'){
    return 'js-error_submit address';
  } else if (params == 'tel'){
    return 'js-error_submit tel';
  } else if (params == 'mail'){
    return 'js-error_submit mail';
  } else if (params == 'inquiry'){
    return 'js-error_submit inquiry';
  }
}


// export function validation_submit_mwform($checkName, $checkTel, $checkkMail, $checkInquiry, $checkSubmit) {
//   var chkSubmitName = false;
//   var chkSubmitTel = false;
//   var chkSubmitMail = false;
//   var chkSubmitInquiry = false;
//   // submit
//   $('form').submit(function() {
//     if(!chkSubmitName || !chkSubmitTel || !chkSubmitMail || !chkSubmitInquiry ){
//       return false;
//     }
//   });
//   $($checkSubmit).on('click', function(){
//     chkSubmitName = func_name($checkName);
//     chkSubmitTel = func_tel($checkTel);
//     chkSubmitMail = func_mail($checkkMail);
//     // お問い合わせ内容の有無
//     if ($('input[name=送信内容]:checked').val() === "お問い合わせ") {
//       if($($checkInquiry).val() !== ""){
//         $('input#form_submit').prevAll('span.js-error_submit.detail').remove();
//         chkSubmitInquiry = true;
//       }else {
//         $('input#form_submit').prevAll('span.js-error_submit.detail').remove();
//         $('input#form_submit').before('<span class="js-error_submit detail">&#8251; お問い合わせ内容を入力してください。</span>');
//         chkSubmitInquiry = false;
//       }
//     } else {
//       chkSubmitInquiry = true;
//     }
//   });
// }
