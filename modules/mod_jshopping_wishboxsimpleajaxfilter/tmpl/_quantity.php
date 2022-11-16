<?php defined('_JEXEC') or die('Restricted access');?>
<div class="quantity-filter filter-block-wrap">
    <div class="filter-head">
        <?php print JText::_('AVAILABILITY').":"?>
    </div>
    <div class="filter-item">
        <input type="radio" name="quantity_filter" value="0" id="filter-availability-0" <?php if ($quantity_filter == '0' || !($quantity_filter)) print "checked";?> > <label for="filter-availability-0"><?php print JText::_('All')?></label>
    </div> 
    <div class="filter-item">
        <input type="radio" name="quantity_filter" value="1" id="filter-availability-1" <?php if ($quantity_filter == '1') print "checked";?> > <label for="filter-availability-1"><?php print JText::_('IN_STOCK')?></label>
    </div> 
    <div class="filter-item">
        <input type="radio" name="quantity_filter" value="2" id="filter-availability-2" <?php if ($quantity_filter == '2') print "checked";?> > <label for="filter-availability-2"><?php print JText::_('UNAVAILABLE')?></label>
    </div> 
</div>
<div class="after-filter-item"></div>