<?php defined('_JEXEC') or die('Restricted access') ?>
<?php 
    $min_price = $minMaxProductPrice[0]*$jshopConfig->currency_value;
    if ($jshopConfig->decimal_count < 1) {
        $min_price = floor($min_price);
    }
    $max_price = $minMaxProductPrice[1]*$jshopConfig->currency_value;
    if ($jshopConfig->decimal_count < 1) {
        $max_price = ceil($max_price);
    }
    if ($fprice_from>0) { 
        $price_from_set=$fprice_from;
    } else { 
        $price_from_set=number_format($min_price, $jshopConfig->decimal_count, ".","");
    }
    if ($fprice_to>0) { 
        $price_to_set=$fprice_to;
    } else { 
        $price_to_set=number_format($max_price, $jshopConfig->decimal_count, ".","");
    } 
    ?>
<div class="price-filter">
    <div class="filter-head">
        <?php print JText::_('PRICE'); print ' ('.$jshopConfig->currency_code.')';?>:
    </div>
    <div class="filter-item">
        <div id="slider-mod"></div>
        <div class="price-from-to">
            <span class="price-from">
                <?php print JText::_('FROM')?> <input type = "text" class = "inputbox" name = "fprice_from" id="mod_price_from" size="7" value="<?php print $price_from_set;?>" />
            </span>
            <span class="price-to">
                <?php print JText::_('TO')?> <input type = "text" class = "inputbox" name = "fprice_to"  id="mod_price_to" size="7" value="<?php print $price_to_set?>" />
            </span>
        </div>
    </div>
</div>
<div class="after-filter-item"></div>