ALTER TABLE `{TABLEPREFIX}user` ADD `role` INT NOT NULL AFTER `subscription_date`;

INSERT INTO `{TABLEPREFIX}user` (`user_id`, `email`, `password`, `subscription_date`, `role`, `name`, `culture`, `read_only`, `duo_id`) VALUES ('1bd821a8-e5d5-11e4-b1a2-e4d53de1ede6', '', MD5('admin'), CURRENT_DATE(), '2', 'Administrateur', 'fr-FR', '0', '');
