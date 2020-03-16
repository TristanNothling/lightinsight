SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `npaaykwvxc`;
CREATE DATABASE `npaaykwvxc` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `npaaykwvxc`;

DROP TABLE IF EXISTS `cqeiq_users`;
CREATE TABLE `cqeiq_users` (
  `rqipo_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `vnaik_email` varchar(128) NOT NULL,
  `dfpcc_validated_email` tinyint(1) NOT NULL DEFAULT '0',
  `oifgh_password` varchar(128) NOT NULL,
  `btnyv_salt` varchar(32) NOT NULL,
  `btasd_reg_datetime` datetime NOT NULL,
  `pjkla_last_login` datetime NOT NULL,
  `tgrrq_login_attempts` smallint(6) NOT NULL,
  `hhyyi_locked` tinyint(1) NOT NULL DEFAULT '0',
  `nvrie_new_password_hash` varchar(128) NOT NULL,
  `wefef_validate_email_hash` varchar(128) NOT NULL,
  `nvruo_mfa_code` varchar(4) NOT NULL,
  `eglaa_mfa_expires` datetime NOT NULL,
  `vrnxx_mfa_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `zxcpo_config` varchar(512) NOT NULL,
  `asfdf_active_sub` tinyint(1) NOT NULL DEFAULT '0',
  `sdgzp_stripe_id` varchar(64) NOT NULL,
  `nvrpp_first_name` varchar(64) NOT NULL,
  `nvrpp_last_name` varchar(128) NOT NULL,
  `etnvc_current_bal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`rqipo_id`),
  UNIQUE KEY `rqipo_id` (`rqipo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cqeiq_users` (`rqipo_id`, `vnaik_email`, `dfpcc_validated_email`, `oifgh_password`, `btnyv_salt`, `btasd_reg_datetime`, `pjkla_last_login`, `tgrrq_login_attempts`, `hhyyi_locked`, `nvrie_new_password_hash`, `wefef_validate_email_hash`, `nvruo_mfa_code`, `eglaa_mfa_expires`, `vrnxx_mfa_enabled`, `zxcpo_config`, `asfdf_active_sub`, `sdgzp_stripe_id`, `nvrpp_first_name`, `nvrpp_last_name`, `etnvc_current_bal`) VALUES
(1, 'tristan@lightinsight.app', 1,  '0fda37b79643c757a72d6816d4d2960b24b15c18582c3f528569355272c398f111625e47b25095f0d6d8abd09c8089052103ad41bef37a9353df2fb40cd99b95', 'vnru8d93', '2020-02-09 14:22:06',  '0000-00-00 00:00:00',  0,  0,  '', '', '', '0000-00-00 00:00:00',  0,  '', 1,  '', 'Tristan',  'Nothling', 346.99),
(2001,  'tcnwilson@hotmail.co.uk',  1,  '6f7a04993a74a49e7df823a8ae8f59403fd47f4b3f051823ea04625d3a6db5cc16a58ca200311791885fd5f15f6a68f965e621abf8e8e476b95841c0d5095da8', 'fh347cna', '2020-03-10 16:43:32',  '0000-00-00 00:00:00',  0,  0,  '', '', '', '0000-00-00 00:00:00',  0,  '', 1,  '', 'Teagan', 'Wilson', -1400.00);

DROP TABLE IF EXISTS `asdfz_sessions`;
CREATE TABLE `asdfz_sessions` (
  `vrbty_session_token` varchar(64) NOT NULL,
  `sadkp_id` int(12) NOT NULL AUTO_INCREMENT,
  `jweoz_belongs_to` int(8) unsigned NOT NULL,
  `plzxa_user_agent` varchar(128) NOT NULL,
  `inuaq_ip_address` varchar(64) NOT NULL,
  `asdqw_cookie` varchar(128) NOT NULL,
  `fgyua_start` datetime NOT NULL,
  `fgyua_expires` datetime NOT NULL COMMENT 'Set to 15 minutes after last interaction',
  PRIMARY KEY (`sadkp_id`),
  KEY `jweoz_belongs_to` (`jweoz_belongs_to`),
  CONSTRAINT `asdfz_sessions_ibfk_1` FOREIGN KEY (`jweoz_belongs_to`) REFERENCES `cqeiq_users` (`rqipo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ivdbk_ip_attemps`;
CREATE TABLE `ivdbk_ip_attemps` (
  `alwro_id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `jijxc_remote_addr` varchar(45) NOT NULL,
  `njbgx_x_forwarded_for` varchar(45) NOT NULL,
  `zxcna_attempts` int(6) NOT NULL,
  `kkmzx_blocked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`alwro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `jwrpa_categories`;
CREATE TABLE `jwrpa_categories` (
  `aszcp_id` int(4) NOT NULL AUTO_INCREMENT,
  `afkvx_name` varchar(64) NOT NULL,
  `hnccp_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`aszcp_id`),
  UNIQUE KEY `aszcp_id` (`aszcp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `jwrpa_categories` (`aszcp_id`, `afkvx_name`, `hnccp_type`) VALUES
(1,	'General Spend',	0),
(2,	'Salary',	1);

DROP TABLE IF EXISTS `nnbca_recurring_transactions`;
CREATE TABLE `nnbca_recurring_transactions` (
  `qwepo_id` int(8) unsigned NOT NULL,
  `dbfxv_type` int(2) unsigned NOT NULL,
  `bsdjw_repeat_type` int(2) unsigned NOT NULL COMMENT '1:Certain date every month, 2:Every x days',
  `etrhc_repeat` int(4) unsigned NOT NULL COMMENT 'Either the date of month, or the amount of days per repeating',
  `xcvbl_active` tinyint(1) unsigned NOT NULL,
  `egbvv_start_date` date NOT NULL,
  `vbzpp_occurences` int(4) NOT NULL DEFAULT '0' COMMENT 'if 0, unlimited',
  `tiyrh_value_sig` binary(128) NOT NULL,
  `egtrr_belongs_to` int(8) unsigned DEFAULT NULL,
  `hatrx_category` int(4) DEFAULT NULL,
  `jwena_description` binary(128) NOT NULL,
  PRIMARY KEY (`qwepo_id`),
  UNIQUE KEY `qwepo_id` (`qwepo_id`),
  KEY `egtrr_belongs_to` (`egtrr_belongs_to`),
  KEY `hatrx_category` (`hatrx_category`),
  CONSTRAINT `nnbca_recurring_transactions_ibfk_2` FOREIGN KEY (`egtrr_belongs_to`) REFERENCES `cqeiq_users` (`rqipo_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `nnbca_recurring_transactions_ibfk_3` FOREIGN KEY (`hatrx_category`) REFERENCES `jwrpa_categories` (`aszcp_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `plzna_transactions`;
CREATE TABLE `plzna_transactions` (
  `zeqwe_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `askdl_value_sig` binary(128) NOT NULL,
  `wqeok_description` binary(255) NOT NULL,
  `xcnap_spent` tinyint(1) NOT NULL,
  `vrbtn_belongs_to` int(8) unsigned DEFAULT NULL,
  `jwecv_date` date NOT NULL,
  `haasx_category` int(4) DEFAULT NULL,
  `asdjl_recurring_parent` int(8) unsigned DEFAULT NULL,
  `jkqwe_type` tinyint(1) unsigned NOT NULL,
  `oqwaa_enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`zeqwe_id`),
  UNIQUE KEY `zeqwe_id` (`zeqwe_id`),
  KEY `vrbtn_belongs_to` (`vrbtn_belongs_to`),
  KEY `haasx_category` (`haasx_category`),
  KEY `asdjl_recurring_parent` (`asdjl_recurring_parent`),
  CONSTRAINT `plzna_transactions_ibfk_1` FOREIGN KEY (`vrbtn_belongs_to`) REFERENCES `cqeiq_users` (`rqipo_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `plzna_transactions_ibfk_3` FOREIGN KEY (`haasx_category`) REFERENCES `jwrpa_categories` (`aszcp_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `plzna_transactions_ibfk_4` FOREIGN KEY (`asdjl_recurring_parent`) REFERENCES `nnbca_recurring_transactions` (`qwepo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
