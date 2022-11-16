<?php defined('_JEXEC') or die('Restricted access');?>
<?php if (is_array($listAttribut) && count($listAttribut)) { ?>
    <div class="attr-filter filter-block-wrap">
        <input type="hidden" name="attr_val[]" value="0" /> 
        <?php $html_char_name='<div class="filter-head">'//.JText::_('PRODUCT_ATTRIBUTES').":".'</div> '; $head_only_one='1';?>  

    <?php foreach ($listAttribut as $attr){
            $html_head = '<div class="head-item" >'.$attr->name.'</div>';
            $html_body=""; 
            if($show_attributes=='1') {
                foreach ($attr->values as $attr_values) {
                    $disabled="";
                    if (!in_array($attr_values->value_id,$attributvaluesInProducts) && $display_unavailable_value=='1') $disabled=' disabled="disabled" ';  
                    if (!in_array($attr_values->value_id,$attributvaluesInProducts) && $display_unavailable_value=='0') $hide=1; else $hide=0;
                    if ($hide==0){
                        if (is_array($attribut_active) && in_array($attr_values->value_id, $attribut_active)) $checked=' checked="checked" '; else $checked="";
                        //if ($show_attribute_image && (in_array($attr->attr_id, $show_attribute_image) || in_array('0', $show_attribute_image))) $attr_img = ' <img src="'.$jshopConfig->image_attributes_live_path.'/'.$attr_values->image.'" alt="" />';
                        //else 
                        $attr_img="";
                        $html_body.='<div class="filter-item"><input type="checkbox" name="attr_val[]" id="filterattr_'.$attr_values->value_id.'" value="'.$attr_values->value_id.'" '.$checked.$disabled.'  />'.$attr_img.' <label for="filterattr_'.$attr_values->value_id.'">'.$attr_values->name.'</label></div>';
                    } 
                }
            } elseif ($show_attributes=='2') { 
                $html_body .='<div class="filter-item"><select name="attr_val[]" data-jsfatrrid="'.$attr->attr_id.'">';
                $html_body .='<option value="">'.JText::_('JALL').'</option>';
                foreach ($attr->values as $attr_values) {  
                    $disabled="";
                    if(!in_array($attr_values->value_id,$attributvaluesInProducts) && $display_unavailable_value=='1') $disabled=' disabled="disabled" ';  
                    if(!in_array($attr_values->value_id,$attributvaluesInProducts) && $display_unavailable_value=='0') $hide=1; else $hide=0;
                    if( $hide==0){ 
                                if (is_array($attribut_active) && in_array($attr_values->value_id, $attribut_active)) $checked=' selected="selected" '; else $checked="";
                                $html_body.='<option value="'.$attr_values->value_id.'" '.$checked.$disabled.'>'.$attr_values->name.'</option>';
                    } 
                }                    
                $html_body .='</select></div>'; 
            }
			// 
			// 
			$head_only_one='1';
            if ($html_body!='' && $head_only_one=='1'){
                echo $html_char_name;  
                $head_only_one='0';
            }                
            if ($html_body!='') {
                echo $html_head.$html_body;
            }
    }?>
    </div>  
    <div class="after-filter-item"></div>
 <?php } ?>