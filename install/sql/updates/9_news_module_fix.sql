-- ----------------------------
-- Table structure for `f_attachs`
-- ----------------------------
DROP TABLE IF EXISTS `f_attachs`;
CREATE TABLE `f_attachs` (
  `attach_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attach_file` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `attach_location` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `attach_hits` int(10) NOT NULL DEFAULT '0',
  `attach_date` int(10) NOT NULL,
  `attach_tid` int(10) unsigned NOT NULL DEFAULT '0',
  `attach_member_id` int(8) unsigned NOT NULL,
  `attach_filesize` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attach_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of f_attachs
-- ----------------------------

-- ----------------------------
-- Table structure for `f_categories`
-- ----------------------------
DROP TABLE IF EXISTS `f_categories`;
CREATE TABLE `f_categories` (
  `cat_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL DEFAULT 'New Category',
  `cat_disp_position` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of f_categories
-- ----------------------------
INSERT INTO `f_categories` VALUES ('1', 'News and Updates', '1');
INSERT INTO `f_categories` VALUES ('2', 'Bug Tracker', '2');

-- ----------------------------
-- Table structure for `f_forums`
-- ----------------------------
DROP TABLE IF EXISTS `f_forums`;
CREATE TABLE `f_forums` (
  `forum_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `forum_name` varchar(255) NOT NULL DEFAULT 'New forum',
  `forum_desc` varchar(255) DEFAULT NULL,
  `redirect_url` varchar(200) DEFAULT NULL,
  `moderators` varchar(255) DEFAULT NULL,
  `num_topics` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `num_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_topic_id` int(10) unsigned DEFAULT NULL,
  `disp_position` smallint(6) NOT NULL DEFAULT '0',
  `quick_reply` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`forum_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of f_forums
-- ----------------------------
INSERT INTO `f_forums` VALUES ('1', 'MangosWeb Enhanced News Module', 'News for your server', null, null, '1', '1', '1', '1', '0', '0', '0', '1');
INSERT INTO `f_forums` VALUES ('2', 'Bugs', 'A list of the servers bugs', null, null, '0', '0', null, '1', '0', '0', '0', '2');

-- ----------------------------
-- Table structure for `f_markread`
-- ----------------------------
DROP TABLE IF EXISTS `f_markread`;
CREATE TABLE `f_markread` (
  `marker_member_id` int(8) unsigned NOT NULL DEFAULT '0',
  `marker_forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `marker_last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `marker_unread` smallint(5) NOT NULL DEFAULT '0',
  `marker_topics_read` text NOT NULL,
  `marker_last_cleared` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `marker_forum_id` (`marker_forum_id`,`marker_member_id`),
  KEY `marker_member_id` (`marker_member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of f_markread
-- ----------------------------

-- ----------------------------
-- Table structure for `f_posts`
-- ----------------------------
DROP TABLE IF EXISTS `f_posts`;
CREATE TABLE `f_posts` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poster` varchar(12) NOT NULL,
  `poster_id` int(8) unsigned NOT NULL DEFAULT '0',
  `poster_ip` varchar(15) DEFAULT NULL,
  `poster_character_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `posted` int(10) unsigned NOT NULL DEFAULT '0',
  `edited` int(10) unsigned DEFAULT NULL,
  `edited_by` varchar(30) DEFAULT NULL,
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of f_posts
-- ----------------------------
INSERT INTO `f_posts` VALUES ('1', 'wilson212', '6', '127.0.0.1', '6', 'Welcome to your new MangosWeb Enhanced v2. In order to delete this test news, go &quot;Admin panel -&gt; Edit News -&gt; \'click on this news title\' -&gt; and hit the &quot;DELETE THIS NEWS TOPIC&quot; button.&quot; Good luck <img src=\"images/smiles/biggrin.gif\" align=\"absmiddle\">', '1280934760', null, null, '1');

-- ----------------------------
-- Table structure for `f_topics`
-- ----------------------------
DROP TABLE IF EXISTS `f_topics`;
CREATE TABLE `f_topics` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_poster` varchar(12) NOT NULL,
  `topic_poster_id` int(8) unsigned NOT NULL,
  `topic_name` varchar(255) NOT NULL,
  `topic_posted` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_poster` varchar(200) DEFAULT NULL,
  `num_views` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `num_replies` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `redirect_url` varchar(200) DEFAULT NULL,
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of f_topics
-- ----------------------------
INSERT INTO `f_topics` VALUES ('1', 'wilson212', '6', 'Welcome To Your New MangosWeb Enhanced v2!', '1280934760', '1280934760', '1', 'wilson212', '0', '1', '0', '0', null, '1');