<?php
global $salonInfomation;
$dataTokadai = $salonInfomation['tokadai'];
$icon_tel = '<svg class="c-button__icon--tel" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"></path></svg>';

$data = array(
array('dt' => '職種', 'dd' => 'アシスタント・レセプション・アイリスト・ネイリスト・エステティシャン・高校卒'),
array('dt' => '給与', 'dd' => 'アシスタント<br>
月給20万円（入社後半年間19万円）<br>
レセプション・アイリスト・ネイリスト・エステティシャン・高校卒<br>
月給19万円（入社後半年間18万円）'),
array('dt' => '資格', 'dd' => '18～40歳位まで'),
array('dt' => '勤務地', 'dd' => 'guiches各店<br>北外山店、イオン小牧店、桃花台店、江南店、北名古屋店'),
array('dt' => '勤務時間', 'dd' => '9：00～18：00　※店舗により多少異なる'),
array('dt' => '休日・休暇', 'dd' => '完全週休2日制(交替制)＋年3日（有給含む）'),
array('dt' => '待遇', 'dd' => '・昇給年4回、賞与(実績による)<br>
・交通費支給(1ヶ月　1万5000円まで)<br>
・社会保険 ・厚生年金 ・雇用保険 ・労災保険 ・寮完備（1人部屋） ・車通勤OK ・独立支援制度 ・研修費補助 ・保養所(会員制リゾート「エクシブ」) ・社員旅行 ・ジェットスキー ・スノボー ・温泉 ・ボウリング大会 ・バーベキュー ・カラオケ ・長島スパーランド ・クリスマス会 ・新年会他'),
array('dt' => '教育制度', 'dd' => '・名古屋、海外(ニューヨーク、ヨーロッパ)研修<br>
・ギッシュ　ビューティーオブアカデミー開校<br>
・外部講師によるカットセミナー、カラーセミナー、メイクセミナー、ヒューマンスキルアップセミナー、社内セミナー<br>
・雑誌やヘアカタログ、コンテスト(優勝・入賞多数)で活躍しているスタッフも多数在籍、先輩を通じスキルを磨けます。'),
array('dt' => '応募方法', 'dd' => 'お気軽にお電話の上、履歴書(写真貼付)を持参下さい。'),
);

foreach ($data as $index => $item) {
echo '<div class="p-recruit-info__dl--row">';
if ($index === 0) {
echo '<dt class="p-recruit-info__dt--first">' . $item['dt'] . '</dt>';
} else {
echo '<dt>' . $item['dt'] . '</dt>';
}
echo '<dd>' . wp_kses_post($item['dd']) . '</dd>';
echo '</div>';
}
?>

<div class="p-recruit-info__dl--row">
    <dt>受付連絡先</dt>
    <dd>

      <p>受付・面接は桃花台店まで</p>
      <?php if( is_mobile()) : ?>
        <div class="l-row mt-1">
            <div class="p-button__wrap">
              <a href="tel:<?php echo esc_attr($dataTokadai['tel_href']); ?>" class="c-button c-button__tel">
              <?php echo $icon_tel; ?><span>電話する</span>
            </a>
            </div>
          </div>
      <?php else: ?>
        <span class="c-typ__m">TEL. <?php echo esc_attr($dataTokadai['tel']); ?></span>
      <?php endif; ?>

    </dd>
</div>