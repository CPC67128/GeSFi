ALTER TABLE `sf_user` ADD `user_uuid` VARCHAR( 40 ) NOT NULL AFTER `user_id` ;

update sf_user set user_uuid = UUID();

ALTER TABLE `sf_user`  DROP `user_id`;

ALTER TABLE `sf_user` CHANGE `user_uuid` `user_id` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `sf_user` ADD PRIMARY KEY ( `user_id` ) ;

ALTER TABLE `sf_gfc_configuration` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_gfc_records` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_attribute` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_company` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_company_attribute` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_configuration` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_contact` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_contact_attribute` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_file` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_note` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_relation_contact_to_contact` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_prm_relation_type` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_unp_notes` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sf_user_connection` CHANGE `user_id` `user_id` VARCHAR( 40 ) NOT NULL ;

update sf_gfc_configuration set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_gfc_records set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_attribute set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_company set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_company_attribute set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_configuration set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_contact set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_contact_attribute set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_file set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_note set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_relation_contact_to_contact set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_prm_relation_type set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_unp_notes set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
update sf_user_connection set user_id = (select user_id from sf_user order by subscription_date desc limit 0, 1) ;
