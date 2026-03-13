<?php
header('X-FRAME-OPTIONS: SAMEORIGIN');
header('Content-Type: text/html; charset=UTF-8');

//管理者名
$adminName = "会社名";
//管理者メールアドレス xxxが本番用
$adminEmail = "info@consultingservice.co.jp";
// $adminEmail = "form@xxx.com";
//文章中メールアドレス xxxが本番用
$contentEmail = "info@consultingservice.co.jp";
// $contentEmail = "form@xxx.com";

//DKIM/DMARC対策：wp_mail()送信元（Return-Path）を$adminEmailに設定するフィルターとフック
add_filter('wp_mail_from', function($email) use ($adminEmail) {
  return $adminEmail;
});

// DKIM/DMARC検証で用いるエンベロープ送信元名。$header , $reheader のfromとは競合しない
add_filter('wp_mail_from_name', function($name) {
  return 'ホームページから配信';
});

//phpmailer_initフックでエンベロープ送信元（Return-Path）を設定
add_action('phpmailer_init', function($phpmailer) use ($adminEmail) {
  $phpmailer->Sender = $adminEmail;
});


// 本ファイルの表示url
$fileName = home_url('/').'form-contact/form-contact-chk';
//送信後移動先
$thanksPage = home_url('/').'form-contact/form-contact-thk';

// メールフッター
$mailSignature = <<< FOOTER

----------------------------------------------------

会社情報



FOOTER;


