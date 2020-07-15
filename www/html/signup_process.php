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
  $name = get_post('name');
  $password = get_post('password');
  $password_confirmation = get_post('password_confirmation');
  $token = get_post('token');

  if (is_valid_csrf_token($token) === true) {
    $db = get_db_connect();

    try{
      $result = regist_user($db, $name, $password, $password_confirmation);
      if( $result=== false){
        set_error('ユーザー登録に失敗しました。');
        redirect_to(SIGNUP_URL);
      }
    }catch(PDOException $e){
      set_error('ユーザー登録に失敗しました。');
      redirect_to(SIGNUP_URL);
    }

    set_message('ユーザー登録が完了しました。');
    login_as($db, $name, $password);
    redirect_to(HOME_URL);
  }
}
redirect_to(LOGIN_URL);