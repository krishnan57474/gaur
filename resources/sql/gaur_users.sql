CREATE TABLE `gaur_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `activation` tinyint(1) UNSIGNED NOT NULL,
  `admin` tinyint(1) UNSIGNED NOT NULL,
  `date_added` datetime NOT NULL,
  `last_visited` datetime NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
