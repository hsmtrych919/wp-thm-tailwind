<div class="l-row--container c-gutter__row">
      <div class="c-col--12">
        <h2 class="p-salonlist__title"><span class="c-ttl__small"><span class="text-clr1">#</span> カット</span></h2>
      </div>
    </div>

    <div class="l-row--container c-gutter__row p-menu__content">
      <div class="c-col--12">
        <p class="c-typ__xs text-gray-700">※シャンプー・ブロー・5分間マッサージサービス。</p>
      </div>
      <div class="p-menu__frame">
        <div class="p-menu__left">
      <dl class="">
<?php
$menuItems = array(
    array('title' => 'レディースカット', 'price' => '4,730円'),
    array('title' => 'メンズカット', 'price' => '5,060円'),
    array('title' => '学生割引（大学・専門学校）', 'price' => '4,290円', ),
);
component_dlItemMenu($menuItems );
?>
      </dl>
        </div>
        <div class="p-menu__right">
      <dl class="">
      <?php
$menuItems = array(
    array('title' => '学生割引（高校生）', 'price' => '3,960円',),
    array('title' => '学生割引（中学生以下）', 'price' => '3,300円'),
);

component_dlItemMenu($menuItems, true );
?>
      </dl>
        </div>
      </div>
    </div>