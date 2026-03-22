<?php
function component_buttonLinkExternal($args) {
// $args が渡されていない場合に備えて初期値を設定
$args = isset($args) ? $args : array();

// $args から各値を取得
$link_url = isset($args['link_url']) ? $args['link_url'] : '';
$className = isset($args['className']) ? $args['className'] : '';
$setClass = 'button ' . $className;
$text = isset($args['text']) ? $args['text'] : '';

?>


<a href='<?php echo esc_url($link_url); ?>' class="<?php echo esc_attr($setClass); ?>" target="_blank" rel="noopener noreferrer">
<?php echo $text; ?>
<svg class="button__icon--external" data-slot="icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M15.75 2.25H21a.75.75 0 0 1 .75.75v5.25a.75.75 0 0 1-1.5 0V4.81L8.03 17.03a.75.75 0 0 1-1.06-1.06L19.19 3.75h-3.44a.75.75 0 0 1 0-1.5Zm-10.5 4.5a1.5 1.5 0 0 0-1.5 1.5v10.5a1.5 1.5 0 0 0 1.5 1.5h10.5a1.5 1.5 0 0 0 1.5-1.5V10.5a.75.75 0 0 1 1.5 0v8.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V8.25a3 3 0 0 1 3-3h8.25a.75.75 0 0 1 0 1.5H5.25Z"></path></svg>
</a>

<?php
    // 関数の最後には余分な改行や空白を避けるため、ここで PHP タグを閉じる
}
?>