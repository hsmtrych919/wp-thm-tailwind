<div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0">
      <div class="w-full">
        <h2 class="salonlist__title"><span class="ttl__small"><span class="text-clr1">#</span> カット</span></h2>
      </div>
    </div>

    <div class="container-width mx-auto flex flex-wrap justify-center px-gutter-row xl:px-0 menu__content">
      <div class="w-full">
        <p class="typ__xs text-gray-700">※シャンプー・ブロー・5分間マッサージサービス。</p>
      </div>
      <div class="menu__frame">
        <div class="menu__left">
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
        <div class="menu__right">
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