<?php
	// 
	use \Joomla\CMS\Form\Field\ListField;
	use \Joomla\CMS\HTML\HTMLHelper;
	use \Joomla\CMS\Language\Text;
	
	// 
	// 
	if (!file_exists(JPATH_SITE.'/components/com_jshopping/bootstrap.php'))
	{
		// 
		// 
		\JSError::raiseError(500,"Please install component \"joomshopping\"");
    } 
	// 
	// 
    require_once (JPATH_SITE.'/components/com_jshopping/bootstrap.php');
	
	
	/**
	 *
	 */
	class JFormFieldAttributes extends ListField
	{
		/**
		 *
		 */
		public $type = 'attributes';
		
		
		/**
		 *
		 */
		protected function getOptions()
		{
			// 
			// 
			$jshopConfig = \JSFactory::getConfig();
			// 
			// 
			$db = JFactory::getDBO();
			// 
			// 
			$query = $db->getQuery(true);
			// 
			// 
			$query->select('attr_id AS value');
			// 
			// 
			$query->select('`name_'.$jshopConfig->frontend_lang .'` AS text');
			// 
			// 
			$query->from('#__jshopping_attr');
			// 
			// 
			$query->order('attr_ordering');
			// 
			// 
			$db->setQuery($query);
			// 
			// 
			$listAttribut = $db->loadObjectList();
			// 
			// 
			$tmp = new stdClass();
			// 
			// 
			$tmp->value = '0';
			// 
			// 
			$tmp->text = Text::_('JALL');
			// 
			// 
			$attr_1 = array($tmp);
			// 
			// 
			$list = array_merge($attr_1, $listAttribut);   
			// 
			// 
			return $list;
		}
	}