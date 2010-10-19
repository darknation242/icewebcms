SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `acc_creation_captcha`
-- ----------------------------
DROP TABLE IF EXISTS `acc_creation_captcha`;
CREATE TABLE `acc_creation_captcha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(200) NOT NULL DEFAULT '',
  `key` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of acc_creation_captcha
-- ----------------------------

-- ----------------------------
-- Table structure for `account_extend`
-- ----------------------------
DROP TABLE IF EXISTS `account_extend`;
CREATE TABLE `account_extend` (
  `account_id` int(10) unsigned NOT NULL,
  `account_level` smallint(3) NOT NULL DEFAULT '1',
  `theme` smallint(3) NOT NULL DEFAULT '0',
  `last_visit` int(25) DEFAULT NULL,
  `registration_ip` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `activation_code` bigint(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `secret_q1` text,
  `secret_a1` text,
  `secret_q2` text,
  `secret_a2` text,
  `hide_email` smallint(3) NOT NULL DEFAULT '0',
  `web_points` int(3) NOT NULL DEFAULT '0',
  `points_earned` smallint(5) NOT NULL DEFAULT '0',
  `points_spent` smallint(5) NOT NULL DEFAULT '0',
  `total_donations` varchar(5) NOT NULL DEFAULT '0.00',
  `total_votes` smallint(5) NOT NULL DEFAULT '0',
  `gender` int(3) NOT NULL DEFAULT '0',
  `location` varchar(255) DEFAULT NULL,
  `msn` varchar(255) DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of account_extend
-- ----------------------------

-- ----------------------------
-- Table structure for `account_groups`
-- ----------------------------
DROP TABLE IF EXISTS `account_groups`;
CREATE TABLE `account_groups` (
  `account_level` smallint(2) NOT NULL DEFAULT '1',
  `title` text,
  PRIMARY KEY (`account_level`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of account_groups
-- ----------------------------
INSERT INTO `account_groups` VALUES ('1', 'Guest');
INSERT INTO `account_groups` VALUES ('2', 'Member');
INSERT INTO `account_groups` VALUES ('3', 'Admin');
INSERT INTO `account_groups` VALUES ('4', 'Super Admin');
INSERT INTO `account_groups` VALUES ('5', 'Banned');

-- ----------------------------
-- Table structure for `account_keys`
-- ----------------------------
DROP TABLE IF EXISTS `account_keys`;
CREATE TABLE `account_keys` (
  `id` int(11) unsigned NOT NULL,
  `key` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `assign_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- ----------------------------
-- Records of account_keys
-- ----------------------------

-- ----------------------------
-- Table structure for `gallery`
-- ----------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `img` text NOT NULL,
  `comment` text NOT NULL,
  `autor` text NOT NULL,
  `date` date NOT NULL,
  `cat` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of gallery
-- ----------------------------
INSERT INTO `gallery` VALUES ('1', 'Mangosweb_wall.jpg', 'Test Wallpaper', 'MangosWeb', '0000-00-00', 'wallpaper');
INSERT INTO `gallery` VALUES ('2', 'Mangosweb_scr.jpg', 'Test Screenshot', 'MangosWeb', '0000-00-00', 'screenshot');

