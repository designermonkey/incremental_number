<?php

	Class extension_incremental_number extends Extension
	{

		public function uninstall(){
			try{
				Symphony::Database()->query("DROP TABLE `tbl_fields_incremental_number`");
			}
			catch( Exception $e ){
			}

			return true;
		}


		public function install(){
			try{
				Symphony::Database()->query(
					"CREATE TABLE `tbl_fields_incremental_number` (
						`id` int(11) unsigned NOT NULL auto_increment,
						`field_id` int(11) unsigned NOT NULL,
						`start_number` int(11) unsigned NOT NULL,
						PRIMARY KEY  (`id`),
						UNIQUE KEY `field_id` (`field_id`)
					) TYPE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
				);
			} catch( Exception $e ){
			}

			return true;
		}
	}
