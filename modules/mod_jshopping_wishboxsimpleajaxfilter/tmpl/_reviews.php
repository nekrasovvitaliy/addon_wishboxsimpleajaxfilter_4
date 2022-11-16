<?php defined('_JEXEC') or die('Restricted access');?>
<div class="reviews-filter filter-block-wrap">
    <div class="filter-head">
        <?php print JText::_('REVIEWS_FILTER').":"?>
    </div>
    <div class="filter-item">
        <input type="radio" name="reviews_count" value="0" id="filter-review-0" <?php if ($reviews_count == '0' || !($quantity_filter)) print "checked";?> > <label for="filter-review-0"><?php print JText::_('All')?></label>
    </div> 
    <div class="filter-item">
        <input type="radio" name="reviews_count" value="1" id="filter-review-1" <?php if ($reviews_count == '1') print "checked";?> > <label for="filter-review-1"><?php print JText::_('WITH_REVIEWS')?></label>
    </div> 
    <div class="filter-item">
        <input type="radio" name="reviews_count" value="2" id="filter-review-2" <?php if ($reviews_count == '2') print "checked";?> > <label for="filter-review-2"><?php print JText::_('WITHOUT_REVIEWS')?></label>
    </div> 
</div>
<div class="after-filter-item"></div>