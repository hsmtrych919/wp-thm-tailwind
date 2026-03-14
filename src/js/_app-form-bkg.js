// import 文を使って モジュール （.jsファイル）を読み込み

import {checkboxByParam, radioByParam} from './form';
checkboxByParam('input_menu', 'menu');
radioByParam('input_salon', 'salon');


import {inputValidationBooking, submitValidationBooking} from './form-validation';
inputValidationBooking('input#form_name','input#form_tel','input#form_email', '#form__group--submit');

submitValidationBooking('form#form__rsv02','input#form_name','input#form_tel','input#form_email','input#form_submit', '#form__group--submit');

