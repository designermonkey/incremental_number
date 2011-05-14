<?php

	Class extension_order_number extends Extension{

		public function about(){
			return array('name' => 'Field: Order Number',
						 'version' => '1.4',
						 'release-date' => '2008-05-14',
						 'author' => array('name' => 'John Porter',
										   'email' => 'contact@designermonkey.co.uk')
				 		);
		}

		public function uninstall(){
			Symphony::Database()->query("DROP TABLE `tbl_fields_order_number`");
		}


		public function install(){

			return Symphony::Database()->query("CREATE TABLE `tbl_fields_order_number` (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `field_id` int(11) unsigned NOT NULL,
			  `start_number` int(11) unsigned NOT NULL,
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `field_id` (`field_id`)
			) TYPE=MyISAM");

		}

	}

?>