<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'cart.php';


function regist_order($db, $user_id) {
    $params = [
        $user_id
    ];
    $sql = 'INSERT INTO orders (user_id, created) 
            VALUES (?, NOW())';
    return execute_query($db, $sql, $params);
}

function regist_order_details($db, $item_name, $price, $amount) {
    $params = [
        $item_name,
        $price,
        $amount
    ];
    $sql = 'INSERT INTO order_details (name, price, amount) 
            VALUES (?, ?, ?)';
    return execute_query($db, $sql, $params);
}

function regist_purchase_carts_transaction($db, $user, $carts) {
    $db->beginTransaction();
    if (purchase_carts($db, $carts) && regist_order($db, $user['user_id'])) {
        foreach ($carts as $cart) {
            if (regist_order_details($db, $cart['name'], $cart['price'], $cart['amount']) === false) {
                $db->rollback();
                return false;
            }
        }
        $db->commit();
        return true;
    } else {
        $db->rollback();
        return false; 
    }
}
?>