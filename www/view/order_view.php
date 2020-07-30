<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order.css'); ?>">
</head>
<body>
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>

  <div class="container">
    <h1>購入履歴</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>



<?php if(count($orders) > 0){ ?>
      <table class="table table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文の合計金額</th>
            <th>購入明細</th>
          </tr>
        </thead>
        <tbody>
  <?php foreach($orders as $order){ ?>
          <tr>
            <td><?php print($order['order_id']); ?></td>
            <td><?php print($order['created']); ?></td>
            <td><?php print(number_format($order['SUM(price*amount)'])); ?>円</td>
            <td>
              <form method="post" action="order_details.php">
                <input type="submit" value="表示" class="btn btn-primary">
                <input type="hidden" name="order_id" value="<?php print $order['order_id']; ?>">
                <input type="hidden" name="token" value="<?php print $token; ?>">
              </form>
            </td>
          </tr>
  <?php } ?>
        </tbody>
      </table>
  <?php if ($display_count['min'] < $display_count['max']) { ?><!-- ページネーション(最大7ページ表示) -->
      <p><?php print $all_count; ?>件中 <?php print $display_count['min']; ?> - <?php print $display_count['max']; ?>件目の商品</p>
  <?php } else {?>
      <p><?php print $all_count; ?>件中 <?php print $display_count['min']; ?>件目の商品</p>
  <?php } ?>
      <section class="pagination">
  <?php if ($current_page > 4) { ?>
        <p><a href="?page=1">最初へ</a></p>
  <?php } ?>
  <?php if ($current_page > 1) { ?>
        <p><a href="?page=<?php print $current_page-1 ;?>">前へ</a></p>
  <?php } ?>
  <?php if ($current_page >= 4 && $current_page <= $all_page - 3) { ?>
    <?php for ($i = -3; $i <= 3; $i++) { ?>
      <?php if ($i !== 0) { ?>
        <p><a href="?page=<?php print $current_page+$i; ?>"><?php print $current_page+$i; ?></a></p>
      <?php } else { ?>
        <p class="current"><?php print $current_page; ?></p>
      <?php } ?>
    <?php } ?>
  <?php } else if ($current_page < 4) { ?>
    <?php for ($i = -3; $i <= 0; $i++) { ?>
      <?php if ($i === 0) { ?>
        <p class="current"><?php print $current_page; ?></p>
      <?php } else if ($current_page+$i > 0) { ?>
        <p><a href="?page=<?php print $current_page+$i; ?>"><?php print $current_page+$i; ?></a></p>
      <?php } ?>
    <?php } ?>
    <?php for ($i = 1; $i <= 7-$current_page; $i++) { ?>
      <?php if ($current_page+$i <= $all_page) { ?>
        <p><a href="?page=<?php print $current_page+$i; ?>"><?php print $current_page+$i; ?></a></p>
      <?php } ?>
    <?php } ?>
  <?php } else { ?>
    <?php for ($i = -6-$current_page+$all_page; $i <= 0; $i++) { ?>
      <?php if ($i === 0) { ?>
        <p class="current"><?php print $current_page; ?></p>
      <?php } else if ($current_page+$i > 0) { ?>
        <p><a href="?page=<?php print $current_page+$i; ?>"><?php print $current_page+$i; ?></a></p>
      <?php } ?>
    <?php } ?>
    <?php for ($i = 1; $i <= 3; $i++) { ?>
      <?php if ($current_page+$i <= $all_page) { ?>
        <p><a href="?page=<?php print $current_page+$i; ?>"><?php print $current_page+$i; ?></a></p>
      <?php } ?>
    <?php } ?>
  <?php } ?>
  <?php if ($current_page < $all_page) { ?>
        <p><a href="?page=<?php print $current_page+1; ?>">次へ</a></p>
  <?php } ?>
  <?php if ($all_page - $current_page > 3) { ?>
        <p><a href="?page=<?php print $all_page; ?>">最後へ</a></p>
  <?php } ?>
      </section><!-- ページネーション終了 -->
<?php } else { ?>
      <p>購入履歴はありません。</p>
<?php } ?> 
  </div>
</body>
</html>