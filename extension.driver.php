<?php

	Class extension_incremental_number extends Extension{

		public function about(){
			return array('name' => 'Field: Incremental Number',
						 'version' => '1.0',
						 'release-date' => '2011-07-18',
						 'author' => array('name' => 'John Porter',
										   'email' => 'contact@designermonkey.co.uk')
				 		);
		}

		public function uninstall(){
			Symphony::Database()->query("DROP TABLE `tbl_fields_incremental_number`");
		}


		public function install(){

			return Symphony::Database()->query("CREATE TABLE `tbl_fields_incremental_number` (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `field_id` int(11) unsigned NOT NULL,
			  `start_number` int(11) unsigned NOT NULL,
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `field_id` (`field_id`)
			) TYPE=MyISAM");

		}

	}

?>