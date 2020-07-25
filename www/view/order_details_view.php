<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order.css'); ?>">
</head>
<body>
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>

  <div class="container">
    <h1>購入明細</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>


    <?php if (is_array($order_details) === true) {?>
      <p>注文番号:<?php print $order_details[0]['order_id']; ?></p>
      <p>購入日時:<?php print $order_details[0]['created']; ?></p>
      <p>合計金額:<?php print number_format($total_price); ?>円</p>
      <table class="table table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order_details as $order){ ?>
          <tr>
            <td><?php print($order['name']); ?></td>
            <td><?php print(number_format($order['price'])); ?>円</td>
            <td><?php print($order['amount']); ?></td>
            <td><?php print(number_format($order['price*amount'])); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入明細が見つかりません。</p>
    <?php } ?> 
  </div>
</body>
</html>