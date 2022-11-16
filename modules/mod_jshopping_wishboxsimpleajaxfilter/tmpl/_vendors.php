<?php defined('_JEXEC') or die('Restricted access');?>
<div class="vendors-filter filter-block-wrap">
<input type="hidden" name="vendors[]" value="0" />
    <div class="filter-head"> 
        <?php print JText::_('VENDORS').":"?>
    </div>
    <?php if($show_vendors=='1') { ?>
        <?php foreach($filter_vendors as $v){ ?>
            <div class="filter-item">
                <input type="checkbox" name="vendors[]" id="filter-vendors-<?php print $v->id;?>" value="<?php print $v->id;?>" <?php if (in_array($v->id, $vendors)) print "checked";?> > <label for="filter-vendors-<?php print $v->id;?>"><?php print $v->shop_name;?></label>
            </div>
        <?php }
    } elseif ($show_vendors=='2') { ?>
        <div class="filter_item">
            <?php $filter_all_vendors[] = JHTML::_('select.option',  '', JText::_(JALL), 'id', 'shop_name' );
            $filter = array_merge($filter_all_vendors, $filter_vendors);
            echo JHTML::_('select.genericlist', $filter, 'vendors[]', 'size = "1" ','id', 'shop_name', $vendors); ?>
        </div>
    <?php }?>
</div>
<div class="after-filter-item"></div>