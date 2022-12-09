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
	class JFormFieldCharacteristics extends ListField
	{
		/**
		 *
		 */
		public $type = 'characteristics';
		
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
			$db = \JFactory::getDBO();
			// 
			// 
			$ordering = 'ordering';
			// 
			// 
			$query = "SELECT F.id AS value,
							F.`name_".$jshopConfig->frontend_lang."` as text,
							F.allcats,
							F.type,
							F.cats,
							F.ordering,
							F.`group`,
							G.`name_".$jshopConfig->frontend_lang."` as groupname
						FROM `#__jshopping_products_extra_fields` as F
						left join `#__jshopping_products_extra_field_groups` as G on G.id=F.group
						order by ".$ordering;
			// 
			// 
			$db->setQuery($query);
			// 
			// 
			$rows = $db->loadObjectList();
			// 
			// 
			$list = parent::getOptions();
			// 
			// 
			foreach($rows as $v)
			{
				// 
				// 
				if ($v->allcats)
				{
					// 
					// 
					$v->cats = [];
				}
				else
				{
					// 
					// 
					$v->cats = unserialize($v->cats);
				}
				// 
				// 
				$list[] = $v;
			}
			// 
			// 
			unset($rows);
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
			$char_1 = [$tmp];
			// 
			// 
			$list = array_merge($char_1, $list);
			// 
			// 
			return $list;
		}
	}