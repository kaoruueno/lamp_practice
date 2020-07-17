CREATE TABLE orders (
  order_id INT(11) AUTO_INCREMENT,
  user_id INT(11),
  created DATETIME,
  primary key(order_id)
);

CREATE TABLE order_details (
  order_id INT(11),
  cart_id INT(11),
  item_id INT(11),
  price INT(11)
);