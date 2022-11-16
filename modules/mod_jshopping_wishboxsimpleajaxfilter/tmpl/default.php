<?php
	// 
	defined('_JEXEC') or die;
	
	// 
	// 
	define('JALL', 'JALL');
	// 
	// 
	$filter_all[] = JHTML::_('select.option',  '', JText::_(JALL), 'id', 'name' );
?>
<div class="jshop-simpleajax-filter">
	<div class="attention-filter-container"></div>
    <form
		action="<?php echo $form_action; ?>"
		method="post"
		autocomplete="on"
		name="jshop_filters"
		data-redirect="<?php echo $data_redirect; ?>"
	>
		<input type="hidden" id="filter-limit" name="limit" value="" />
        <?php if ($show_prices){
            include (dirname(__FILE__).'/_prices.php');
        } else { ?>
            <div class="dnone">
                <?php include (dirname(__FILE__).'/_prices.php'); ?>
            </div>
        <?php } ?> 
        <div class="hide-for-mobile <?php if ($hide_onmobile) echo 'd-none d-xxl-block d-xl-block d-lg-block d-md-block hidden-phone hidden-tablet hidden-xs hidden-sm';?>">
            <?php if (is_array($filter_manufactures) && count($filter_manufactures)) {
                include (dirname(__FILE__).'/_manufacturer.php');
            }?>
            
            <?php if (is_array($filter_categorys) && count($filter_categorys)) {
                include (dirname(__FILE__).'/_categorys.php');
            }?>
            <?php if (is_array($filter_vendors) && count($filter_vendors)) {
                include (dirname(__FILE__).'/_vendors.php');
            }?>    

            <?php if ($show_labels){
                include (dirname(__FILE__).'/_labels.php');
            }?>

            <?php if ($show_characteristics) {
                include (dirname(__FILE__).'/_characteristics.php');
            }?>

            <?php if ($show_quantity) {
                include (dirname(__FILE__).'/_quantity.php');
            }?> 
            
            <?php if ($show_photo_filter) {
                include (dirname(__FILE__).'/_photo.php');
            }?> 
            
            <?php if ($show_delivery_time) {
                include (dirname(__FILE__).'/_delivery.php');
            } ?>
                    
            <?php if ($show_attributes) {
                include (dirname(__FILE__).'/_attributes.php');
            }?>

            <?php if ($show_reviews_count) {
                include (dirname(__FILE__).'/_reviews.php');
            }?>
        </div>
        <div class="clearfix clear-fix"></div>
        
        <div class="js-showallfilter <?php if ($hide_onmobile) echo 'd-block d-xxl-none d-xl-none d-lg-none d-md-none visible-sm visible-xs visible-phone visible-tablet'?>">
            <a href="#">
				<?php echo JText::_('SHOW_ALLFILTERS'); ?>
			</a>
        </div>
        <div class="js-hideallfilter <?php if ($hide_onmobile) echo 'd-block d-xxl-none d-xl-none d-lg-none d-md-none visible-sm visible-xs visible-phone visible-tablet dnone-force'?>">
            <a href="#">
				<?php echo JText::_('HIDE_ALLFILTERS'); ?>
			</a>
        </div>
        <div class="clear-simpleajax-filter">
            <?php if ($auto_filter != 1) { ?>
			<button type="submit" id="handler-start-filter" class="btn btn-success">
			   <?php echo JText::_('GO'); ?>
			</button>
            <?php } ?> 
            <a href="#"><?php echo JText::_('RESET_FILTER'); ?></a>
        </div>
        <div class="clearfix clear-fix"></div>
        <input type="hidden" id="filter-jsfajax" name="filter-jsfajax" value="1" />
    </form>
    <?php
		Joomla\CMS\Uri\Uri::getInstance();
		if (isset($_POST['filter-jsfajax']))
		{ 
			header('Location: '.Joomla\CMS\Uri\Uri::getInstance()); 
		} 
    $min_price = $minMaxProductPrice[0]*$jshopConfig->currency_value;
    if ($jshopConfig->decimal_count < 1) {
        $min_price = floor($min_price);
    }
    $max_price = $minMaxProductPrice[1]*$jshopConfig->currency_value;
    if ($jshopConfig->decimal_count < 1) {
        $max_price = ceil($max_price);
    }
    $pricemin=number_format($min_price, $jshopConfig->decimal_count, ".","");
    $pricemax=number_format($max_price, $jshopConfig->decimal_count, ".","");
    if ( $pricemin == $pricemax) $pricemin=$pricemax-0.001;
    ?>
    <span class="filter-load-popap js-filter-params dnone" data-filterparams='{
    "bsversion":"<?php echo $bs_version?>",
    "animation":"<?php echo $animate_filter?>",
    "ajaxon":"<?php echo $ajax_on?>",
    "showtext":"<?php echo JText::_('SHOW_ALLFILTERS')?>",
    "hidetext":"<?php echo JText::_('HIDE_ALLFILTERS')?>",
    "filterisactive":"<?php echo JText::_('FILTER_ISACTIVE')?>",
    "popuptext":"<?php echo JText::_('POPUP_TEXT')?>",
    "popupposttext":"<?php echo JText::_('POPUP_POSTTEXT')?>",
    "resetfilter":"<?php echo JText::_('RESET_FILTER')?>",
    "decimal":"<?php echo $jshopConfig->decimal_count?>",
    "pricefromset":"<?php echo $price_from_set?>",
    "pricetoset":"<?php echo $price_to_set?>",
    "pricemin":"<?php echo $pricemin ?>",
    "pricemax":"<?php echo $pricemax ?>",
    "totalquantity":"<?php echo $total_quantity; ?>",
    "autofilter":"<?php echo $auto_filter ?>"
    }'>
    </span>
</div>
<div class="clearfix clear-fix"></div>