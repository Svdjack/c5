
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- admin
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(255) NOT NULL,
    `hash` VARCHAR(40) NOT NULL,
    `last_login` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `firm_id` INTEGER NOT NULL,
    `user` VARCHAR(200) DEFAULT '0' NOT NULL,
    `email` VARCHAR(200) DEFAULT '' NOT NULL,
    `text` TEXT NOT NULL,
    `date` DATETIME NOT NULL,
    `score` TINYINT DEFAULT 0 NOT NULL,
    `ip` VARCHAR(100) DEFAULT '' NOT NULL,
    `edited` tinyint(3) unsigned DEFAULT 0 NOT NULL,
    `emotion` CHAR NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fid` (`firm_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- firm
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `firm`;

CREATE TABLE `firm`
(
    `id` INTEGER NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `official_name` VARCHAR(255),
    `subtitle` VARCHAR(255),
    `description` TEXT,
    `postal` VARCHAR(10),
    `address` VARCHAR(255) DEFAULT '' NOT NULL,
    `city_id` INTEGER(10) DEFAULT 0 NOT NULL,
    `street` VARCHAR(100),
    `home` VARCHAR(100),
    `office` VARCHAR(255),
    `worktime` VARCHAR(255),
    `views` INTEGER DEFAULT 0 NOT NULL,
    `lon` FLOAT(15,12) DEFAULT 0.000000000000,
    `lat` FLOAT(15,12) DEFAULT 0.000000000000,
    `random` int(10) unsigned DEFAULT 0 NOT NULL,
    `logo` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `city_id` (`city_id`, `random`),
    INDEX `city_index` (`city_id`),
    INDEX `city_last` (`city_id`, `id`),
    INDEX `city_name` (`city_id`, `name`),
    INDEX `city_pop` (`city_id`, `views`),
    INDEX `main_cat` (`city_id`),
    INDEX `rnd` (`random`),
    INDEX `search_index` (`name`, `official_name`, `description`(255)),
    INDEX `urlcity` (`city_id`),
    INDEX `view_sort` (`views`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- firm_childs
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `firm_childs`;

CREATE TABLE `firm_childs`
(
    `firm_id` INTEGER NOT NULL,
    `value` VARCHAR(255),
    INDEX `firm_childs_firm_id_index` (`firm_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- firm_contacts
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `firm_contacts`;

CREATE TABLE `firm_contacts`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `firm_id` INTEGER NOT NULL,
    `type` CHAR NOT NULL,
    `value` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `firm_id` (`id`),
    INDEX `firm_contacts_fi_049fe5` (`firm_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- firm_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `firm_group`;

CREATE TABLE `firm_group`
(
    `firm_id` int(10) unsigned NOT NULL,
    `group_id` int(10) unsigned NOT NULL,
    `city_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`firm_id`,`group_id`),
    INDEX `cid` (`city_id`),
    INDEX `gid` (`group_id`),
    INDEX `fid` (`firm_id`),
    INDEX `city_group_count` (`city_id`, `group_id`, `firm_id`),
    INDEX `city_group` (`city_id`, `group_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- firm_photos
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `firm_photos`;

CREATE TABLE `firm_photos`
(
    `firm_id` INTEGER DEFAULT 0,
    `photo` VARCHAR(255),
    INDEX `firm_photos_fi_049fe5` (`firm_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- firm_tags
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `firm_tags`;

CREATE TABLE `firm_tags`
(
    `firm_id` INTEGER DEFAULT 0 NOT NULL,
    `tag_id` INTEGER DEFAULT 0 NOT NULL,
    INDEX `firm_tags_tag_id_firm_id_index` (`tag_id`, `firm_id`),
    INDEX `firm_tags_tag_id_index` (`tag_id`),
    INDEX `firm_tags_firm_id_index` (`firm_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- firm_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `firm_user`;

CREATE TABLE `firm_user`
(
    `firm_id` int(10) unsigned NOT NULL,
    `user_id` INTEGER NOT NULL,
    PRIMARY KEY (`firm_id`,`user_id`),
    INDEX `fid` (`firm_id`),
    INDEX `uid` (`user_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- groups
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `parent` INTEGER DEFAULT 0 NOT NULL,
    `name` VARCHAR(255) DEFAULT '' NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `worktime` MEDIUMBLOB,
    `live` TEXT,
    PRIMARY KEY (`id`),
    INDEX `alias` (`url`),
    INDEX `parent` (`parent`),
    INDEX `name` (`name`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- jur_data
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `jur_data`;

CREATE TABLE `jur_data`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `rusprofile_id` INTEGER,
    `firm_id` INTEGER DEFAULT 0,
    `name` VARCHAR(255),
    `region` VARCHAR(127),
    `city` VARCHAR(127),
    `postal` VARCHAR(7),
    `address` VARCHAR(127),
    `director` VARCHAR(63),
    `phone` VARCHAR(127),
    `inn` VARCHAR(15),
    `okato` VARCHAR(15),
    `fsfr` VARCHAR(15),
    `ogrn` VARCHAR(15),
    `okpo` VARCHAR(15),
    `org_form` VARCHAR(63),
    `okogu` VARCHAR(63),
    `reg_date` VARCHAR(63),
    `is_liquidated` bit(1),
    `capital` VARCHAR(63),
    `activities` TEXT,
    `kpp` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `name` (`name`),
    INDEX `phone` (`phone`),
    INDEX `city` (`city`),
    INDEX `rusprofile_id` (`rusprofile_id`),
    INDEX `firm_id` (`firm_id`),
    INDEX `address` (`address`),
    INDEX `city_address` (`city`, `address`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- page
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page`
(
    `url` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `data` BLOB NOT NULL,
    UNIQUE INDEX `url` (`url`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- region
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `region`;

CREATE TABLE `region`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `area` int(10) unsigned,
    `telcode` smallint(5) unsigned,
    `timezone` VARCHAR(128),
    `name` VARCHAR(100) NOT NULL,
    `url` VARCHAR(100) NOT NULL,
    `count` INTEGER(10) DEFAULT 0 NOT NULL,
    `data` BLOB,
    `lon` FLOAT(10,7),
    `lat` FLOAT(10,7),
    PRIMARY KEY (`id`),
    INDEX `url` (`url`),
    INDEX `name` (`name`),
    INDEX `lon` (`lon`),
    INDEX `lat` (`lat`),
    INDEX `lat_lon` (`lat`, `lon`),
    INDEX `region_area_index` (`area`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- sessions
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions`
(
    `id` VARCHAR(32) DEFAULT '' NOT NULL,
    `value` VARCHAR(255) DEFAULT '' NOT NULL,
    `expire` int(11) unsigned NOT NULL,
    `ip` VARCHAR(15) DEFAULT 'undefined' NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MEMORY;

-- ---------------------------------------------------------------------
-- sites_data
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `sites_data`;

CREATE TABLE `sites_data`
(
    `url` VARCHAR(255) NOT NULL,
    `title` TEXT,
    `keywords` TEXT,
    `description` TEXT,
    `status` INTEGER(1),
    `screen` VARCHAR(255),
    `date` INTEGER,
    UNIQUE INDEX `url` (`url`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- tags
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `tag` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `tags_id_uindex` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- url_aliases
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `url_aliases`;

CREATE TABLE `url_aliases`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `source` VARCHAR(255) NOT NULL,
    `alias` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `alias_index` (`alias`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(255) NOT NULL,
    `hash` VARCHAR(32) NOT NULL,
    `email` VARCHAR(255) DEFAULT '' NOT NULL,
    `name` VARCHAR(255) DEFAULT '' NOT NULL,
    `last_login` int(10) unsigned DEFAULT 0 NOT NULL,
    `secret` VARCHAR(50) DEFAULT '' NOT NULL,
    `reg_date` INTEGER DEFAULT 0 NOT NULL,
    `ip` VARCHAR(50) DEFAULT '' NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
