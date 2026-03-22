<?php
function component_buttonLink($args, $forPost = false) {
    // $args が渡されていない場合に備えて初期値を設定
    $args = isset($args) ? $args : array();

    // $args から各値を取得
    $link_slug = isset($args['link_slug']) ? $args['link_slug'] : '';
    $url = $forPost ? esc_url($link_slug) : home_url('/' . $link_slug);
    $className = isset($args['className']) ? $args['className'] : '';
    $setClass = 'button ' . $className;
    $text = isset($args['text']) ? $args['text'] : '';
?>
<a href='<?php echo esc_url($url); ?>' class="<?php echo esc_attr($setClass); ?>">
    <?php echo $text; ?>
    <svg class="button__icon--arrow" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path></svg>
</a>
<?php
    // 関数の最後には余分な改行や空白を避けるため、ここで PHP タグを閉じる
}
?>