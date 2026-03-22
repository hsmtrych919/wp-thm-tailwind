<?php
function component_listItemMenuIntroduction($args) {
    // $args が渡されていない場合に備えて初期値を設定
    $args = isset($args) ? $args : array();

    // $args から各値を取得
    $image_src = isset($args['image_src']) ? $args['image_src'] : '';
    $image = get_template_directory_uri() . '/img/' . $image_src;
    $lead_text = isset($args['lead_text']) ? $args['lead_text'] : '';
    $content = isset($args['content']) ? wp_kses_post($args['content']) : '';
?>
<li>
    <div class="front-menu__item " href='<?php echo esc_url($url); ?>'>
        <p class="front-menu__thumbnail"><img src='<?php echo $image; ?>' alt="<?php echo esc_attr($lead_text); ?>"></p>
        <div class="front-menu__content">
            <p class="front-menu__lead--detail"><?php echo esc_html($lead_text); ?></p>
            <div class="front-menu__detail">
                <p><?php echo $content; ?></p>
            </div>
        </div>
    </div>
</li>
<?php
    // 関数の最後には余分な改行や空白を避けるため、ここで PHP タグを閉じる
}
?>