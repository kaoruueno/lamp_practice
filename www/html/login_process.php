<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
header('X-FRAME-OPTIONS: DENY');

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = get_post('token');
  $name = get_post('name');
  $password = get_post('password');

  if (is_valid_csrf_token($token) === true) {
    $db = get_db_connect();
  
    $user = login_as($db, $name, $password);
    if( $user === false){
      set_error('ログインに失敗しました。');
      redirect_to(LOGIN_URL);
    }
    
    set_message('ログインしました。');
    if ($user['type'] === USER_TYPE_ADMIN){
      redirect_to(ADMIN_URL);
    }
    redirect_to(HOME_URL);
  }
}
redirect_to(LOGIN_URL);