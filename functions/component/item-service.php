<?php
function component_listItemService($args) {
    // $args が渡されていない場合に備えて初期値を設定
    $args = isset($args) ? $args : array();

    // $args から各値を取得
    $number = isset($args['number']) ? $args['number'] : '';
    $image = get_template_directory_uri() . '/img/service_icon' . $number . '.svg';
    $title = isset($args['title']) ? $args['title'] : '';
    $content = isset($args['content']) ? wp_kses_post($args['content']) : '';
?>
    <li>
        <div class="p-service__frame">
            <p class="p-service__icon">
                <img src='<?php echo $image; ?>' alt="<?php echo $title; ?>">
            </p>
            <div class="p-service__content">
                <h2 class="p-service__content--title">
                    <span class="text-gray-700"><?php echo $number; ?>.</span>
                    <?php echo $title; ?>
                </h2>
                <p class="p-service__content--detail">
                    <?php echo $content; ?>
                </p>
            </div>
        </div>
    </li>
<?php
    // 関数の最後には余分な改行や空白を避けるため、ここで PHP タグを閉じる
}
?>