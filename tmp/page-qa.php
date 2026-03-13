<article class="">
  <div class="c-headline-pic__outer">
    <div class="l-row--container c-gutter__row jc__start">
      <div class="c-headline-pic__frame js-headline-frame" >
        <h1 class="c-headline-pic__title"><span class="c-headline-pic__typ--for-gsap">Q&A</span></h1>
        <div class="c-headline-pic__pic js-headline-pic" >
        <span class="c-headline-pic__pic--ract"></span>
        <img src='<?php echo get_template_directory_uri(); ?>/img/c_qa.jpg' alt="Q&A">
        </div>
        <div class="c-headline-pic__detail c-headline-pic__typ--for-gsap">
          <p class="c-typ">初めてギッシュにご来店される方に向けて、お客様からのよくある質問をご紹介します。</p>
        </div>
      </div>
    </div>
  </div>
  <div class="mt__7 mt__8--md">

    <div class="l-row--container c-gutter__row">
      <div class="c-col--12 c-col__md--12">

<?php
// 事前に用意した質問と回答のデータ
$content = array(
    array(
        'question' => '予約は必要ですか？',
        'answer' => '事前にご希望の日時をご連絡頂けるとお待たせすることなく、スムーズにご案内させて頂きます。<br>ご予約はお電話の他に、メールで24時間お好きな時間にご予約頂けます。',
    ),
    array(
        'question' => 'メニューや髪型が決まっていなくても予約できますか？',
        'answer' => 'はい、大丈夫です。髪型が決まっていない場合はいらした後にスタイリストにお気軽にご相談ください。<br>スタイリストがお客様のご希望をご納得のいくまで十分に聞きながら、ぴったりのスタイルをご提案いたします。',
    ),
    array(
        'question' => '価格がいくら位になるのか心配ですが',
        'answer' => 'カウンセリング時に必要なメニューに関しての料金をしっかりご説明致しますのでご安心下さい。',
    ),
    array(
        'question' => '駐車場はありますか？',
        'answer' => '全店舗女性に優しい広い駐車場がございますので安心してお車でご来店下さい。',
    ),
    array(
        'question' => 'それぞれのメニューにかかる大体の所要時間はどのくらいですか？',
        'answer' => '混雑の状況により異なりますが、以下が目安ですので参考にして下さい。<br>●カット / 1時間　●パーマまたはカラー / 1時間半　●縮毛矯正 / 3時間',
    ),
    array(
        'question' => 'スタイリングはしないで行ったほうがいいですか？',
        'answer' => '普段のスタイルを把握するためにもスタイリングはして来て頂いてOKです。',
    ),
    array(
        'question' => '子供を連れていっても大丈夫ですか？',
        'answer' => '小さなお子様の場合ですと、キッズルームのある店舗を是非ご利用下さい。',
    ),
    array(
        'question' => '雑誌の切り抜きなど希望の髪型を持参したほうがいいですか？',
        'answer' => 'お好みの髪型がある場合はお持ち頂けると嬉しいです。',
    ),
    array(
        'question' => '指名料は何ですか？',
        'answer' => 'カットの際に前もってスタッフを指名される場合は以下の金額を頂いております。指名なしでもお気軽にご予約ください。<br>スタイリスト個人指名＋550円。',
    ),
    array(
        'question' => 'ロング料金は何ですか？',
        'answer' => 'カラーとパーマをご利用される場合、髪の長さによって使用する薬液の量が異なるため、
        以下のとおり追加料金を頂いております。<br>セミロング / 耳下から肩まで＋550円、ロング / 肩下まで＋1100円',
    ),
    array(
        'question' => '気に入らなかった場合、やり直して頂く事は可能ですか？',
        'answer' => 'お気づき、ご不満の点がございましたら、14日以内に再度ご来店ください。無料でお直しさせていただきます。',
    ),
    array(
        'question' => '学生の場合は割引がありますか？',
        'answer' => 'はい、ございます。メニューページをご確認下さい。',
    ),
    array(
        'question' => 'ストレートパーマと縮毛矯正はどう違うんですか？',
        'answer' => 'ストレートパーマは、パーマをかけた髪を、ストレートに戻すために行うものです。元々クセ毛の方は時間がたつと元のクセ毛に戻ってしまいます。縮毛矯正は、クセ毛の方がストレートにするために行うものです。かけた部分は半永久的にストレートになります。',
    ),
);


foreach ($content as $qa) {
    echo '<div class="p-qa__frame">';
    echo '<p class="p-qa__question">' . $qa['question'] . '</p>';
    echo '<p class="p-qa__answer"><span class="clr__1">A. </span>' . wp_kses_post($qa['answer']) . '</p>';
    echo '</div>';
}
?>

        </div>
      </div>
</div>

</article>


