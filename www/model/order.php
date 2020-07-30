<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'user.php';


function regist_order($db, $user_id) {
    $params = [
        $user_id
    ];
    $sql = 'INSERT INTO orders (user_id, created) 
            VALUES (?, NOW())';
    return execute_query($db, $sql, $params);
}

function regist_order_details($db, $order_id, $item_name, $price, $amount) {
    $params = [
        $order_id,
        $item_name,
        $price,
        $amount
    ];
    $sql = 'INSERT INTO order_details (order_id, name, price, amount) 
            VALUES (?, ?, ?, ?)';
    return execute_query($db, $sql, $params);
}

function regist_purchase_carts_transaction($db, $user, $carts) {
    $db->beginTransaction();
    purchase_carts($db, $carts);
    regist_order($db, $user['user_id']);
    $order_id = $db->lastInsertId('order_id');
    foreach ($carts as $cart) {
      regist_order_details($db, $order_id, $cart['name'], $cart['price'], $cart['amount']);
    }
    if (has_error() === false) {
      $db->commit();
      return true;
    } else {
      $db->rollback();
      return false; 
    }
}

/** ページネーション用の購入履歴取得関数(get_limit_ordersとセット) */
function get_all_orders_count($db, $user){
  $params = array();
  $sql = '
    SELECT
      COUNT(*)
    FROM
      orders
  ';
  if (is_admin($user) === false) {
    $params[] = $user['user_id'];
    $sql .= '
      WHERE user_id = ?
    ';    
  }
  $result = fetch_query($db, $sql, $params);
  return $result['COUNT(*)'];
}

function get_limit_orders($db, $user, $current_page = 1){
  $skip_count = ($current_page-1)*8;
  $params = array();
  $sql = '
    SELECT
      orders.order_id, 
      created,
      SUM(price*amount)
    FROM
      orders
      INNER JOIN order_details
      ON orders.order_id = order_details.order_id
  ';
  if (is_admin($user) === false) {
    $params[] = $user['user_id'];
    $sql .= '
      WHERE user_id = ?
    ';    
  }
  $params[] = $skip_count;
  $sql .= '
    GROUP BY 
      order_id
    ORDER BY
      orders.order_id DESC
    LIMIT ?, 8
  ';
  return fetch_all_query($db, $sql, $params);
}




function get_order_details($db, $user, $order_id){
  $params = [$order_id];
  $sql = '
    SELECT
      name,
      price,
      amount,
      price*amount,
      orders.order_id, 
      created
    FROM
      orders
      INNER JOIN order_details
      ON orders.order_id = order_details.order_id
    WHERE order_details.order_id = ?
  ';
  if (is_admin($user) === false) {
    $params[] = $user['user_id'];
    $sql .= '
      AND user_id = ?
    ';    
  }
  return fetch_all_query($db, $sql, $params);
}




// 指定テーブルのレコード件数取得関数
function get_all_records_count($db, $sql, $params = array()){
  // $params = [$table_name];
  // $sql = '
  //   SELECT
  //     COUNT(*)
  //   FROM
  //     ?
  // ';
  // if (is_admin($user) === false) {
  //   $params[] = $user['user_id'];
  //   $sql .= '
  //     WHERE user_id = ?
  //   ';    
  // }
  $result = fetch_query($db, create_count_sql($sql), $params);
  return $result['COUNT(*)'];
}

function create_count_sql($sql){
  $sql = preg_replace('/SELECT([\s ]*?.*?)*?FROM/ui','SELECT COUNT(*) FROM',$sql);
  $sql = preg_replace('/LIMIT([\s ]*.*)*/ui','',$sql);
  return $sql;
}
?>