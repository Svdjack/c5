<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1497415478.
 * Generated on 2017-06-14 07:44:38 by root
 */
class PropelMigration_1497415478
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `firm_up`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `firm_id` INTEGER,
    `time_start` int(10) unsigned DEFAULT 0 NOT NULL,
    `time_end` int(10) unsigned DEFAULT 0 NOT NULL,
    `cash` int(10) unsigned DEFAULT 0 NOT NULL,
    `type` VARCHAR(255) DEFAULT \'\' NOT NULL,
    `email` VARCHAR(255) DEFAULT \'\' NOT NULL,
    `status` int(10) unsigned DEFAULT 0 NOT NULL,
    `spam_type` int(10) unsigned DEFAULT 0 NOT NULL,
    `last_mail_send` int(10) unsigned DEFAULT 0 NOT NULL,
    `last_days` int(10) unsigned DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `firm_id_index` (`firm_id`),
    INDEX `status_index` (`status`),
    INDEX `time_start_index` (`time_start`),
    INDEX `time_end_index` (`time_end`)
) ENGINE=MyISAM;

CREATE TABLE `adv_server_firm_up`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `order_id` INTEGER,
    `status` int(10) unsigned DEFAULT 0 NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `url` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `status_index` (`status`),
    INDEX `adv_server_firm_up_fi_d12082` (`order_id`)
) ENGINE=MyISAM;

CREATE TABLE `adv_server_orders`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `months` INTEGER NOT NULL,
    `cash` INTEGER NOT NULL,
    `type` VARCHAR(255) DEFAULT \'\' NOT NULL,
    `city_url` VARCHAR(255) NOT NULL,
    `firm_id` INTEGER NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `status` int(10) unsigned DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id` (`id`),
    INDEX `adv_server_orders_fi_049fe5` (`firm_id`)
) ENGINE=MyISAM;

CREATE TABLE `adv_server_prices`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `city_id` int(10) unsigned,
    `city_url` VARCHAR(100),
    `data` BLOB,
    PRIMARY KEY (`id`),
    INDEX `url_index` (`city_url`),
    INDEX `citi_id_index` (`city_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `firm_up`;

DROP TABLE IF EXISTS `adv_server_firm_up`;

DROP TABLE IF EXISTS `adv_server_orders`;

DROP TABLE IF EXISTS `adv_server_prices`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}