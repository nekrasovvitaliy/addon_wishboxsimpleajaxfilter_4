<?php defined('_JEXEC') or die('Restricted access');?>
<?php if (is_array($listDeliveryTimes) && count($listDeliveryTimes)) { ?>
    <div class="labels-filter filter-block-wrap">
        <input type="hidden" name="delivery_times[]" value="0" /> 
        <?php 
        $html_head='<div class="filter-head">'.JText::_('Delivery_Time').":".'</div> ';
        $html_body ="";
        if ($show_delivery_time=='1') {
            foreach($listDeliveryTimes as $delivery_times){
                $disabled="" ; 
                if(!in_array($delivery_times->id,$delivery_timeInProducts) && $display_unavailable_value=='1') $disabled=' disabled="disabled" ';
                if(!in_array($delivery_times->id,$delivery_timeInProducts) && $display_unavailable_value=='0') $hide=1; else $hide=0; 
                if( $hide==0){
                    $checked="";
                    if (is_array($delivery_time_active) && in_array($delivery_times->id, $delivery_time_active)) $checked='checked="checked"' ;
                    $html_body .= '<div class="filter-item"><input type="checkbox" name="delivery_times[]" id="filter-delivery-'.$delivery_times->id.'" value="'.$delivery_times->id.'" '.$checked.$disabled.'  /> <label for="filter-delivery-'.$delivery_times->id.'">'.$delivery_times->name.'</label></div>';                       
                }
            }
        } elseif ($show_delivery_time=='2') {
            $html_body .='<div class="filter-item"><select name="delivery_times[]">'; 
            $html_body .='<option value="">'.JText::_('JALL').'</option>';  
            foreach($listDeliveryTimes as $delivery_times){
                $disabled="" ; 
                if(!in_array($delivery_times->id,$delivery_timeInProducts) && $display_unavailable_value=='1') $disabled=' disabled="disabled" ';   
                if(!in_array($delivery_times->id,$delivery_timeInProducts) && $display_unavailable_value=='0') $hide=1; else $hide=0; 
                if( $hide==0){
                    $checked="";
                    if (is_array($delivery_time_active) && in_array($delivery_times->id, $delivery_time_active)) $checked='selected="selected"' ;
                    $html_body .= '<option value="'.$delivery_times->id.'" '.$checked.$disabled.'>'.$delivery_times->name.'</option>';                       
                }
            }
            $html_body .='</select></div>'; 
        }        

        if ($html_body!='') {
            echo $html_head.$html_body;
        }
        ?>
    </div>
    <div class="after-filter-item"></div>  
<?php } ?>