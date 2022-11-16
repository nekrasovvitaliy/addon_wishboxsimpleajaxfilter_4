<?php defined('_JEXEC') or die('Restricted access');?>
<input type="hidden" name="categorys[]" value="0" />
<div class="category-filter filter-block-wrap">
    <div class="filter-head"> 
        <?php print JText::_('CATEGORY').":"?>
    </div>
    <?php if($show_categorys=='1') { ?>
        <div class="filter-item">
        <?php foreach ($filter_categorys as $v) {
            if ($hide_subcategorys && $v->level>0) continue;
            if ($v->level=="0") $prefix="";
            if ($v->level=="1") $prefix="-";
            if ($v->level=="2") $prefix="--";
            if ($v->level=="3") $prefix="---";
            if ($v->level=="4") $prefix="----";
            ?>
            <div class="filter-inem-value">
                <input type="checkbox" name="categorys[]" id="filter-categorys-<?php print $v->id;?>" value="<?php print $v->id;?>" <?php if (in_array($v->id, $categorys)) print "checked";?> > <label for="filter-categorys-<?php print $v->id;?>"><?php print $prefix." ".$v->name;?></label>
            </div>
        <?php } ?>
        </div>
   <?php } elseif ($show_categorys=='2') { ?>
        <div class="filter-item">
            <?php 
            $filter = array_merge($filter_all, $filter_categorys);
            echo JHTML::_('select.genericlist', $filter, 'categorys[]', 'size = "1" ','id', 'name', $categorys); ?>
        </div>
    <?php }?>
</div>
<div class="after-filter-item"></div>