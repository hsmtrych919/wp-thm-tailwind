<?php
header('X-FRAME-OPTIONS: SAMEORIGIN');
header('Content-Type: text/html; charset=UTF-8');
//CSRF対策のワンタイムトークン発行
$randomNumber = openssl_random_pseudo_bytes(16);
$token = bin2hex($randomNumber);
?>

<article class="">
<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row jc__start">
      <div class="c-headline__frame" >
        <h1 class="c-headline__title c-headline__title--long"><span class="">CONTACT</span></h1>
        <div class="c-headline__detail">
    <p class="">
    <span class="c-ttl__xsmall clr__3"><span class="clr__1">#</span> お問合せフォーム</span></p>
        </div>
      </div>
    </div>
  </div>

  <div class="l-container">
  <div class="l-row--container c-gutter__row">
    <p class="c-col--12 c-col__sm--10  c-typ clr__1 tac__sm">本フォームはお問い合わせ専用でございます。<br>ご予約は各ページの予約ボタンからお願いいたします。</p>
  </div>

  <div class="l-row--container mt__3 mt__4--md">
    <div class="c-col--12 c-col__md--10 c-col__lg--9 c-col_xl--8">
      <ul class="p-entrystep">
      <?php component_listItemEntrystep('input', '01', '入力画面', 'is-active'); ?>
      <?php component_listItemEntrystep('confirm', '02', '内容確認'); ?>
      <?php component_listItemEntrystep('send', '03', '送信完了'); ?>
      </ul>

    </div>
  </div>
  <div class="l-row--container c-gutter__row">
    <p class="c-col--12 c-col__sm--10 p-entrystep__caption">下記項目にご入力いただき<br class="hide__sm--up">「内容を確認する」ボタンを押してください。<br class="hide__sm--up">ご確認後、入力内容を送信します。</p>
  </div>

  <div class="l-row--container"><div class="c-col--12 c-col__md--10 c-col__lg--9 c-col_xl--8">
    <div class="p-form">

<!-- form -->
<form class="h-adr" method="POST" action="<?php echo home_url( '/' ); ?>form-contact/form-contact-chk" id="form__contact">
<?php
//トークンをSESSIONに格納
$_SESSION['input_token'] = $token;
//トークンを POSTで送信
echo '<input name="input_token" type="hidden" value="'.$token.'">';
?>


<!-- 送信内容 -->
<div class="p-form__group">
<div class="p-form__ttl--radio"><span class="c-form__ttl">送信内容</span></div>
<div class="p-form__input">
<?php
$sendtypeCat = ['お問い合わせ' ];

foreach( $sendtypeCat as $index => $sendtype){
  $index = ++$index;
echo '<span class="p-form__radio-field vertical-item"><label for="form_sendtype-'.$index.'">';
echo '<input type="radio" name="input_sendtype" value="'.$sendtype.'" id="form_sendtype-'.$index.'" checked >';
echo '<span class="p-form__radio-field--text">'.$sendtype.'</span>';
echo '</label></span>';
}
?>
</div>
</div>


<!-- 名前 -->
<div class="p-form__group">
<div class="p-form__ttl"><span class="c-form__ttl">お名前</span><strong>必須</strong></div>
<div class="p-form__input">
<input type="text" name="input_name" id="form_name" class="p-form__control--input-half" size="20" value="" placeholder="お名前">
</div>
</div>

<!-- 電話番号 -->
<div class="p-form__group" >
<div class="p-form__ttl"><span class="c-form__ttl">電話番号</span><strong>必須</strong></div>
<div class="p-form__input">
<input type="tel" name="input_tel" id="form_tel" class="p-form__control--input-half" size="26" value="" placeholder="電話番号" >
<p></p>
<p class="p-form__group--caution">※携帯電話の番号も可</p>
</div>
</div>


<!-- メールアドレス -->
<div class="p-form__group" >
<div class="p-form__ttl"><span class="c-form__ttl">メールアドレス</span><strong>必須</strong></div>
<div class="p-form__input">
<input type="email" name="input_email" id="form_email" class="p-form__control--input" size="40" maxlength="50" value="" placeholder="メールアドレス" >
<p></p>
<p class="mt__075 mt__1--md fz__14">※必ず半角英数でご入力ください。</p>
<!-- <p class="p-form__group--caution">※携帯電話のメールアドレスも可</p> -->
</div>
</div>


<!-- 郵便番号 -->
<div class="p-form__group">
<div class="p-form__ttl"><span class="c-form__ttl">郵便番号</span></div>
<div class="p-form__input">
  <input type="hidden" class="p-country-name" value="Japan">
  <input type="tel" id="form_postalcode" name="input_postalcode" size="16" maxlength="16" class="p-form__control--input-half p-postal-code" placeholder="000-0000" />
  <p class="mt__075 mt__1--md fz__14">※ハイフンは不要です。 <br class="">※郵便番号に応じた住所が以下に自動入力されます。</p>
</div>
</div>


<!-- 住所 -->
<div class="p-form__group">
<div class="p-form__ttl"><span class="c-form__ttl">ご住所</span></div>
<div class="p-form__input">
<input type="hidden" class="p-country-name" value="Japan">
<p class="p-form__address--caption">都道府県</p>
  <select name="input_region" class="p-form__control--select-half p-region">
    <option value="" selected>--</option>
<?php
$regionArr = ["北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県","茨城県","栃木県","群馬県","埼玉県","千葉県","東京都","神奈川県","新潟県","富山県","石川県","福井県","山梨県","長野県","岐阜県","静岡県","愛知県","三重県","滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県","鳥取県","島根県","岡山県","広島県","山口県","徳島県","香川県","愛媛県","高知県","福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県","沖縄県"];

foreach( $regionArr as $region){
echo '<option value="'.$region.'">'.$region.'</option>';
}
?>
  </select>
  <p class="mt__075 mt__1--md p-form__address--caption">市区町村</p>
  <input type="text" id="form_add-city" name="input_city" class="p-form__control--input p-locality p-street-address" placeholder="市区町村" />
  <p class="mt__075 mt__1--md p-form__address--caption">以降の住所</p>
  <input type="text" id="form_add-extend" name="input_extendadd" class="p-form__control--input p-extended-address" placeholder="以降の住所" />
</div>
</div>







<!-- お問い合わせ内容 -->
<div class="p-form__group" >
<div class="p-form__ttl"><span class="c-form__ttl">お問い合わせ内容</span><strong>必須</strong></div>
<div class="p-form__input">
<textarea name="input_inquiry" id="form_inquiry" class="p-form__control--textarea" cols="60" rows="5" placeholder="※140字以内でご記入ください。"></textarea>
</div>
</div>




<div class="mt__3 mt__4--md tac" id="form__group--submit">
<input type="submit" value="内容を確認する" class="p-submit__button" id="form_submit" />
</div>

</form>
<!-- /form -->

    </div>
  </div></div>

  <div class="l-row--container">
    <div class="c-col--12 c-col__md--10 c-col__lg--9 c-col_xl--8">
      <?php get_template_part('tmp/tmp', 'form-caution'); ?>
    </div>
  </div>


  </div>
</article>
