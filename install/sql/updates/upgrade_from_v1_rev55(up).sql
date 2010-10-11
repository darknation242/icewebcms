SET FOREIGN_KEY_CHECKS=0;
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

ALTER TABLE `donations_template` 
ADD `amount` int(3) NOT NULL default '1';