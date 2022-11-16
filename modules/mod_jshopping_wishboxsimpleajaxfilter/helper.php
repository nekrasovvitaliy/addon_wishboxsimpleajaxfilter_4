<?php
	// 
	defined('_JEXEC') or die;
	
	// 
	use \Joomla\CMS\Factory;
	
	
	/**
	 *
	 */
	class modJshoppingWishBoxSimpleAjaxFilterHelper
	{
		/**
		 *
		 */
		static function getDisplayCharacteristics($category_id, $manufacturer_id, $vendor_id, $show_characteristics_id)
		{
			// 
			// 
			$db = Factory::getDbo();
			// 
			// 
			$all_characteristic = \JSFactory::getAllProductExtraField();
			// 
			// 
			$showCharacteristics = array();
			// 
			// 
			$where = '';
			// 
			// 
			$join = '';
			// 
			// 
			if ($category_id)
			{
				// 
				// 
				$join = " LEFT JOIN `#__jshopping_products_to_categories` as pc ON (pc.product_id=p.product_id) ";
				// 
				// 
				$where .= ' AND pc.category_id = '.$category_id;
			}
			// 
			// 
			$join .= " LEFT JOIN #__jshopping_products_to_extra_fields AS ptef ON ptef.product_id = p.product_id ";
			// 
			// 
			if ($manufacturer_id)
			{
				// 
				// 
				$where .= " AND p.product_manufacturer_id = '".$manufacturer_id."' ";
			}
			// 
			// 
			if ($vendor_id)
			{
				// 
				// 
				$query = "SELECT id FROM `#__jshopping_vendors` WHERE `main`=1";
				// 
				// 
				$db->setQuery($query);
				// 
				// 
				$id_main = intval($db->loadResult());
				// 
				// 
				if ($vendor_id == $id_main)
				{
					// 
					// 
					$where.=" AND p.vendor_id = '0' ";
				}
				else
				{
					// 
					// 
					$where.=" AND p.vendor_id = '".$vendor_id."' ";
				}
			}
			// 
			// 
			$select = '';
			// 
			// 
			foreach ($all_characteristic as $extraField)
			{
				// 
				// 
				if ($show_characteristics_id && in_array($extraField->id, $show_characteristics_id))
				{
					// 
					// 
					$select .= 'ptef.extra_field_'.$extraField->id.' AS `'.$extraField->id.'`, ';
				}
			}
			// 
			// 
			if ($select != '')
			{
				// 
				// 
				$select = substr($select, 0 , strlen($select) - 2);
			}
			// 
			// 
			if ($select)
			{
				// 
				// 
				$query = "SELECT distinct ".$select." FROM `#__jshopping_products` AS p "
				.$join.
				"WHERE p.product_publish = '1'
				".$where;
				// 
				// 
				$db->setQuery($query);
				// 
				// 
				$rows = $db->loadAssocList();
				// 
				// 
				foreach ($rows as $row)
				{
					// 
					// 
					foreach ($row as $k => $v)
					{
						// 
						// 
						if (($all_characteristic[$k]->type == 1 && $v != '') || $v)
						{
							// 
							// 
							if ($all_characteristic[$k]->type == 1)
							{
								// 
								// 
								$showCharacteristics[$k][] = $v;
							}
							else
							{
								// 
								// 
								$v_arr = explode(',', $v);
								// 
								// 
								foreach ($v_arr as $val)
								{
									// 
									// 
									$showCharacteristics[$k][] = $val;
								}
							}
						}
					}
				}
			}
			// 
			// 
			return $showCharacteristics;
		}
		
		
		/**
		 *
		 */
		static function getInProductsAttribut($category_id, $manufacturer_id, $vendor_id)
		{
			// 
			// 
			$db = Factory::getDbo();
			// 
			// 
			$jshopConfig = \JSFactory::getConfig();
			// 
			// 
			$where = '';
			// 
			// 
			$join = '';
			// 
			// 
			if ($category_id)
			{
				// 
				// 
				$join = " LEFT JOIN `#__jshopping_products_to_categories` as pc ON (pc.product_id=p.product_id) ";
				// 
				// 
				$where .= " AND pc.category_id = '".$category_id."' ";
			}
			// 
			// 
			if ($manufacturer_id)
			{
				// 
				// 
				$where.=" AND p.product_manufacturer_id = '".$manufacturer_id."' ";
			}
			// 
			// 
			if ($vendor_id)
			{
				$query = "SELECT id FROM `#__jshopping_vendors` WHERE `main`=1";
				$db->setQuery($query);
				$id_main = intval($db->loadResult());
				if ($vendor_id==$id_main) 
					$where.=" AND vendor_id = '0' "; 
				else 
					$where.=" AND vendor_id = '".$vendor_id."' ";
			}

			$query = "SELECT distinct a.attr_value_id FROM `#__jshopping_products_attr2` AS a
			LEFT JOIN `#__jshopping_products` AS p ON (a.product_id=p.product_id) ".$join."
			WHERE p.product_publish='1' ".$where." GROUP BY a.attr_value_id";
			$db->setQuery($query);
			$arr_independent = $db->loadColumn();
			
			$query = "SELECT `attr_id` FROM `#__jshopping_attr` WHERE `independent`='0'";  
			$db->setQuery($query); 
			$alldependattr = $db->loadColumn();

			$arr_dependent = array();
			if ($jshopConfig->hide_product_not_avaible_stock){
				$where.=" and a.count>0 ";
			}
			if ($alldependattr) 
				foreach($alldependattr as $attr){
				$query = "SELECT distinct a.attr_".$attr." FROM `#__jshopping_products_attr` a 
				LEFT JOIN `#__jshopping_products` AS p ON (a.product_id=p.product_id)
				".$join." 
				WHERE p.product_publish='1' ".$where;
				$db->setQuery($query);
				$tmplist = $db->loadColumn();
				$arr_dependent = array_merge($arr_dependent, $tmplist);
			}
			$arr_dependent = array_unique($arr_dependent);
			// 
			// 
			return array_merge($arr_independent, $arr_dependent);
		} 
		
		
		/**
		 *
		 */
		static function getInProductsLabels($category_id,$manufacturer_id,$vendor_id)
		{
			$db = JFactory::getDbo();
			$where=""; $join="";
			if ($category_id) {
				$join.=" LEFT JOIN `#__jshopping_products_to_categories` as pc ON (pc.product_id=p.product_id) ";
				$where.=" AND pc.category_id = '".$category_id."' "; 
			}
			if ($manufacturer_id) {
				$where.=" AND p.product_manufacturer_id = '".$manufacturer_id."' ";
			}
			if ($vendor_id) {
				$query = "SELECT id FROM `#__jshopping_vendors` WHERE `main`=1";
				$db->setQuery($query);
				$id_main = intval($db->loadResult());
				if ($vendor_id==$id_main) { 
					$where.=" AND p.vendor_id = '0' ";
				} else {
					$where.=" AND p.vendor_id = '".$vendor_id."' ";
				}
			}
			$query = " SELECT distinct label_id FROM `#__jshopping_products` AS p ".$join." WHERE p.product_publish='1' AND label_id!='0' ".$where;
			$db->setQuery($query);
			$res = $db->loadObjectList();
			$arr=array();
			if ($res) {
				foreach ($res as $_res) {
					$arr = array_merge($arr, array($_res->label_id));
				}
			}
			$arr=array_unique($arr);
			return  $arr;
		}
		
		
		/**
		 *
		 */
		static function getInProductsDelivery_time($category_id,$manufacturer_id,$vendor_id)
		{
			$db = JFactory::getDbo();
			$where=""; $join="";
			if ($category_id) {
				$join.=" LEFT JOIN `#__jshopping_products_to_categories` as pc ON (pc.product_id=p.product_id) ";
				$where.=" AND pc.category_id = '".$category_id."' "; 
			}
			if ($manufacturer_id) {
				$where.=" AND p.product_manufacturer_id = '".$manufacturer_id."' ";
			}
			if ($vendor_id) {
				$query = "SELECT id FROM `#__jshopping_vendors` WHERE `main`=1";
				$db->setQuery($query);
				$id_main = intval($db->loadResult());
				if ($vendor_id==$id_main) { 
					$where.=" AND p.vendor_id = '0' ";
				} else {
					$where.=" AND p.vendor_id = '".$vendor_id."' ";
				}
			} 
			$query = "SELECT distinct delivery_times_id FROM `#__jshopping_products` AS p ".$join." WHERE p.product_publish='1' AND delivery_times_id!='0' ".$where;
			$db->setQuery($query);
			$res = $db->loadObjectList(); 
			$arr=array();
			if ($res) foreach ($res as $_res) {
				$arr = array_merge($arr,array($_res->delivery_times_id));
			}
			$arr = array_unique($arr);
			return  $arr;
		}
		
		
		/**
		 *
		 */
		static function getVendorsForCategory($category_id)
		{
			$db = JFactory::getDbo();  
			$jshopConfig = JSFactory::getConfig();
			$user = JFactory::getUser();
			$adv_query = "";
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$adv_query .=' AND prod.access IN ('.$groups.')';
			if ($jshopConfig->hide_product_not_avaible_stock){
				$adv_query .= " AND prod.product_quantity > 0";
			}
			$query = "SELECT distinct man.id AS id, man.`shop_name` FROM `#__jshopping_products` AS prod
					  LEFT JOIN `#__jshopping_products_to_categories` AS categ USING (product_id)
					  LEFT JOIN `#__jshopping_vendors` AS man ON (prod.vendor_id=man.id OR (prod.vendor_id=0 AND man.main=1) )
					  WHERE categ.category_id = '".$category_id."' AND prod.product_publish = '1' ".$adv_query." order by shop_name";
			$db->setQuery($query);
			$list = $db->loadObjectList();
			return $list;
		}
		
		
		/**
		 *
		 */
		static function getVendorsForManufacturer($manufacturer_id)
		{
			$db = JFactory::getDbo();
			$jshopConfig = JSFactory::getConfig();
			$user = JFactory::getUser();
			$adv_query = "";
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$adv_query .=' AND prod.access IN ('.$groups.')';
			if ($jshopConfig->hide_product_not_avaible_stock){
				$adv_query .= " AND prod.product_quantity > 0";
			}
			$query = "SELECT distinct man.id AS id, man.`shop_name` FROM `#__jshopping_products` AS prod
					  LEFT JOIN `#__jshopping_vendors` AS man ON (prod.vendor_id=man.id OR (prod.vendor_id=0 AND man.main=1) )
					  WHERE prod.product_manufacturer_id = '".$manufacturer_id."' AND prod.product_publish = '1' ".$adv_query." order by shop_name";
			$db->setQuery($query);
			$list = $db->loadObjectList();
			return $list;     
		}  
		
		
		/**
		 *
		 */
		static function getManufacturersForVendors($vendor_id)
		{
			$db = JFactory::getDbo();
			$jshopConfig = JSFactory::getConfig();
			$user = JFactory::getUser();
			$lang = JSFactory::getLang();
			$adv_query = "";
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$adv_query .=' AND prod.access IN ('.$groups.')';
			if ($jshopConfig->hide_product_not_avaible_stock){
				$adv_query .= " AND prod.product_quantity > 0";
			}
			$query = "SELECT distinct man.manufacturer_id AS id, man.`".$lang->get('name')."` AS name FROM `#__jshopping_products` AS prod
					  LEFT JOIN `#__jshopping_manufacturers` AS man ON prod.product_manufacturer_id=man.manufacturer_id 
					  LEFT JOIN `#__jshopping_vendors` AS v ON (prod.vendor_id=v.id OR (prod.vendor_id=0 AND v.main=1) ) 
					  WHERE v.id = '".$vendor_id."' AND prod.product_publish = '1' AND prod.product_manufacturer_id!=0 ".$adv_query." order by name";
			$db->setQuery($query);
			$list = $db->loadObjectList();
			return $list;
		} 
		
		
		/**
		 *
		 */
		static function getCategorysForVendors($vendor_id)
		{
			$db = JFactory::getDbo();
			$jshopConfig = JSFactory::getConfig();
			$user = JFactory::getUser();
			$lang = JSFactory::getLang();
			$adv_query = "";
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$adv_query .=' AND prod.access IN ('.$groups.') AND cat.access IN ('.$groups.')';
			if ($jshopConfig->hide_product_not_avaible_stock){
				$adv_query .= " AND prod.product_quantity > 0";
			}
			$query = "SELECT distinct cat.category_id AS id, cat.`".$lang->get('name')."` AS name FROM `#__jshopping_products` AS prod
					  LEFT JOIN `#__jshopping_products_to_categories` AS categ USING (product_id)
					  LEFT JOIN `#__jshopping_categories` AS cat ON cat.category_id=categ.category_id
					  LEFT JOIN `#__jshopping_vendors` AS v ON (prod.vendor_id=v.id OR (prod.vendor_id=0 AND v.main=1) ) 
					  WHERE prod.product_publish = '1' AND v.id = '".$vendor_id."' AND cat.category_publish='1' ".$adv_query." order by name";
			$db->setQuery($query);
			$list = $db->loadObjectList();
			return $list;
		}
		
		
		/**
		 *
		 */
		static function getMinMaxPriceProd($category_id)
		{
			$app = JFactory::getApplication();
			$jshopConfig = JSFactory::getConfig();
			$user = JFactory::getUser();
			$db = JFactory::getDBO();
			$controller = $app->input->getCmd('controller');
			if ($controller=='category' && $category_id) {
				$controller_type = 'category';
			}
			if ($jshopConfig->displayprice == 2 && !$user->id) {
				return;
			}
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$where="";$jointax="";$join="";$where_attrib="";$join_attrib=""; $where_attrib2="";$join_attrib2=""; $where_disc="";$join_disc="";

			if ($jshopConfig->display_price_admin == $jshopConfig->display_price_front) {
				$jointax= '';
				$tax = '';
			} else {
				$jointax .= 'LEFT JOIN `#__jshopping_taxes` AS tax ON prod.product_tax_id = tax.tax_id';
				if ($jshopConfig->display_price_admin) {
					$tax = ' * ';
				} else {
					$tax = ' / ';
				}
				$tax .= '(1 + tax.tax_value / 100)';
			}

			$select = '
				MIN('.( "if (prod.different_prices, prod.min_price, prod.product_price)").' / cr.currency_value'.$tax.') AS `min`,
				MAX('.(  "if (prod.different_prices, prod.product_price, prod.product_price)").' / cr.currency_value'.$tax.') AS `max`
			';
			$from = '
				FROM `#__jshopping_products` AS prod
			';
			$join .= '
				LEFT JOIN `#__jshopping_currencies` AS cr ON ( prod.currency_id = cr.currency_id )
			';
			if ($category_id) {
				$join .= '
					LEFT JOIN `#__jshopping_products_to_categories` AS cat ON (prod.product_id = cat.product_id)
				';
			}
			$where .= '
				WHERE prod.product_publish = 1 AND prod.access IN ('.$groups.')
			';
			if ($category_id) {
				$where .= '
					AND cat.category_id = '.$category_id
				;
			}
			if ($jshopConfig->hide_product_not_avaible_stock) {
				$where .= '
					AND prod.product_quantity > 0
				';
			}
			$query = '
				SELECT
				'.$select.'
				'.$from.'
				'.$jointax.'
				'.$join.'
				'.$where.'
			';
			$db->setQuery( $query );
			$prod_min_max = $db->loadObject();

			//MIN AND MAX FROM ATTRIB DEPEND
			$select_attrib = '
				MIN(attr.price / cr.currency_value'.$tax.') as `min`,
				MAX(attr.price / cr.currency_value'.$tax.') as `max`
			';
			$from_attrib = '
				FROM `#__jshopping_products_attr`AS attr
			';
			$join_attrib .= '
				LEFT JOIN `#__jshopping_products` AS prod ON (attr.product_id = prod.product_id)
			';
			$join_attrib .='
				LEFT JOIN `#__jshopping_currencies` AS cr ON (prod.currency_id = cr.currency_id)
			';
			if ($category_id) {
				$join_attrib .= '
					LEFT JOIN `#__jshopping_products_to_categories` AS cat ON (prod.product_id = cat.product_id)
				';
			}
			$where_attrib .= '
				WHERE prod.product_publish = 1 AND prod.access IN ('.$groups.')
			';
			if ($category_id) {
				$where_attrib .= '
					AND cat.category_id = '.$category_id
				;
			}
			if ($jshopConfig->hide_product_not_avaible_stock) {
				$where_attrib .= '
					AND attr.count > 0
				';
			}
			$query = '
				SELECT
				'.$select_attrib.'
				'.$from_attrib.'
				'.$join_attrib.'
				'.$jointax.'
				'.$where_attrib.'
			';
			$db->setQuery( $query );
			$attr_min_max = $db->loadObject();

			//MIN AND MAX FROM ATTRIB INDEPEND
			$select_attrib2 = '
				MIN('.( "(CASE attr2.price_mod
					WHEN '+' THEN if (attr.price, attr.price, prod.product_price) + attr2.addprice
					WHEN '-' THEN if (attr.price, attr.price, prod.product_price) - attr2.addprice
					WHEN '*' THEN if (attr.price, attr.price, prod.product_price) * attr2.addprice
					WHEN '/' THEN if (attr.price, attr.price, prod.product_price) / attr2.addprice
					WHEN '%' THEN if (attr.price, attr.price, prod.product_price) * attr2.addprice / 100
					WHEN '=' THEN attr2.addprice / cr.currency_value".$tax."
					else prod.product_price END)").' / cr.currency_value) AS `min`,
				MAX('.( "(CASE attr2.price_mod
					WHEN '+' THEN if (attr.price, attr.price, prod.product_price) + attr2.addprice
					WHEN '-' THEN if (attr.price, attr.price, prod.product_price) - attr2.addprice
					WHEN '*' THEN if (attr.price, attr.price, prod.product_price) * attr2.addprice
					WHEN '/' THEN if (attr.price, attr.price, prod.product_price) / attr2.addprice
					WHEN '=' THEN attr2.addprice / cr.currency_value".$tax."
					WHEN '%' THEN if (attr.price, attr.price, prod.product_price) * attr2.addprice / 100
					else prod.product_price END)").' / cr.currency_value) AS `max`
			';
			$from_attrib2 = '
				FROM `#__jshopping_products_attr2` AS attr2
			';
			$join_attrib2 .= '
				LEFT JOIN `#__jshopping_products` AS prod ON (attr2.product_id = prod.product_id)
			';
			$join_attrib2 .='
				LEFT JOIN `#__jshopping_products_attr` AS attr ON (attr2.product_id = attr.product_id)
			';
			$join_attrib2 .='
				LEFT JOIN `#__jshopping_currencies` AS cr ON (prod.currency_id = cr.currency_id)
			';
			if ($category_id) {
				$join_attrib2 .= '
					LEFT JOIN `#__jshopping_products_to_categories` AS cat ON (prod.product_id = cat.product_id)
				';
			}
			$where_attrib2 .= '
				WHERE prod.product_publish = 1 AND prod.access IN ('.$groups.')
			';
			if ($category_id) {
				$where_attrib2 .= '
					AND cat.category_id = '.$category_id
				;
			}
			if ($jshopConfig->hide_product_not_avaible_stock) {
				$where_attrib2 .= '
					AND prod.product_quantity > 0
				';
			}
			$query = '
				SELECT
				'.$select_attrib2.'
				'.$from_attrib2.'
				'.$join_attrib2.'
				'.$jointax.'
				'.$where_attrib2.'
			';
			$db->setQuery( $query );
			$attr2_min_max = $db->loadObject();

			//MIN AND MAX FROM PRODUCTS DISCOUNT
			$select_disc = '
				prod.product_price * (100-MIN(prod_price.discount)) / 100 / cr.currency_value as `max`
			';
			$from_disc = '
				FROM `#__jshopping_products_prices` AS prod_price
			';
			$join_disc .= '
				LEFT JOIN `#__jshopping_products`AS prod ON (prod_price.product_id = prod.product_id)
			';
			$join_disc .= '
				LEFT JOIN `#__jshopping_currencies` AS cr ON (prod.currency_id = cr.currency_id)
			';
			if ($category_id) {
				$join_disc .= '
					LEFT JOIN `#__jshopping_products_to_categories` AS cat ON (prod.product_id = cat.product_id)
				';
			}
			$where_disc .= '
				WHERE prod.product_publish = 1 AND prod.access IN ('.$groups.') AND prod.product_is_add_price = 1
			';
			if ($category_id) {
				$where_disc .= '
					AND cat.category_id = '.$category_id
				;
			}
			if ($jshopConfig->hide_product_not_avaible_stock) {
				$where_disc .= '
					AND prod.product_quantity > 0
				';
			}
			$query = '
				SELECT
				'.$select_disc.'
				'.$from_disc.'
				'.$join_disc.'
				'.$where_disc.'
			';
			$db->setQuery( $query );
			$disc_min_max = $db->loadObject();

			if ($prod_min_max->min > 0) $min = $prod_min_max->min; else $min = null;
			if ($attr_min_max->min > 0 && $min > $attr_min_max->min) $min = $attr_min_max->min; else $min = $min;
			if ($attr2_min_max->min > 0 && $min > $attr2_min_max->min) $min = $attr2_min_max->min; else $min = $min;
			if ($disc_min_max->max > 0 && $min > $disc_min_max->max) $min = $disc_min_max->max; else $min = $min;
			
			if ($prod_min_max->max > 0) $max = $prod_min_max->max; else $max = null;
			if ($attr_min_max->max > 0 && $max < $attr_min_max->max) $max = $attr_min_max->max; else $max = $max;
			if ($attr2_min_max->max > 0 && $max < $attr2_min_max->max) $max = $attr2_min_max->max; else $max = $max;
			if ($disc_min_max->max > 0 && $max < $disc_min_max->max) $max = $disc_min_max->max; else $max = $max;

			return Array($min, $max);
		}
	}