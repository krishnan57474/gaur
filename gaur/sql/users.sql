CREATE TABLE `gaur_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` bit(1) NOT NULL,
  `activation` bit(1) NOT NULL,
  `admin` bit(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_visited` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;