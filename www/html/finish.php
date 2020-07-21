<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
header('X-FRAME-OPTIONS: DENY');

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = get_post('token');
  
  if (is_valid_csrf_token($token) === false) {
    redirect_to(HOME_URL);
  }
  $carts = get_user_carts($db, $user['user_id']);

  if (regist_purchase_carts_transaction($db, $user, $carts) === true){
    set_message('ご購入ありがとうございます。');
  } else {
    set_error('商品が購入できませんでした。');
    redirect_to(CART_URL);
  }

  $total_price = sum_carts($carts);
}

redirect_to(HOME_URL);
include_once '../view/finish_view.php';