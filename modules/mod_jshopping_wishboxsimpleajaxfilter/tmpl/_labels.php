<?php defined('_JEXEC') or die('Restricted access');?>
<?php if (is_array($listLabels) && count($listLabels)){?>
    <div class="labels-filter filter-block-wrap">
    <input type="hidden" name="labels[]" value="0" /> 
    <?php 
    $lhtml_head='<div class="filter-head">'.JText::_('LABEL').":".'</div> ';
    $lhtml_body =""; 
    if($show_labels=='1') {
        foreach($listLabels as $label){
            $disabled="" ;
            if(!in_array($label->id,$labelInProducts) && $display_unavailable_value=='1') $disabled=' disabled="disabled" ';
            if(!in_array($label->id,$labelInProducts) && $display_unavailable_value=='0') $hide=1; else $hide=0; 
            if( $hide==0){
                $checked="";
                if (is_array($labels_active) && in_array($label->id, $labels_active)) $checked='checked="checked"' ;
                $lhtml_body .= '<div class="filter-item"><input type="checkbox" id="filter-label-'.$label->id.'" name="labels[]" value="'.$label->id.'" '.$checked.$disabled.'  /> <label for="filter-label-'.$label->id.'">'.$label->name.'</label></div>';
            }
        }
    } elseif ($show_labels=='2') {
        $lhtml_body .='<div class="filter-item"><select name="labels[]">';
        $lhtml_body .='<option value="">'.JText::_('JALL').'</option>';  
        foreach($listLabels as $label){
            $disabled="" ; 
            if(!in_array($label->id,$labelInProducts) && $display_unavailable_value=='1') $disabled=' disabled="disabled" ';
            if(!in_array($label->id,$labelInProducts) && $display_unavailable_value=='0') $hide=1; else $hide=0; 
            if( $hide==0){
                $checked="";
                if (is_array($labels_active) && in_array($label->id, $labels_active)) $checked='selected="selected"' ;
                $lhtml_body .= '<option  value="'.$label->id.'" '.$checked.$disabled.'>'.$label->name.'</option>';
            } 
        }
        $lhtml_body .='</select></div>'; 
    }
    if ($lhtml_body!='') {
        echo $lhtml_head.$lhtml_body;
    }
    ?>
    </div>
    <div class="after-filter-item"></div>
    <?php } ?>