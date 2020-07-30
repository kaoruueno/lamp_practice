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

$all_count = get_all_orders_count($db, $user);
$all_page = (int)ceil($all_count / 8);
$get_page = get_get('page');
$current_page = is_valid_get_page($get_page, $all_page);
var_dump($all_count);
var_dump($all_page);
var_dump($current_page);
$display_count = get_display_count($current_page, $all_page, $all_count);

$orders = get_limit_orders($db, $user, $current_page);

$token = get_csrf_token();
include_once VIEW_PATH . '/order_view.php';
