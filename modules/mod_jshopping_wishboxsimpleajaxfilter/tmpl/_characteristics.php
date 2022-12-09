<?php
	// 
	defined('_JEXEC') or die;
	
	// 
	// 
	use \Joomla\CMS\Language\Text;
?>
<?php if (is_array($characteristic_fields) && count($characteristic_fields) && $getDisplayCharacteristics) { ?>
<div class="characteristic-filter filter-block-wrap">
	<div class="filter-head">
		<?php echo Text::_('CHARACTERISTICS').":"?>
	</div>
	<?php 
	foreach ($getDisplayCharacteristics as $extraFieldId=>$extraFieldValues) {
		$chfv='';
		if (is_array($characteristic_fieldvalues[$extraFieldId])) {
			$chfv = $characteristic_fieldvalues[$extraFieldId];
		}
		if ($characteristic_fields[$extraFieldId]->type=='0') {
			echo '<input type="hidden" name="extra_fields['. $extraFieldId.'][]" value="0" />';
		}
		$multi='';
		$marr='';
		if ($show_characteristics=="2") {
			if ($all_characteristic[$extraFieldId]->multilist=="1") {
				$multi = "multiple";
				$marr = "[]";                   
			}
			echo "<div class='char-head'>".$characteristic_fields[$extraFieldId]->name."</div>";
			echo "<div  class='filter-item'><select ".$multi." name='extra_fields[".$extraFieldId."]". $marr."'>
			<option value=''>".Text::_('JSHOP_ALL')."</option>";
			asort($extraFieldValues, SORT_NATURAL | SORT_FLAG_CASE);
			foreach (array_unique($extraFieldValues) as $auefv) {
				if ($chfv) {
					$auefvtext=$chfv[$auefv];
				} else {
					$auefvtext=$auefv;
				} 
				if ($extra_fields_active[$extraFieldId] == $auefv || (is_array($extra_fields_active[$extraFieldId]) && in_array($auefv,$extra_fields_active[$extraFieldId]))) {
					$checked =' selected="selected" '; 
				} else {
					$checked='';
				}
				
			echo "<option value='".$auefv."' $checked>$auefvtext</option>";
			}
			echo "</select></div>";
		} elseif ($show_characteristics=="1") {
			echo "<div class='char-head'>".$characteristic_fields[$extraFieldId]->name."</div>";
			if ($all_characteristic[$extraFieldId]->type == "0") {
				echo "<div  class='filter-item'>";
				asort($extraFieldValues, SORT_NATURAL | SORT_FLAG_CASE);
				foreach (array_unique($extraFieldValues) as $auefv) {
					if ($chfv) {
						$auefvtext=$chfv[$auefv];
					} else {
						$auefvtext=$auefv;
					}
					if ($extra_fields_active[$extraFieldId] == $auefv || (is_array($extra_fields_active[$extraFieldId]) && in_array($auefv,$extra_fields_active[$extraFieldId]))) {
						$checked =" checked='checked' "; 
					} else {
						$checked='';
					}
					echo "<div class='filter-inem-value'><input type='checkbox' name='extra_fields[".$extraFieldId."][]' id='filter-ef-".$auefv."' value='".$auefv."' $checked /> <label for='filter-ef-".$auefv."'>$auefvtext</label></div>";
				}
				echo "</div>";
			} else {
				if ($all_characteristic[$extraFieldId]->multilist=="1") {
					$multi = "multiple";
					$marr = "[]";
				}
				echo "<div  class='filter-item'>
				<select ".$multi." name='extra_fields[".$extraFieldId."]". $marr."'>
				<option value=''>"._JSHOP_ALL."</option>";
				asort($extraFieldValues, SORT_NATURAL | SORT_FLAG_CASE);
				foreach (array_unique($extraFieldValues) as $auefv) {
					if ($chfv) {
						$auefvtext=$chfv[$auefv];
					} else {
						$auefvtext=$auefv;
					}
					if ($extra_fields_active[$extraFieldId]== $auefv || (is_array($extra_fields_active[$extraFieldId]) && in_array($auefv,$extra_fields_active[$extraFieldId]))) {
						$checked =' selected="selected" '; 
					} else {
						$checked='';
					}
				echo "<option value='".$auefv."' $checked>$auefvtext</option>";
				}
				echo "</select></div>";
			}
		}
	}
	?>
</div>
<?php } ?>
<div class="after-filter-item"></div>