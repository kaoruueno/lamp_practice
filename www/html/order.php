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

$orders = get_all_orders($db, $user);
// dd($orders);

$token = get_csrf_token();
include_once VIEW_PATH . '/order_view.php';
