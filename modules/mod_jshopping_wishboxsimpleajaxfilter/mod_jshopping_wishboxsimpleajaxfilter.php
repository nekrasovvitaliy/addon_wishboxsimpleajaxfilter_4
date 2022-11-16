<?php
	// 
	defined('_JEXEC') or die;
	
	// 
	// 
	if (!file_exists(JPATH_SITE.'/components/com_jshopping/bootstrap.php'))
	{
		// 
		// 
		\JSError::raiseError(500, "Please install component \"joomshopping\"");
	} 
	// 
	// 
	require_once (JPATH_SITE.'/components/com_jshopping/bootstrap.php');
	// 
	// 
	JLoader::register('modJshoppingWishBoxSimpleAjaxFilterHelper', __DIR__.'/helper.php');
	// 
	// 
	\JSFactory::loadCssFiles();
	// 
	// 
	\JSFactory::loadLanguageFile();
	// 
	// 
	$jshopConfig = \JSFactory::getConfig();
	// 
	// 
	$app = JFactory::getApplication();
	// 
	// 
	$bs_version = $params->get('bs_version', 1);
	// 
	// 
	$show_manufacturers = $params->get('show_manufacturers');
	// 
	// 
	$show_categorys = $params->get('show_categorys');
	// 
	// 
	$hide_subcategorys = $params->get('hide_subcategorys');
	// 
	// 
	$show_vendors = $params->get('show_vendors');
	// 
	// 
	$show_prices = $params->get('show_prices');
	// 
	// 
	$show_labels = $params->get('show_labels');
	// 
	// 
	$show_characteristics = $params->get('show_characteristics');
	// 
	// 
	$show_attributes = $params->get('show_attributes');
	// 
	// 
	$show_quantity = $params->get('show_quantity');
	// 
	//
	$show_photo_filter = $params->get('show_photo_filter');
	// 
	// 
	$show_delivery_time = $params->get('show_delivery_time');
	// 
	// 
	$show_attributes_id = $params->get('attr_id');
	// 
	// 
	$show_characteristics_id = $params->get('characteristics_id');
	// 
	// 
	$display_unavailable_value = $params->get('display_unavailable_value');
	// 
	// 
	$show_on_all_pages = $params->get('show_on_all_pages', 0);
	// 
	// 
	$show_reviews_count = $params->get('show_reviews', 0);
	// 
	// 
	$animate_filter = $params->get('animate_filter', 0);
	// 
	// 
	$ajax_on = $params->get('ajax_on', 1);
	// 
	// 
	$auto_filter = $params->get('auto_filter', 1);
	// 
	// 
	$hide_onmobile = $params->get('hide_onmobile', 1);
	// 
	// 
	$jinput = JFactory::getApplication()->input;
	// 
	// 
	$session = JFactory::getSession();
	// 
	// 
	$display_fileters = 0;
	// 
	// 
	$task = $jinput->get('task');
	// 
	// 
	$controller = $jinput->get('controller');
	// 
	// 
	$view = $jinput->get('view');
	// 
	// 
	$category_id = $jinput->get("category_id");
	// 
	// 
	$manufacturer_id = $jinput->get("manufacturer_id");
	// 
	// 
	$vendor_id = $jinput->get("vendor_id"); 
	// 
	// 
	if ($task == '')
	{
		// 
		// 
		$task = 'display';
	}
	// 
	// 
	$total_quantity = 0;
	// 
	// 
	if (
			($view == 'category' && $category_id)
			||
			($controller == 'category' && $category_id && $session->get('show_category'))
			||
			($controller == "manufacturer" && $manufacturer_id)
			||
			($controller == "vendor" && $vendor_id)
			||
			($controller == "products" && $task == "display")
		)
	{
		// 
		// 
		$display_fileters = 1;
		// 
		// 
		$form_action = $_SERVER["REQUEST_URI"];
		// 
		// 
		$total_quantity = $session->get('total_quantity', 0);
	}
	// 
	// 
	if ($show_on_all_pages)
	{
		// 
		// 
		$display_fileters = 1;
	}
	// 
	// 
	if (!$display_fileters)
	{
		// 
		// 
		return '';
	}
	// 
	// 
	$document = JFactory::getDocument();
	// 
	// 
	$document->addStyleSheet(JURI::base().'modules/mod_jshopping_wishboxsimpleajaxfilter/assets/css/style.min.css');
	// 
	// 
	$document->addScript(JURI::base().'modules/mod_jshopping_wishboxsimpleajaxfilter/assets/js/script.min.js');
	// 
	// 
	if (!isset($form_action))
	{
		// 
		// 
		$form_action = \JSHelper::SEFLink('index.php?option=com_jshopping&controller=products',1);
		// 
		// 
		$data_redirect = $form_action;
	}
	// 
	// 
	$contextajaxfilter = "";
	// 
	// 
	if ($controller == 'category')
	{
		// 
		// 
		$contextajaxfilter = 'jshoping.list.front.product.cat.'.$category_id;
	}
	// 
	// 
	if ($controller == "manufacturer")
	{
		// 
		// 
		$contextajaxfilter = "jshoping.list.front.product.manf.".$manufacturer_id;
	}
	// 
	// 
	if ($controller == "vendor")
	{
		// 
		// 
		$contextajaxfilter = "jshoping.list.front.product.vendor.".$vendor_id;  
	}
	// 
	// 
	if ($controller == 'products')
	{
		// 
		// 
		$contextajaxfilter = 'jshoping.list.front.product.fulllist';
	}
	// 
	// 
	if ($show_manufacturers && $controller != 'manufacturer')
	{
		// 
		// 
		$category = JSFactory::getTable('category');
		// 
		// 
		$category->load($category_id);
		// 
		// 
		$manufacturers = $app->getUserStateFromRequest($contextajaxfilter.'manufacturers', 'manufacturers', []);
		// 
		// 
		$manufacturers = \JSHelper::filterAllowValue($manufacturers, "int+");
		// 
		// 
		if ($category_id != '')
		{
			// 
			// 
			$filter_manufactures = $category->getManufacturers();
		}
		elseif ($vendor_id != '')
		{
			// 
			// 
			$filter_manufactures = modJshoppingWishBoxSimpleAjaxFilterHelper::getManufacturersForVendors($vendor_id);  
		}
		else
		{
			// 
			// 
			$_manufacturers = JSFactory::getTable('manufacturer');
			// 
			// 
			$ordering = $jshopConfig->manufacturer_sorting==1 ? 'ordering' : 'name';
			$filter_manufactures = $_manufacturers->getAllManufacturers(1, $ordering);
			foreach ($filter_manufactures as $key=>$value){
				$filter_manufactures[$key]->id = $filter_manufactures[$key]->manufacturer_id;
			}
		}
	}

	if ($show_categorys && $controller!='category'){
		$manufacturer = JSFactory::getTable('manufacturer');
		$manufacturer->load($manufacturer_id);
		$categorys = $app->getUserStateFromRequest( $contextajaxfilter.'categorys', 'categorys', array());
		$categorys = \JSHelper::filterAllowValue($categorys, "int+");
		if ($manufacturer_id!=''){
			$filter_categorys = $manufacturer->getCategorys();
		} elseif ($vendor_id!='') {
			$filter_categorys = modJshoppingWishBoxSimpleAjaxFilterHelper::getCategorysForVendors($vendor_id); 
		}
		else
		{
			// 
			// 
			$filter_categorys = \JSHelper::buildTreeCategory(1, 0, 1);
			// 
			// 
			foreach($filter_categorys as $key => $value)
			{
				// 
				// 
				$filter_categorys[$key]->id = $filter_categorys[$key]->category_id;
			}
		}
	}
	// 
	// 
	$filter_vendors = null;
	// 
	// 
	if ($show_vendors && $controller!="vendor")
	{
		$vendor = JSFactory::getTable('vendor');
		$vendors = $app->getUserStateFromRequest( $contextajaxfilter.'vendors', 'vendors', array());
		$vendors = \JSHelper::filterAllowValue($vendors, "int+");
		// 
		// 
		if ($category_id != '')
		{
			$filter_vendors = modJshoppingWishBoxSimpleAjaxFilterHelper::getVendorsForCategory($category_id, $reviews_count);
		}
		elseif($manufacturer_id != '')
		{
			$filter_vendors = modJshoppingWishBoxSimpleAjaxFilterHelper::getVendorsForManufacturer($manufacturer_id);
		}
		else
		{
			$filter_vendors = $vendor->getAllVendors(1, 0, $vendor->getCountAllVendors(1));
		}
	}

	$minMaxProductPrice=modJshoppingWishBoxSimpleAjaxFilterHelper::getMinMaxPriceProd($category_id);
	//$minMaxProductPrice=modJshoppingWishBoxSimpleAjaxFilterHelper::test($category_id);
	$price_from = \JSHelper::saveAsPrice($jinput->get('mod_price_from'));
	$price_to = \JSHelper::saveAsPrice($jinput->get('mod_price_to'));
	$fprice_from = $app->getUserStateFromRequest( $contextajaxfilter.'fprice_from', 'fprice_from');
	$fprice_from = \JSHelper::saveAsPrice($fprice_from);
	if (!$fprice_from) $fprice_from = $price_from;
	$fprice_to = $app->getUserStateFromRequest( $contextajaxfilter.'fprice_to', 'fprice_to');
	$fprice_to = \JSHelper::saveAsPrice($fprice_to);
	if (!$fprice_to) $fprice_to = $price_to;

	if ($show_characteristics){
		$characteristic_fields = JSFactory::getAllProductExtraField();
		if ($controller=="category" && $category_id) {
			foreach($characteristic_fields as $k=>$val){
				$_display = 0;
				if ($val->allcats){
					$_display = 1; 
				} else {
					if (in_array($category_id, $val->cats)) $_display = 1;
				}
			}
		}
		$all_characteristic = JSFactory::getallProductExtraField();
		$characteristic_fieldvalues = JSFactory::getAllProductExtraFieldValueDetail();
		$getDisplayCharacteristics=modJshoppingWishBoxSimpleAjaxFilterHelper::getDisplayCharacteristics($category_id, $manufacturer_id, $vendor_id, $show_characteristics_id);
		$context_ef = $contextajaxfilter.'extra_fields';
		$extra_fields_active = $app->getUserStateFromRequest( $context_ef, 'extra_fields', array());
		$extra_fields_active = \JSHelper::filterAllowValue($extra_fields_active, "array_int_k_v+");
	}

	if ($show_labels)
	{
		// 
		// 
		$productLabel = \JSFactory::getTable('productLabel');
		// 
		// 
		$listLabels = $productLabel->getListLabels();
		$labelInProducts = modJshoppingWishBoxSimpleAjaxFilterHelper::getInProductsLabels($category_id,$manufacturer_id,$vendor_id);
		$labels_active = $app->getUserStateFromRequest( $contextajaxfilter.'labels', 'labels', array());
		$labels_active = \JSHelper::filterAllowValue($labels_active, "int+");
	}
		
	if ($show_attributes)
	{
		// 
		// 
		$attribut = \JSFactory::getTable('attribut');
		$attributvalue = JSFactory::getTable('attributvalue');
		$listAttribut = $attribut->getAllAttributes();
		$attributvaluesInProducts = modJshoppingWishBoxSimpleAjaxFilterHelper::getInProductsAttribut($category_id, $manufacturer_id, $vendor_id);
		foreach($listAttribut as $key=>$value){
			if ($controller=="category") {
				$_display = 0;
				if ($value->allcats){
					$_display = 1; 
				}else{
					if (in_array($category_id, $value->cats)) $_display = 1;
				}
				if (!$_display){
					unset($listAttribut[$key]);
					continue;
				}
			}
			
			if ($show_attributes_id && (in_array($value->attr_id, $show_attributes_id) || in_array(0, $show_attributes_id))){
				$values_for_attribut = $attributvalue->getAllValues($value->attr_id);
				if (!count($values_for_attribut)){
					unset($listAttribut[$key]);
					continue;
				}
				$listAttribut[$key]->values = $values_for_attribut;
			}else{
				unset($listAttribut[$key]);
			}
		}
		$attribut_active = $app->getUserStateFromRequest( $contextajaxfilter.'attr_val', 'attr_val', array());
		$attribut_active = \JSHelper::filterAllowValue($attribut_active, "int+");
	}

	if ($show_quantity){
		$quantity_filter = $app->getUserStateFromRequest( $contextajaxfilter.'quantity_filter', 'quantity_filter');
	}  

	if ($show_photo_filter) {
		$photo_filter = $app->getUserStateFromRequest( $contextajaxfilter.'photo_filter', 'photo_filter');
	}

	if ($show_reviews_count) {
		$reviews_count = $app->getUserStateFromRequest( $contextajaxfilter.'reviews_count', 'reviews_count');
	}

	if ($show_delivery_time)
	{
		// 
		// 
		$deliveryTimes = JSFactory::getTable('deliveryTimes');
		// 
		// 
		$listDeliveryTimes = $deliveryTimes->getDeliveryTimes();
		$delivery_timeInProducts = modJshoppingWishBoxSimpleAjaxFilterHelper::getInProductsDelivery_time($category_id,$manufacturer_id,$vendor_id);   
		$delivery_time_active = $app->getUserStateFromRequest( $contextajaxfilter.'delivery_times', 'delivery_times', []);
		$delivery_time_active = \JSHelper::filterAllowValue($delivery_time_active, "int+");
	}   
	// 
	// 
	$layout = $params->get('layout', 'default');
	// 
	// 
	require(JModuleHelper::getLayoutPath('mod_jshopping_wishboxsimpleajaxfilter', $layout));