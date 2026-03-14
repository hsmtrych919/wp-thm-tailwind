//import defaultExport from "module-name"; or import { exportName } from "module-name";

// フォーム操作
// import {switchSendType, checkboxByParam, radioByParam} from './form';
// switchSendType('お問い合わせ', 'お申し込み');
// checkboxByParam('input_menu', 'menu');
// radioByParam('input_howto', 'howto');

// バリデーション 入力チェック
import {inputValidationInquiry, submitValidationInquiry} from './form-validation';
inputValidationInquiry('input#form_name','input#form_tel','input#form_email', 'textarea#form_inquiry', '#form__group--submit');
submitValidationInquiry('form#form__contact', 'input#form_name','input#form_tel','input#form_email','textarea#form_inquiry', 'input#form_submit', '#form__group--submit');

