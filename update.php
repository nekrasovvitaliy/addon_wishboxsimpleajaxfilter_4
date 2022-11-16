<?php
	// 
	use Joomla\CMS\Factory;
	
	// 
	// 
	$app = Factory::getApplication();
	// 
	// 
	$db = Factory::getDbo();
	// 
	// 
	$addon_table = \JSFactory::getTable('addon', 'jshop');
	// 
	// 
	$addon_table->installJoomlaExtension(
											[
												'name' => 'mod_jshopping_wishboxsimpleajaxfilter',
												'type' => 'module',
												'element' => 'mod_jshopping_wishboxsimpleajaxfilter',
												'folder' => '',
												'client_id' => '0',
												'enabled' => 1
											],
											true
										);
	// 
	// 
	$addon_table->installJoomlaExtension(
											[
												'name' => 'plg_jshoppingproducts_wishboxproductssimpleajaxfilter',
												'type' => 'plugin',
												'element' => 'wishboxproductssimpleajaxfilter',
												'folder' => 'jshoppingproducts',
												'client_id' => '0',
												'enabled' => 1
											],
											true
										);