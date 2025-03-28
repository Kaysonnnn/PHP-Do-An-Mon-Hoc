<?php
namespace app\helpers;

/**
 * <h1>Chi tiết về CSRF Token trong Form để bảo vệ chống tấn công giả mạo</h1>
 *
 * <b>CSRF (Cross-Site Request Forgery)</b> là một lỗ hổng bảo mật phổ biến, nơi kẻ tấn
 * công gửi các yêu cầu trái phép thay mặt người dùng mà họ không hề hay biết.
 * <p>
 * Để ngăn chặn, sử dụng <b>CSRF Token</b> trong các form và xác minh token trước khi xử
 * lý dữ liệu từ form.
 *
 * @see https://en.wikipedia.org/wiki/Cross-site_request_forgery
 */
class Csrf
{
  public static function generateToken()
  {
    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
  }

  public static function validateToken($token)
  {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
  }

  public static function clearToken()
  {
    unset($_SESSION['csrf_token']);
  }
}
