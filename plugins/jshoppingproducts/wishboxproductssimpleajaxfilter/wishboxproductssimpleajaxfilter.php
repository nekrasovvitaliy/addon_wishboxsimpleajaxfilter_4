<?php
	// 
	defined('_JEXEC') or die;
	
	// 
	// 
	use \Joomla\CMS\Factory;
	use \Joomla\CMS\Plugin\CMSPlugin;
	
	
	/**
	 *
	 */
	class plgJshoppingProductsWishBoxProductsSimpleajaxFilter extends CMSPlugin
	{
		/**
		 *
		 */
		var $query_cache = null;
		
		
		/**
		 *
		 */
		public function __construct(&$subject, $config)
		{
			// 
			// 
			$this->app = Factory::getApplication();
		}
		
		
		/**
		 *
		 */
		public function onBeforeQueryGetProductList($type, &$adv_result, &$adv_from, &$adv_query, &$order_query)
		{
			// 
			// 
			$ext_query = $this->_getQueryAjaxFilter($type, $adv_result, $adv_from, $adv_query);
			// 
			// 
			if (in_array($type, ['category', 'manufacturer', 'vendor', 'all_products', '']))
			{
				// 
				// 
				$adv_query .= $ext_query;
			}
		}
		
		
		/**
		 *
		 */
		public function onBeforeQueryCountProductList($type, &$adv_result, &$adv_from, &$adv_query)
		{
			// 
			// 
			$ext_query = $this->_getQueryAjaxFilter($type, $adv_result, $adv_from, $adv_query);
			// 
			// 
			if (in_array($type, ['category', 'manufacturer', 'vendor', 'all_products', '']))
			{
				// 
				// 
				$adv_query .= $ext_query;
			}
		}
		
		
		/**
		 *
		 */
		public function onBeforeFixLimitstartDisplayProductList(&$limitstart, &$total, $product_list_name)
		{
			// 
			// 
			$session = $this->app->getSession();
			// 
			// 
			$session->set('total_quantity', $total);
		}
		
		
		/**
		 *
		 */
		public function onBeforeDisplayProductListView(&$view)
		{
			// 
			// 
			$session = $this->app->getSession();
			// 
			// 
			if ($view->display_list_products =='0')
			{
				// 
				// 
				if ($view->filters['mod_price_from'] > 0)
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				if ($view->filters['mod_price_to'] > 0)
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				if (count($view->filters['categorys']) > 0)
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				if (count($view->filters['manufacturers']) > 0)
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				if (count($view->filters['vendors']) > 0)
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				if (count($view->filters['labels']) > 0)
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				if (count($view->filters['extra_fields']) > 0)
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				$contextajaxfilter = $this->_getContextAjaxFilter();
				// 
				// 
				$attribut_active_value = $this->app->getUserStateFromRequest($contextajaxfilter.'attr_val', 'attr_val', []); 
				// 
				// 
				$attribut_active_value = \JSHelper::filterAllowValue($attribut_active_value, 'int+');
				// 
				// 
				if (count($attribut_active_value))
				{
					// 
					// 
					$res = 1;
				}
				// 
				// 
				$session->set('show_category', $res);
			}
			else
			{
				// 
				// 
				$session->set('show_category', 1);
			}
		}
		
		
		/**
		 *
		 */
		public function afterGetBuildFilterListProduct(&$filters)
		{
			// 
			// 
			$session = $this->app->getSession();
			// 
			// 
			$jinput = $this->app->input;
			// 
			// 
			$option = $jinput->get('option');
			// 
			// 
			if ($option == 'com_jshopping')
			{
				// 
				// 
				$controller = $jinput->get('controller');
				// 
				// 
				$key = $jinput->get($controller.'_id');
				// 
				// 
				if ($controller != 'product' && $key != $session->get($controller.'_id'))
				{
					// 
					// 
					$filters = [];
					// 
					// 
					$jinput->set('categorys', []);
					// 
					// 
					$jinput->set('manufacturers', []);
					// 
					// 
					$jinput->set('vendors', []);
					// 
					// 
					$jinput->set('extra_fields', []);
					// 
					// 
					$jinput->set('labels', []);
					// 
					// 
					$jinput->set('quantity_filter', []);
					// 
					// 
					$jinput->set('photo_filter', []);
					// 
					// 
					$jinput->set('delivery_times', []);
					// 
					// 
					$jinput->set('fprice_from', 0);
					// 
					// 
					$jinput->set('fprice_to', 0);
				}
				// 
				// 
				$session->set($controller.'_id', $key);
			}
		}
		
		
		/**
		 *
		 */
		private function _getContextAjaxFilter()
		{
			// 
			// 
			$category_id = $this->app->input->getInt('category_id');
			// 
			// 
			$manufacturer_id = $this->app->input->getInt('manufacturer_id');
			// 
			// 
			$vendor_id = $this->app->input->getInt('vendor_id');
			// 
			// 
			$contextajaxfilter = '';
			// 
			// 
			if ($this->app->input->getVar('controller')=='category')
			{
				// 
				// 
				$contextajaxfilter = 'jshoping.list.front.product.cat.'.$category_id;
			}
			// 
			// 
			if ($this->app->input->getVar('controller')=='manufacturer')
			{
				// 
				// 
				$contextajaxfilter = 'jshoping.list.front.product.manf.'.$manufacturer_id;
			}
			// 
			// 
			if ($this->app->input->getVar('controller') == 'vendor')
			{
				// 
				// 
				$contextajaxfilter = 'jshoping.list.front.product.vendor.'.$vendor_id;
			}
			// 
			// 
			if ($this->app->input->getVar('controller')=='products')
			{
				// 
				// 
				$contextajaxfilter = 'jshoping.list.front.product.fulllist';
			}
			// 
			// 
			return $contextajaxfilter;
		}
		
		
		/**
		 *
		 */
		private function _getQueryAjaxFilter($type, $adv_result, $adv_from, $adv_query)
		{
			// 
			// 
			if ($this->query_cache !== null)
			{
				// 
				// 
				return $this->query_cache;
			}
			// 
			// 
			$query_cache = "";
			// 
			// 
			$db = Factory::getDBO();
			// 
			// 
			$jshopConfig = \JSFactory::getConfig();
			// 
			// 
			$contextajaxfilter = $this->_getContextAjaxFilter();
			// 
			// 
			$attribut_active_value = $this->app->getUserStateFromRequest( $contextajaxfilter.'attr_val', 'attr_val', []);
			// 
			// 
			$attribut_active_value = \JSHelper::filterAllowValue($attribut_active_value, "int+");
			// 
			// 
			$quantity_filter = $this->app->getUserStateFromRequest($contextajaxfilter.'quantity_filter', 'quantity_filter');
			// 
			// 
			$photo_filter = $this->app->getUserStateFromRequest($contextajaxfilter.'photo_filter', 'photo_filter');
			// 
			// 
			$reviews_count = $this->app->getUserStateFromRequest($contextajaxfilter.'reviews_count', 'reviews_count');
			// 
			// 
			$delivery_time_active = $this->app->getUserStateFromRequest($contextajaxfilter.'delivery_times', 'delivery_times', []);
			// 
			// 
			$delivery_time_active = \JSHelper::filterAllowValue($delivery_time_active, "int+");   
			// 
			// 
			if ($attribut_active_value)
			{
				// 
				// 
				$query = " SELECT `attr_id` FROM `#__jshopping_attr_values` WHERE `value_id` in (".implode(",",$attribut_active_value).") GROUP BY attr_id";
				// 
				// 
				$db->setQuery($query);
				// 
				// 
				$attr_id = $db->loadColumn();
				// 
				// 
				if ($attr_id)
				{
					// 
					// 
					$query = "SELECT a.attr_id, av.value_id, ap.product_id FROM `#__jshopping_attr` AS a 
								LEFT JOIN  `#__jshopping_attr_values` AS av ON (av.attr_id=a.attr_id)
								LEFT JOIN  `#__jshopping_products_attr2` AS ap ON (av.value_id=ap.attr_value_id) 
								WHERE av.value_id in (".implode(",",$attribut_active_value).") AND a.independent='1' ORDER BY a.attr_id";
					// 
					// 
					$db->setQuery($query);
					// 
					// 
					$attr_array_independent = $db->loadObjectList();
					// 
					// 
					if ($attr_array_independent)
					{
						// 
						// 
						foreach ($attr_array_independent AS $_attr_arr)
						{
							// 
							// 
							$attr_ind[$_attr_arr->attr_id] .= $_attr_arr->product_id.',';
						}
					}
					// 
					// 
					$adv_query_independent = "";
					// 
					// 
					if ($attr_ind) foreach ($attr_ind AS $key=>$value)
					{
						// 
						// 
						$array_res = array_unique(explode(",", substr($value, 0, strlen($value) - 1)));
						// 
						// 
						$adv_query_independent .= " AND prod.product_id in (".implode(",",$array_res).") "; 
					}
					// 
					// 
					$query_cache.= $adv_query_independent;
					// 
					// 
					$query = " SELECT `attr_id` FROM `#__jshopping_attr` WHERE `attr_id` in (".implode(",",$attr_id).") AND `independent`='0'";  
					// 
					// 
					$db->setQuery($query);
					// 
					// 
					$attr_id_depend = $db->loadColumn();
					// 
					// 
					$product_id_depend = []; 
					// 
					// 
					if (count($attr_id_depend) > 0)
					{
						// 
						// 
						$_attr_id_depend = implode(",",$attribut_active_value);
						// 
						// 
						$_where = "";
						// 
						// 
						foreach ($attr_id_depend as $key => $attr_key)
						{
							// 
							// 
							$_where .= "  `attr_".$attr_key."` in (".$_attr_id_depend.") ";
							// 
							// 
							if ($key < count($attr_id_depend) - 1)
							{
								// 
								// 
								$_where .= " AND ";
							}
						}
						// 
						// 
						if ($jshopConfig->hide_product_not_avaible_stock)
						{
							// 
							// 
							$_where .= " and `count`>0 ";
						}
						// 
						// 
						if ($_where != "")
						{
							// 
							// 
							$_where = " WHERE ".$_where;
						}
						// 
						// 
						$query = " SELECT `product_id` FROM `#__jshopping_products_attr` ".$_where." GROUP BY product_id";
						// 
						// 
						$db->setQuery($query); 
						// 
						// 
						$product_id_depend = $db->loadColumn();
						// 
						// 
						if (count($product_id_depend) > 0)
						{
							// 
							// 
							$query_cache .= " AND prod.product_id in (".implode(",", $product_id_depend).") ";
						}
						else
						{
							// 
							// 
							$query_cache .= " AND prod.product_id = '0' "; 
						}
					}
				}
			}
			// 
			// 
			if ($quantity_filter == '1')
			{
				// 
				// 
				$query_cache .= " AND (prod.product_quantity > '0' OR  prod.unlimited = '1') ";
			}
			// 
			// 
			if ($quantity_filter == '2')
			{
				// 
				// 
				$query_cache .= " AND (prod.product_quantity = '0' AND prod.unlimited = '0') ";
			}
			// 
			// 
			if (version_compare(JVERSION, '3.0.0', '>='))
			{
				// 
				// 
				$image_field = 'image';
			}
			else
			{
				// 
				// 
				$image_field = 'product_name_image';
			}
			// 
			// 
			if ($photo_filter == '1')
			{
				// 
				// 
				$query_cache .= " AND prod.".$image_field." != '' ";
			}
			if ($photo_filter == '2')
			{
				// 
				// 
				$query_cache .= " AND prod.".$image_field." = '' ";
			}
			// 
			// 
			if ($reviews_count == '1')
			{
				// 
				// 
				$query_cache .= " AND prod.reviews_count > 0 ";
			}
			// 
			// 
			if ($reviews_count == '2')
			{
				// 
				// 
				$query_cache .= " AND prod.reviews_count = 0 ";
			}
			// 
			// 
			if (count($delivery_time_active)>0)  $query_cache.=" AND prod.delivery_times_id in (".implode(",",$delivery_time_active).") ";
			// 
			// 
			$this->query_cache = $query_cache;
			// 
			// 
			return $this->query_cache;
		}
	}