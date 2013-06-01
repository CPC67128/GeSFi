delete from sf_user where user_id = 0;

INSERT INTO `sf_user` (`user_id`, `email`, `password`, `subscription_date`, `full_name`, `md5`) VALUES
(0, 'guest', '', '2012-09-14', 'Utilisateur invité', NULL);

update sf_user set user_id = 0 where email = 'guest';
