<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'order.php';
header('X-FRAME-OPTIONS: DENY');

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = get_post('token');
  $order_id = get_post('order_id');

  if (is_valid_csrf_token($token) === false) {
    redirect_to(ORDER_URL);
  }
  $order_details = get_order_details($db, $user, $order_id);
  if ($order_details !== false) { 
    $total_price = sum_price($order_details);
  }
}
$token = get_csrf_token();
include_once VIEW_PATH . '/order_details_view.php';
?>