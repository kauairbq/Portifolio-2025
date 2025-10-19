CREATE DATABASE IF NOT EXISTS project_full_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE project_full_db;

CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `customer_sales_summary` AS select `c`.`id` AS `customer_id`,`c`.`name` AS `customer_name`,count(`s`.`id`) AS `number_of_tickets_purchased`,sum(`t`.`price`) AS `total_spent` from ((`customers` `c` left join `sales` `s` on((`c`.`id` = `s`.`customer_id`))) left join `tickets` `t` on((`s`.`ticket_id` = `t`.`id`))) group by `c`.`id`;

CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `event_details` AS select `e`.`id` AS `event_id`,`e`.`name` AS `event_name`,`e`.`description` AS `description`,`e`.`date` AS `date`,`e`.`time` AS `time`,`e`.`venue` AS `venue`,`e`.`capacity` AS `capacity`,count(`t`.`id`) AS `total_tickets`,sum((case when (`s`.`id` is not null) then 1 else 0 end)) AS `sold_tickets`,(count(`t`.`id`) - sum((case when (`s`.`id` is not null) then 1 else 0 end))) AS `available_tickets` from ((`events` `e` left join `tickets` `t` on((`e`.`id` = `t`.`event_id`))) left join `sales` `s` on((`t`.`id` = `s`.`ticket_id`))) group by `e`.`id`;

INSERT INTO event_details VALUES
('1','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','0','0','0'),
('2','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','0','0','0'),
('3','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','0','0','0'),
('4','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','0','0','0'),
('5','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','0','0','0'),
('6','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','0','0','0'),
('7','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','0','0','0'),
('8','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','0','0','0'),
('9','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','0','0','0'),
('10','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','0','0','0'),
('11','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','0','0','0'),
('12','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','0','0','0'),
('13','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','0','0','0'),
('14','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','0','0','0'),
('15','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','0','0','0'),
('16','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','0','0','0'),
('17','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','0','0','0'),
('18','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','0','0','0'),
('22','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','0','0','0'),
('23','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','0','0','0'),
('24','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','0','0','0'),
('25','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','0','0','0'),
('26','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','0','0','0'),
('27','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','0','0','0');

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `event_sales_summary` AS select `e`.`id` AS `event_id`,`e`.`name` AS `event_name`,count(`s`.`id`) AS `number_of_tickets_sold`,sum(`t`.`price`) AS `total_revenue` from ((`events` `e` left join `tickets` `t` on((`e`.`id` = `t`.`event_id`))) left join `sales` `s` on((`t`.`id` = `s`.`ticket_id`))) group by `e`.`id`;

INSERT INTO event_sales_summary VALUES
('1','Concerto de Rock','0',NULL),
('2','Festival de Jazz','0',NULL),
('3','Teatro: Hamlet','0',NULL),
('4','Exposição de Arte','0',NULL),
('5','Workshop de Fotografia','0',NULL),
('6','Show de Comédia','0',NULL),
('7','Concerto de Rock','0',NULL),
('8','Festival de Jazz','0',NULL),
('9','Teatro: Hamlet','0',NULL),
('10','Exposição de Arte','0',NULL),
('11','Workshop de Fotografia','0',NULL),
('12','Show de Comédia','0',NULL),
('13','Concerto de Rock','0',NULL),
('14','Festival de Jazz','0',NULL),
('15','Teatro: Hamlet','0',NULL),
('16','Exposição de Arte','0',NULL),
('17','Workshop de Fotografia','0',NULL),
('18','Show de Comédia','0',NULL),
('22','Concerto de Rock','0',NULL),
('23','Festival de Jazz','0',NULL),
('24','Teatro: Hamlet','0',NULL),
('25','Exposição de Arte','0',NULL),
('26','Workshop de Fotografia','0',NULL),
('27','Show de Comédia','0',NULL);

CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO events VALUES
('1','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','25.00'),
('2','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','40.00'),
('3','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','15.00'),
('4','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','10.00'),
('5','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','30.00'),
('6','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','20.00'),
('7','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','25.00'),
('8','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','40.00'),
('9','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','15.00'),
('10','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','10.00'),
('11','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','30.00'),
('12','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','20.00'),
('13','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','25.00'),
('14','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','40.00'),
('15','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','15.00'),
('16','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','10.00'),
('17','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','30.00'),
('18','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','20.00'),
('22','Concerto de Rock','Um incrível concerto de rock com bandas locais.','2024-12-01','20:00:00','Estádio Municipal','500','25.00'),
('23','Festival de Jazz','Festival anual de jazz com artistas internacionais.','2024-11-15','19:00:00','Centro Cultural','300','40.00'),
('24','Teatro: Hamlet','Adaptação moderna da obra de Shakespeare.','2024-10-20','21:00:00','Teatro Nacional','200','15.00'),
('25','Exposição de Arte','Exposição de arte contemporânea.','2024-09-10','10:00:00','Galeria de Arte','100','10.00'),
('26','Workshop de Fotografia','Aprenda técnicas avançadas de fotografia.','2024-08-25','14:00:00','Centro de Formação','50','30.00'),
('27','Show de Comédia','Noite de stand-up com comediantes famosos.','2024-07-30','20:30:00','Casa de Shows','150','20.00');

CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `purchases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO purchases VALUES
('1','2','1','1','25.00','2025-10-19 14:39:13'),
('2','2','7','1','25.00','2025-10-19 14:39:13');

CREATE TABLE `sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int DEFAULT NULL,
  `sale_date` datetime DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `service_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `seat_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tickets_status` AS select `t`.`id` AS `ticket_id`,`e`.`name` AS `event_name`,`t`.`price` AS `price`,`t`.`seat_number` AS `seat_number`,(case when (`s`.`id` is not null) then 'Sold' else 'Available' end) AS `status` from ((`tickets` `t` left join `events` `e` on((`t`.`event_id` = `e`.`id`))) left join `sales` `s` on((`t`.`id` = `s`.`ticket_id`)));

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default-profile-pic.jpg',
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users VALUES
('1','admin','admin@example.com','$2y$10$e6W3fXnqLDVr0lb7m26ctejZfa4w0wwVv8FU22fvD5XTObvjP6Rhm','default-profile-pic.jpg','admin','2025-10-19 14:30:10'),
('2','kauai rocha','kauai_lucas@hotmail.com','$2y$10$vda4aKEk2foskQE0jCIQLO4pq2ih4VEuTudTOdRDCj9hTqJ3hMVFK','default-profile-pic.jpg','admin','2025-10-19 14:32:28');

