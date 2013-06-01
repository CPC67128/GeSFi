ALTER TABLE `sf_user` ADD `read_only` BOOLEAN NOT NULL DEFAULT FALSE ;

update `sf_user` set read_only = 1 WHERE email = 'guest' ;