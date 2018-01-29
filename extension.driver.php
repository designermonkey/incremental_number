<?php

class extension_incremental_number extends Extension
{
    /**
     *
     * Name of the extension field table
     * @var string
     *
     * @since version 1.2.0
     */

    const FIELD_TBL_NAME = 'tbl_fields_incremental_number';

    /**
     *
     * install the extension
     *
     * @since version 1.0.0
     */

    public function install()
    {
        return self::createFieldTable();
    }

    /**
     *
     * create the field table
     *
     * @since version 1.2.0
     */

    public static function createFieldTable()
    {
        $tbl = self::FIELD_TBL_NAME;

        return Symphony::Database()->query("
            CREATE TABLE IF NOT EXISTS `$tbl` (
                `id`            int(11) unsigned NOT NULL auto_increment,
                `field_id`      int(11) unsigned NOT NULL,
                `start_number`  int(11) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `field_id` (`field_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }

    /**
     *
     * uninstall
     *
     * @since version 1.0.0
     */

    public function uninstall()
    {
        return self::deleteFieldTable();
    }

    /**
     *
     * delete the field table
     *
     * @since version 1.2.0
     */

    public static function deleteFieldTable()
    {
        $tbl = self::FIELD_TBL_NAME;

        return Symphony::Database()->query("
            DROP TABLE IF EXISTS `$tbl`
        ");
    }

}
