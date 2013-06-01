
CREATE TABLE IF NOT EXISTS `sf_user_connection` (
  `user_id` bigint(20) NOT NULL,
  `connection_date_time` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `browser` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


