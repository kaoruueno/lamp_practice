CREATE TABLE orders (
  order_id INT(11) AUTO_INCREMENT,
  user_id INT(11),
  created DATETIME,
  primary key(order_id)
);

CREATE TABLE order_details (
  order_id INT(11),
  name VARCHAR(100) COLLATE utf8_general_ci,
  price INT(11),
  amount INT(11)
);