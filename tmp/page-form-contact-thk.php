<article class="">
<div class="headline__outer">
    <div class="container-width mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0">
      <div class="headline__frame" >
        <h1 class="headline__title headline__title--long"><span class="">THANKS</span></h1>
        <div class="headline__detail">
    <p class="">
    <span class="ttl__xsmall text-clr3"><span class="text-clr1">#</span> 送信完了</span></p>
        </div>
      </div>
    </div>
  </div>

  <div class="container-py">
  <div class="container-width mx-auto flex flex-wrap justify-center">
  <div class="w-full md:w-10/12 lg:w-9/12 xl:w-8/12">
  <ul class="entrystep px-gutter-row md:px-0">
      <?php component_listItemEntrystep('input', '01', '入力画面'); ?>
      <?php component_listItemEntrystep('confirm', '02', '内容確認'); ?>
      <?php component_listItemEntrystep('send', '03', '送信完了', 'is-active'); ?>
      </ul>
    </div>
  </div>


  <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
    <div class="w-full md:w-11/12 xl:w-10/12 entrystep__caption">
      <p class="typ">フォーム送信が完了いたしました。<br class="sm:hidden">記入いただいたメールアドレスに<br class="max-sm:hidden">自動返信メールをお送りしていますのでご確認ください。</p>
      <p class="typ mt-2 md:mt-3">自動返信メールが誤って迷惑メールと判断されてしまい<br class="max-sm:hidden">「迷惑メールフォルダ」や「削除フォルダ」「スパムフォルダ」等に<br class="max-sm:hidden">届いてしまう可能性がございます。<br><strong>自動返信メールが届かない場合、</strong><br class="max-sm:hidden">恐れ入りますが一度「迷惑メールフォルダ」などをご確認ください。</p>
      <p class="typ mt-2 md:mt-3">弊社にてお問い合わせ内容を確認後、担当者より<br class="max-sm:hidden">ご連絡致しますので今しばらくお待ちくださいませ。</p>
    </div>
  </div>

  <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0  mt-6 md:mt-8">
    <div class="button__wrap">
<?php
  $args = array(
    'link_slug' => '',
    'className' => 'button__grd',
    'text' => 'トップページへ',
);
component_buttonLink($args);
?>
    </div>
</div>


  </div>

</article>

<?php //get_template_part('tmp/tmp', 'post-feed_article'); ?>