CREATE TABLE `gaur_user_resets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL,
  `token` char(32) NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `expire` datetime NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
