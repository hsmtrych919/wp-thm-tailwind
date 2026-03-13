<?php
function component_listItemLikepost($args) {
    // $args が渡されていない場合に備えて初期値を設定
    $args = isset($args) ? $args : array();

    // $args から各値を取得
    $link_slug = isset($args['link_slug']) ? $args['link_slug'] : '';
    $url = home_url('/' . $link_slug);
    $image_src = isset($args['image_src']) ? $args['image_src'] : '';
    $image = get_template_directory_uri() . '/img/' . $image_src;
    $lead_text = isset($args['lead_text']) ? $args['lead_text'] : '';
    $content = isset($args['content']) ? wp_kses_post($args['content']) : '';
?>
<li>
    <a class="c-likepost__item" href='<?php echo esc_url($url); ?>'>
        <p class="c-likepost__thumbnail"><img src='<?php echo $image; ?>' alt="<?php echo esc_attr($lead_text); ?>"></p>
        <div class="c-likepost__content">
            <p class="c-likepost__lead"><?php echo esc_html($lead_text); ?></p>
            <div class="c-likepost__detail">
                <p><?php echo $content; ?></p>
                <svg class="c-likepost__arrow" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path></svg>
            </div>
        </div>
    </a>
</li>
<?php
    // 関数の最後には余分な改行や空白を避けるため、ここで PHP タグを閉じる
}
?>