//クロスサイトスクリプティング対策 エスケープ処理
function escape($str) {
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

//メールアドレス検証機能
function validateEmail($email) {
  // 基本的な形式チェック
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 危険な文字の検証（メールヘッダインジェクション対策）
  $dangerous_chars = ["\r", "\n", "%0a", "%0d", "bcc:", "cc:", "to:"];
  foreach ($dangerous_chars as $char) {
    if (strpos(strtolower($email), $char) !== false) {
      return false;
    }
  }

  return true;
}
//入力画面からでない場合にリダイレクト
if(!$_POST) {
  header('Location: '.home_url('/').'form-contact');
}
//トークンチェック・POSTからSESSIONへ追加
if($_SESSION['input_token'] === $_POST['input_token']) {
  // 前画面からのPOSTをSESSIONに格納
  $_SESSION = $_POST;
  $tokenValidateError = false;
} else {
  $tokenValidateError = true;
}
//コンソールログ出力
function console_log($data){
  echo '<script>console.log('.json_encode($data).')</script>';
}

//変数に格納
$inputSendtype = escape( $_SESSION['input_sendtype'] );
$inputName = escape( $_SESSION['input_name'] );
$inputPostalCode = escape( $_SESSION['input_postalcode'] );
$inputRegion = escape( $_SESSION['input_region'] );
$inputCity = escape( $_SESSION['input_city'] );
$inputExAdd = escape( $_SESSION['input_extendadd'] );
$inputTel = escape( $_SESSION['input_tel'] );

// メールアドレスの検証とエスケープ
$inputEmail = escape( $_SESSION['input_email'] );
$emailValidationError = false;
if (!empty($inputEmail) && !validateEmail($_SESSION['input_email'])) {
  $emailValidationError = true;
}

$inputInquiry = escape( $_SESSION['input_inquiry'] );

// if( isset( $_SESSION['input_menu'] ) ){
//   $inputMenu = implode(' , ', $_SESSION['input_menu']);
// }
// $inputHowto = escape( $_SESSION['input_howto'] );
// $inputSalon = escape( $_SESSION['input_salon'] );
// $inputBkg1Month = escape( $_SESSION['input_bkg1month'] );
// $inputBkg1Day = escape( $_SESSION['input_bkg1day'] );
// $inputBkg1Time = escape( $_SESSION['input_bkg1time'] );
// $inputBkg2Month = escape( $_SESSION['input_bkg2month'] );
// $inputBkg2Day = escape( $_SESSION['input_bkg2day'] );
// $inputBkg2Time = escape( $_SESSION['input_bkg2time'] );
// $inputBkg3Month = escape( $_SESSION['input_bkg3month'] );
// $inputBkg3Day = escape( $_SESSION['input_bkg3day'] );
// $inputBkg3Time = escape( $_SESSION['input_bkg3time'] );

// メール本文出力
$outSendtype = isset($_POST['送信内容']) ? "【送信内容】 " . $_POST['送信内容'] . "\n" : "";
$outInquiry = isset($_POST['お問い合わせ内容']) ? "【お問い合わせ内容】\n" . $_POST['お問い合わせ内容'] . "\n" : "";
$outName = isset($_POST['お名前']) ? "【お名前】 " . $_POST['お名前'] . "\n" : "";
$outPostalCode = isset($_POST['郵便番号']) ? "【郵便番号】 " . $_POST['郵便番号'] . "\n" : "";
$outAddress = isset($_POST['ご住所']) ? "【ご住所】 " . $_POST['ご住所'] . "\n" : "";
$outTel = isset($_POST['電話番号']) ? "【電話番号】 " . $_POST['電話番号'] . "\n" : "";
$outMail = isset($_POST['メールアドレス']) ? "【メールアドレス】 " . $_POST['メールアドレス'] . "\n" : "";
// $outMenu = isset($_POST['お問い合わせ項目']) ? "【お問い合わせ項目】 " . $_POST['お問い合わせ項目'] . "\n" : "";
// $outHowto = isset($_POST['ご連絡方法']) ? "【ご連絡方法】 " . $_POST['ご連絡方法'] . "\n" : "";
// $outSalon = isset($_POST['希望サロン']) ? "【希望サロン】 " . $_POST['希望サロン'] . "\n" : "";
// $outBkg1 = isset($_POST['ご予約第1希望']) ? "【ご予約第1希望】 " . $_POST['ご予約第1希望'] . "\n" : "";
// $outBkg2 = isset($_POST['ご予約第2希望']) ? "【ご予約第2希望】 " . $_POST['ご予約第2希望'] . "\n" : "";
// $outBkg3 = isset($_POST['ご予約第3希望']) ? "【ご予約第3希望】 " . $_POST['ご予約第3希望'] . "\n" : "";

$mailContent = <<< EOD

{$outSendtype}{$outInquiry}
-----\n
{$outName}{$outTel}{$outMail}{$outPostalCode}{$outAddress}

EOD;


// wpmp-config.php は 修正ファイルを wp-contentディレクトリにアップ
// 2024 本番サーバーでは mail関数を使用

//管理者宛メール設定--------------------------
$sbj = "ホームページからの".$inputSendtype;
$body= $sbj."について\n\n";
$body.= "本メールはホームページからの自動送信です。\n";
$body.= "お客様への返信は以下記載のメールアドレス宛へお送りください。\n\n";
$body.="----------------------------------------------------\n";
$body.= $mailContent;
$body.="----------------------------------------------------\n";
// wordpressからのメールを管理者メールアドレス（サイトと同ドメイン）へ配信
$header="From: ホームページから配信 <".$adminEmail.">\nContent-Type: text/plain; charset=\"UTF-8\";\nX-Mailer: PHP\n";


//ユーザー宛メール設定--------------------------
$resbj = $adminName." より ".$inputSendtype."受付のお知らせ ";
$rebody = isset($_POST[ 'お名前' ]) ? $_POST[ 'お名前' ]." 様\n\n" : "";
$rebody.="この度は、".$adminName." に".$inputSendtype."いただき、誠にありがとうございます。\n";
$rebody.="下記の内容で".$inputSendtype."を承りました。\n早急にご返信いたしますので今しばらくお待ちください。\n\n";
$rebody.="※本メールはご入力確認のための自動配信メールでございます。\n";
$rebody.="----------------------------------------------------\n";
$rebody.= $mailContent;
$rebody.="----------------------------------------------------\n\n";
$rebody.= "もしこのメールにお心当たりが無い場合や、ご不明な点がございましたら \n";
$rebody.= "お手数ではございますが ".$contentEmail." までご連絡くださいませ。\n\n\n";
$rebody.= $mailSignature;
$reto = isset($_POST['メールアドレス']) ? $_POST['メールアドレス'] : "";
// mb_encode_mimeheader文字列の長さ注意
$reheader="From: ".mb_encode_mimeheader('お知らせ')." <".$adminEmail.">\nReply-To: ".$adminEmail."\nContent-Type: text/plain; charset=\"UTF-8\";\nX-Mailer: PHP\n";

// $adminName 長い場合にエラーでるコード
// $reheader="From: ".mb_encode_mimeheader($adminName)." <".$adminEmail.">\nReply-To: ".$adminEmail."\nContent-Type: text/plain; charset=\"UTF-8\";\nX-Mailer: PHP/". phpversion();

// submit処理
$isSubmit = false;
// recaptcha認証失敗のエラーメッセージ
$errorMessage = false;
// recaptcha認証
if(isset($_POST["recaptchaToken"]) && !empty($_POST["recaptchaToken"])){
  $recaptcha_token = $_POST['recaptchaToken'];
  $recaptcha_secret = 'シークレットキー';
  $recaptch_url = 'https://www.google.com/recaptcha/api/siteverify?secret=';
  $res_json = file_get_contents(
    $recaptch_url.$recaptcha_secret.'&response='.$recaptcha_token
  );
  $res = json_decode($res_json);

  if($res->success && $res->score > 0.75){
    // 認証成功 && スコア 0.75以上
    foreach($_POST as $key=>$val) {
      if($val === "isSubmit") $isSubmit = true;
    }
  }else{
    // 認証エラー
    $errorMessage = true;
    // console_log("認証エラー");
  }
}


if($isSubmit){
  mb_language("ja");
  mb_internal_encoding("UTF-8");
  // 管理者宛
  wp_mail(
  // mail(
    $adminEmail,
    mb_encode_mimeheader($sbj, "UTF-8"),
    mb_convert_encoding($body, "UTF-8"),
    $header
  );
  //ユーザー宛
  wp_mail(
  // mail(
    $reto,
    mb_encode_mimeheader($resbj, "UTF-8"),
    mb_convert_encoding($rebody, "UTF-8"),
    $reheader
  );
  $isSend = true;
  session_destroy();
  $_POST = array();
} else {
  $isSend = false;

  // ページhtml

?>
<?php get_header(); ?>

<article class="">
<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row jc__start">
      <div class="c-headline__frame" >
        <h1 class="c-headline__title c-headline__title--long"><span class="">CONTACT</span></h1>
        <div class="c-headline__detail">
    <p class="">
    <span class="c-ttl__xsmall clr__3"><span class="clr__1">#</span> 入力内容の確認</span></p>
        </div>
      </div>
    </div>
  </div>

  <div class="l-container">
  <div class="l-row--container">
  <div class="c-col--12 c-col__md--10 c-col__lg--9 c-col__xl--8">
  <ul class="p-entrystep">
      <?php component_listItemEntrystep('input', '01', '入力画面'); ?>
      <?php component_listItemEntrystep('confirm', '02', '内容確認', 'is-active'); ?>
      <?php component_listItemEntrystep('send', '03', '送信完了'); ?>
      </ul>
    </div>
  </div>
  <div class="l-row--container c-gutter__row">
  <p class="c-col--12 c-col__sm--10 p-entrystep__caption">以下の内容で間違いがなければ、<br class="hide__xs--down">「送信する」ボタンを押してください。</p>
  </div>
  <div class="l-row--container mt__1_5 mt__2--md"><div class="c-col--12 c-col__md--10 c-col__lg--9 c-col__xl--8">
    <div class="p-form">

<div class="form_confirm">
<form id="form_recaptcha" action="<?php echo $fileName; ?>" method="POST" >

<!-- 送信内容 hidden -->
<?php
  //echo "<input type=\"hidden\" name=\"送信内容\" value=\"".$inputSendtype."\">" ;
?>

<!-- 送信内容 radio-->
<?php
if( isset( $inputSendtype ) && $inputSendtype !== "" ){
?>
<div class="p-form__group--confirm" >
<div class="p-form__ttl--confirm"><span class="c-form__ttl">送信内容</span></div>
<div class="p-form__input--confirm">
<?php
  echo $inputSendtype ;
  echo "<input type=\"hidden\" name=\"送信内容\" value=\"".$inputSendtype."\">" ;
?>
</div>
</div>
<?php } ?>


<!-- お名前 -->
<div class="p-form__group--confirm" >
<div class="p-form__ttl--confirm"><span class="c-form__ttl">お名前</span></div>
<div class="p-form__input--confirm">
<?php
  echo $inputName ;
  echo "<input type=\"hidden\" name=\"お名前\" value=\"".$inputName."\">" ;
?>
</div>
</div>

<!-- 電話番号 -->
<div class="p-form__group--confirm" >
<div class="p-form__ttl--confirm"><span class="c-form__ttl">電話番号</span></div>
<div class="p-form__input--confirm">
<?php
  echo $inputTel ;
  echo "<input type=\"hidden\" name=\"電話番号\" value=\"".$inputTel."\">" ;
?>
</div>
</div>

<!-- メールアドレス -->
<div class="p-form__group--confirm" >
<div class="p-form__ttl--confirm"><span class="c-form__ttl">メールアドレス</span></div>
<div class="p-form__input--confirm">
<?php
  echo $inputEmail ;
  echo "<input type=\"hidden\" name=\"メールアドレス\" value=\"".$inputEmail."\">" ;
?>
</div>
</div>

<!-- 郵便番号 -->
<?php
if( isset( $inputPostalCode ) && $inputPostalCode !== "" ){
?>
<div class="p-form__group--confirm" >
<div class="p-form__ttl--confirm"><span class="c-form__ttl">郵便番号</span></div>
<div class="p-form__input--confirm">
<?php
  $convert = mb_convert_kana($inputPostalCode, "na");
  $outFirst = substr($convert ,0,3);
  $outSecond = substr($convert ,-4);
  $outConvert = $outFirst."-".$outSecond;
  echo $outConvert;
  echo "<input type=\"hidden\" name=\"郵便番号\" value=\"".$outConvert."\">" ;
?>
</div>
</div>
<?php } ?>


<!-- ご住所 -->
<?php
if( isset( $inputRegion ) && $inputRegion !== "" && isset( $inputCity ) && $inputCity !== "" ){
?>
<div class="p-form__group--confirm" >
<div class="p-form__ttl--confirm"><span class="c-form__ttl">ご住所</span></div>
<div class="p-form__input--confirm">
<?php
  $out = $inputRegion." ".$inputCity." ".$inputExAdd;
  echo $out;
  echo "<input type=\"hidden\" name=\"ご住所\" value=\"".$out."\">" ;
?>
</div>
</div>
<?php } ?>


<!-- お問い合わせ内容 -->
<?php
if( isset( $inputInquiry ) && $inputInquiry !== "" ){
?>
<div class="p-form__group--confirm" >
<div class="p-form__ttl--confirm"><span class="c-form__ttl">お問い合わせ内容</span></div>
<div class="p-form__input--confirm fz__16">
<?php
  // textareaの改行をhtmlに反映。取得データには加工無しで反映されている
  echo nl2br($inputInquiry);
  echo "<input type=\"hidden\" name=\"お問い合わせ内容\" value=\"".$inputInquiry."\">" ;
?>
</div>
</div>
<?php } ?>



<!-- 送信ボタン -->
<?php if ( !$errorMessage && !$emailValidationError ) {  ?>
  <?php if(!$tokenValidateError): ?>
    <input type="hidden" name="recaptchaToken" id="recaptchaToken">
    <input type="hidden" name="input_submit" value="isSubmit">
    <div class="c-col--12 mt__3 mt__4--md"><input type="submit" value="送信する" class="p-submit__button"></div>
  <?php endif; ?>
    <div class="c-col--12 mt__3 mt__4--md"><input type="button" value="入力画面に戻る" onClick="history.back()" class="p-submit__button--back"></div>
<?php } else if ($emailValidationError) { ?>
  <div class="c-col--12 mt__3 mt__4--md">
    <p class="tac__sm fz__14 clr__alert">メールアドレスの形式が正しくありません。<br>お手数おかけしますが正しいメールアドレスを入力してください。</p>
  </div>
  <div class="c-col--12 mt__3 mt__4--md">
  <a class="p-submit__button--back fz__16" href='<?php echo home_url('/form-contact'); ?>'>フォーム入力に戻る</a>
  </div>
<?php } else { ?>
  <div class="c-col--12 mt__3 mt__4--md">
    <p class="tac__sm fz__14">申し訳ございません。プログラムによりスパムの疑いが検出されました。<br>お手数おかけしますがフォームの再入力をお願いいたします。</p>
  </div>
  <div class="c-col--12 mt__3 mt__4--md">
  <a class="p-submit__button--back fz__16" href='<?php echo home_url('/form-contact'); ?>'>フォーム入力に戻る</a>
  </div>
<?php } ?>

</form>
</div>

    </div>
  </div></div>

  </div>
</article>
</div>

<script>
document.getElementById('form_recaptcha').addEventListener('submit', onSubmit);
function onSubmit(e) {
  e.preventDefault();
  grecaptcha.ready(function() {
    grecaptcha.execute('サイトキー', {action: 'submit'}).then(function(token) {
      var recaptchaToken = document.getElementById('recaptchaToken');
      recaptchaToken.value = token;
      // console.log('jsフォームサブミット')
      document.getElementById('form_recaptcha').submit();
    });
  });
}
</script>

<?php get_footer(); ?>

<?php
}
if($isSend) {
ob_start();
header('Location: '.$thanksPage);
}
?>