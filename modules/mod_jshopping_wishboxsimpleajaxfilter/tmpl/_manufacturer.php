<?php
	// 
	defined('_JEXEC') or die;
	
	// 
	use \Joomla\CMS\HTML\HTMLHelper;
	use \Joomla\CMS\Language\Text;
?>
<input type="hidden" name="manufacturers[]" value="0" />
<div class="manufacturer-filter filter-block-wrap">
	<div class="filter-head">
		<?php echo Text::_('MANUFACTURER').":"; ?>
	</div>
    <?php if ($show_manufacturers == '1') { ?>
	<?php foreach($filter_manufactures as $v) { ?>
	<div class="filter-item">
		<input type="checkbox"
			name="manufacturers[]"
			id="filter-manufacturer-<?php echo $v->id; ?>"
			value="<?php echo $v->id; ?>"
			<?php if (in_array($v->id, $manufacturers)) echo 'checked'; ?>
		/>
		<label for="filter-manufacturer-<?php echo $v->id; ?>">
			<?php echo $v->name; ?>
		</label>
	</div>
	<?php } ?>
	<?php } elseif ($show_manufacturers == '2') { ?>
	<div class="filter-item">
		<?php $filter = array_merge($filter_all, $filter_manufactures);
			echo HTMLHelper::_('select.genericlist', $filter, 'manufacturers[]', 'size = "1"', 'id', 'name', $manufacturers); ?>
	</div>
	<?php } ?>
</div>
<div class="after-filter-item"></div>