-- hi
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customer_order_id` varchar(255),
  `order_date` date,
  `member_id` int,
  `payment_method` enum('credit-card','line-pay'),
  `delivery_method` enum('home','7-11'),
  `order_district_id` int,
  `order_address` varchar(255),
  `recipient_name` varchar(255),
  `recipient_mobile` varchar(255),
  `member_carrier` int DEFAULT NULL,
  `recipient_invoice_carrier` varchar(50),
  `recipient_tax_id` varchar(50),
  `order_status` enum('unpaid','paid','expired','canceled','tallying','shipping','arrived','completed') DEFAULT 'unpaid',
  `created_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL
);
ALTER TABLE orders
ADD FOREIGN KEY (member_id) REFERENCES users(user_id);

ALTER TABLE orders
ADD FOREIGN KEY (order_district_id) REFERENCES district(id);



CREATE TABLE `order_details` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` int NOT NULL,
  `order_product_id` int NOT NULL,
  `order_quantity` int NOT NULL,
  `order_unit_price` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL
);

ALTER TABLE order_details
ADD FOREIGN KEY (order_id) REFERENCES orders(id);

ALTER TABLE order_details
ADD FOREIGN KEY (order_product_id) REFERENCES product_management(product_id);


ALTER TABLE product_management
ADD FOREIGN KEY (category_id) REFERENCES product_category(category_id);



INSERT INTO `product_category`(`category_name`, `created_at`, `last_modified_at`) VALUES
('策略遊戲', NOW(), NOW()),
('家庭遊戲', NOW(), NOW()),
('卡牌遊戲', NOW(), NOW()),
('冒險遊戲', NOW(), NOW()),
('派對遊戲', NOW(), NOW());

INSERT INTO `product_warehousing`(`product_id`, `quantity`, `created_at`, `last_modified_at`) VALUES
(1, 24, NOW(), NOW()), (1, 78, NOW(), NOW()),
(2, 35, NOW(), NOW()), (2, 95, NOW(), NOW()),
(3, 40, NOW(), NOW()), (3, 85, NOW(), NOW()),
(4, 22, NOW(), NOW()), (4, 100, NOW(), NOW()),
(5, 45, NOW(), NOW()), (5, 55, NOW(), NOW()),
(6, 25, NOW(), NOW()), (6, 90, NOW(), NOW()),
(7, 50, NOW(), NOW()), (7, 60, NOW(), NOW()),
(8, 70, NOW(), NOW()), (8, 80, NOW(), NOW()),
(9, 20, NOW(), NOW()), (9, 30, NOW(), NOW()),
(10, 44, NOW(), NOW()), (10, 66, NOW(), NOW()),
(11, 29, NOW(), NOW()), (11, 99, NOW(), NOW()),
(12, 31, NOW(), NOW()), (12, 77, NOW(), NOW()),
(13, 33, NOW(), NOW()), (13, 88, NOW(), NOW()),
(14, 22, NOW(), NOW()), (14, 84, NOW(), NOW()),
(15, 21, NOW(), NOW()), (15, 91, NOW(), NOW()),
(16, 45, NOW(), NOW()), (16, 75, NOW(), NOW()),
(17, 50, NOW(), NOW()), (17, 65, NOW(), NOW()),
(18, 55, NOW(), NOW()), (18, 60, NOW(), NOW()),
(19, 35, NOW(), NOW()), (19, 95, NOW(), NOW()),
(20, 40, NOW(), NOW()), (20, 85, NOW(), NOW());