-- ----------------------------
-- Table structure for `gallery_ssotd`
-- ----------------------------
DROP TABLE IF EXISTS `gallery_ssotd`;
CREATE TABLE `gallery_ssotd` (
  `image` varchar(50) NOT NULL,
  `date` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gallery_ssotd
-- ----------------------------
INSERT INTO `gallery_ssotd` VALUES ('Mangosweb_scr.jpg', '10.10.19');

-- ----------------------------
-- Table structure for `iceweb_version`
-- ----------------------------
DROP TABLE IF EXISTS `iceweb_version`;
CREATE TABLE `iceweb_version` (
  `dbver` varchar(20) NOT NULL DEFAULT '',
  `dbdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`dbver`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of iceweb_version
-- ----------------------------
INSERT INTO `iceweb_version` VALUES ('1.0', '0');

-- ----------------------------
-- Table structure for `menu_items`
-- ----------------------------
DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `menu_id` int(3) NOT NULL DEFAULT '1',
  `link_title` varchar(100) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `order` int(3) NOT NULL DEFAULT '1',
  `account_level` int(3) NOT NULL DEFAULT '1',
  `guest_only` int(3) NOT NULL DEFAULT '0',
  `id` int(3) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of menu_items
-- ----------------------------
INSERT INTO `menu_items` VALUES ('1', 'Archived News', 'index.php', '1', '1', '0', '1');
INSERT INTO `menu_items` VALUES ('1', 'RSS', 'rss.php', '2', '1', '0', '2');
INSERT INTO `menu_items` VALUES ('2', 'Register', 'index.php?p=account&sub=register', '1', '1', '1', '3');

-- ----------------------------
-- Table structure for `pms`
-- ----------------------------
DROP TABLE IF EXISTS `pms`;
CREATE TABLE `pms` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `message` text,
  `sender_id` int(8) unsigned NOT NULL DEFAULT '0',
  `posted` int(10) unsigned NOT NULL DEFAULT '0',
  `sender_ip` varchar(15) DEFAULT '0.0.0.0',
  `showed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pms
-- ----------------------------

-- ----------------------------
-- Table structure for `secret_questions`
-- ----------------------------
DROP TABLE IF EXISTS `secret_questions`;
CREATE TABLE `secret_questions` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `question` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of secret_questions
-- ----------------------------
INSERT INTO `secret_questions` VALUES ('1', 'What is your mothers maiden name?');

-- ----------------------------
-- Table structure for `shop_items`
-- ----------------------------
DROP TABLE IF EXISTS `shop_items`;
CREATE TABLE `shop_items` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `item_number` varchar(255) NOT NULL DEFAULT '0',
  `itemset` int(10) NOT NULL DEFAULT '0',
  `gold` int(25) NOT NULL DEFAULT '0',
  `quanity` int(25) NOT NULL DEFAULT '1',
  `desc` varchar(255) DEFAULT NULL,
  `wp_cost` varchar(5) NOT NULL DEFAULT '0',
  `donation_cost` varchar(5) NOT NULL DEFAULT '0.00',
  `realms` int(100) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of shop_items
-- ----------------------------

-- ----------------------------
-- Table structure for `site_faq`
-- ----------------------------
DROP TABLE IF EXISTS `site_faq`;
CREATE TABLE `site_faq` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of site_faq
-- ----------------------------

-- ----------------------------
-- Table structure for `site_news`
-- ----------------------------
DROP TABLE IF EXISTS `site_news`;
CREATE TABLE `site_news` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `title` text,
  `message` longtext,
  `posted_by` text,
  `post_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of site_news
-- ----------------------------
INSERT INTO `site_news` VALUES ('5', 'Welcome!', '<center><b><p>Thank you for installing IceWeb CMS!</p></b> <p>Please login with your Admin account username and password to configure the CMS further.</p></center>', 'KeysCMS Dev Team', null);

-- ----------------------------
-- Table structure for `site_regkeys`
-- ----------------------------
DROP TABLE IF EXISTS `site_regkeys`;
CREATE TABLE `site_regkeys` (
  `id` smallint(9) NOT NULL DEFAULT '0',
  `key` int(255) DEFAULT '0',
  `used` smallint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of site_regkeys
-- ----------------------------

-- ----------------------------
-- Insert account data from "account" table
-- ----------------------------
INSERT INTO `account_extend` (`account_id`) SELECT account.id FROM account;

--
-- Add dbinfo to realmlist table
-- Very important that this is in the end, along with ADD ALTERS. Because if
-- file gets applied again, it gets an error here.
--
ALTER TABLE `realmlist` 
ADD `dbinfo` VARCHAR( 355 ) NOT NULL default 'username;password;3306;127.0.0.1;DBWorld;DBCharacter' COMMENT 'Database info to THIS row',
ADD `ra_info` VARCHAR( 355 ) NOT NULL default 'type;port;username;password';
