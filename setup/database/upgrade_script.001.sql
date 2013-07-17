CREATE TABLE IF NOT EXISTS `sf_user` (
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `sf_user` ADD `subscription_date` DATE NOT NULL;

ALTER TABLE `sf_user` ADD `user_id` BIGINT NOT NULL AUTO_INCREMENT FIRST ,
ADD PRIMARY KEY ( `user_id` ) ;

ALTER TABLE `sf_user` ADD UNIQUE (
`email`
);
