CREATE TABLE IF NOT EXISTS `sf_ccb` (
  `application_name` varchar(30) NOT NULL,
  `database_version` int(11) NOT NULL,
  `upgrade_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO sf_ccb
SELECT 'prm', 0, now()
FROM information_schema.tables 
WHERE table_name = 'prm_contact';

CREATE TABLE IF NOT EXISTS `prm_ccb` (
  `database_version` int(11) NOT NULL,
  `upgrade_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO sf_ccb
SELECT 'prm', database_version + 1, upgrade_date
FROM `prm_ccb`;

INSERT INTO sf_ccb
SELECT 'unp', 0, now()
FROM information_schema.tables 
WHERE table_name = 'unp_notes';

INSERT INTO sf_ccb
SELECT 'gfc', 0, now()
FROM information_schema.tables 
WHERE table_name = 'gfc_records';
