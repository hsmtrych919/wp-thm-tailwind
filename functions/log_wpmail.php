
// wp_mail() メール送信失敗時のログ
add_action('wp_mail_failed', 'log_wp_mail_failed', 10, 1);
function log_wp_mail_failed($error) {
    global $wp_mail_failed_flag;
    $wp_mail_failed_flag = true; // 失敗フラグをセット

    $error_message = $error->get_error_message();
    $error_code = $error->get_error_code();
    log_mail_to_file('ERROR', $error_message, $error_code);
}

// wp_mail() メール送信成功時のログ
add_filter('wp_mail', 'log_wp_mail_success', 10, 1);
function log_wp_mail_success($args) {
    global $wp_mail_success_log;
    if (!isset($wp_mail_success_log)) {
        $wp_mail_success_log = [];
    }
    $wp_mail_success_log[] = $args;
    return $args;
}

// wp_mail() メール送信成功時のログ用 メール送信結果をログに記録
add_action('shutdown', 'process_mail_logs');
function process_mail_logs() {
    global $wp_mail_success_log, $wp_mail_failed_flag;
    if (empty($wp_mail_success_log)) {
        return;
    }
    // 失敗フラグが立っている場合は成功ログを記録しない
    if (isset($wp_mail_failed_flag) && $wp_mail_failed_flag) {
        // フラグをリセット
        $wp_mail_failed_flag = false;
        return;
    }
    foreach ($wp_mail_success_log as $mail_args) {
        // 送信先アドレスをログに記録
        log_mail_to_file('SUCCESS', 'wp_mail sent successfully to: ' . implode(',', (array) $mail_args['to']));
    }
    // リセット
    $wp_mail_success_log = [];
}


// Unified function to log mail events
function log_mail_to_file($status, $message, $code = null) {
  $log_dir = dirname(WP_CONTENT_DIR);
  $log_file = $log_dir . '/wp_debug' . date('Ymd') . '.log';

  $log_message = '[' . date('Y-m-d H:i:s') . '] ';
  $log_message .= strtoupper($status) . ': ' . $message;
  if ($code) {
      $log_message .= ' | Code: ' . $code;
  }
  $log_message .= "\n";

  error_log($log_message, 3, $log_file);
}