<?php defined('_JEXEC') or die('Restricted access');?>
<div class="photo-filter filter-block-wrap">
    <div class="filter-head">
        <?php print JText::_('Photo_filter').":"?>
    </div>
    <div class="filter-item">
        <input type="radio" name="photo_filter" value="0" id="filter-photo-0" <?php if ($photo_filter == '0' || !($quantity_filter)) print "checked";?> > <label for="filter-photo-0"><?php print JText::_('All')?></label>
    </div> 
    <div class="filter-item">
        <input type="radio" name="photo_filter" value="1" id="filter-photo-1" <?php if ($photo_filter == '1') print "checked";?> > <label for="filter-photo-1"><?php print JText::_('WITH_PHOTO')?></label>
    </div> 
    <div class="filter-item">
        <input type="radio" name="photo_filter" value="2" id="filter-photo-2" <?php if ($photo_filter == '2') print "checked";?> > <label for="filter-photo-2"><?php print JText::_('WITHOUT_PHOTO')?></label>
    </div> 
</div>
<div class="after-filter-item"></div>