<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1497421764.
 * Generated on 2017-06-14 09:29:24 by root
 */
class PropelMigration_1497421764
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

CREATE TABLE `stat`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `entity_id` int(11) unsigned NOT NULL,
    `city_id` int(11) unsigned NOT NULL,
    `count` int(11) unsigned DEFAULT 0,
    `fake_count` int(11) unsigned DEFAULT 0,
    `date` DATE NOT NULL,
    `type` enum(\'firm\',\'group\',\'keyword\'),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `entity_city_date_type` (`entity_id`, `city_id`, `date`, `type`),
    INDEX `stat_fi_be3335` (`city_id`)
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

DROP TABLE IF EXISTS `stat`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}