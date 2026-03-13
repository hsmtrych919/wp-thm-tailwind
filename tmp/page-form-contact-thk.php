<article class="">
<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row jc__start">
      <div class="c-headline__frame" >
        <h1 class="c-headline__title c-headline__title--long"><span class="">THANKS</span></h1>
        <div class="c-headline__detail">
    <p class="">
    <span class="c-ttl__xsmall clr__3"><span class="clr__1">#</span> 送信完了</span></p>
        </div>
      </div>
    </div>
  </div>

  <div class="l-container">
  <div class="l-row--container">
  <div class="c-col--12 c-col__md--10 c-col__lg--9 c-col__xl--8">
  <ul class="p-entrystep">
      <?php component_listItemEntrystep('input', '01', '入力画面'); ?>
      <?php component_listItemEntrystep('confirm', '02', '内容確認'); ?>
      <?php component_listItemEntrystep('send', '03', '送信完了', 'is-active'); ?>
      </ul>
    </div>
  </div>


  <div class="l-row--container c-gutter__row">
    <div class="c-col--12 c-col__md--11 c-col__xl--10 p-entrystep__caption">
      <p class="c-typ">フォーム送信が完了いたしました。<br class="hide__sm--up">記入いただいたメールアドレスに<br class="hide__xs--down">自動返信メールをお送りしていますのでご確認ください。</p>
      <p class="c-typ mt__2 mt__3--md">自動返信メールが誤って迷惑メールと判断されてしまい<br class="hide__xs--down">「迷惑メールフォルダ」や「削除フォルダ」「スパムフォルダ」等に<br class="hide__xs--down">届いてしまう可能性がございます。<br><strong>自動返信メールが届かない場合、</strong><br class="hide__xs--down">恐れ入りますが一度「迷惑メールフォルダ」などをご確認ください。</p>
      <p class="c-typ mt__2 mt__3--md">弊社にてお問い合わせ内容を確認後、担当者より<br class="hide__xs--down">ご連絡致しますので今しばらくお待ちくださいませ。</p>
    </div>
  </div>

  <div class="l-row--container c-gutter__row  mt__6 mt__8--md">
    <div class="p-button__wrap">
<?php
  $args = array(
    'link_slug' => '',
    'className' => 'c-button__grd',
    'text' => 'トップページへ',
);
component_buttonLink($args);
?>
    </div>
</div>


  </div>

</article>

<?php //get_template_part('tmp/tmp', 'post-feed_article'); ?>