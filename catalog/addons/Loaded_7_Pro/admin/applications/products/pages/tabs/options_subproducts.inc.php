<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: options_subproducts.inc.php v1.0 2014-01-24 datazen $
*/
global $lC_Language, $pInfo; 
?>
<div id="multiTypeControlButtons" class="button-group small-margin-top">
  <label id="lbl-radio-1" for="type_radio_1" class="button green-active">
    <input type="radio" onclick="toggleMultiSkuTypeRadioGroup(this.value);" name="multi_sku_type_radio_group" id="type_radio_1" value="1" />
    <?php echo $lC_Language->get('text_combo_options'); ?>
  </label>
  <label id="lbl-radio-2" for="type_radio_2" class="button green-active">
    <input type="radio" onclick="toggleMultiSkuTypeRadioGroup(this.value);" name="multi_sku_type_radio_group" id="type_radio_2" value="2" />
    <?php echo $lC_Language->get('text_sub_products'); ?>
  </label>
</div>  
         
<div id="multiSKUOptionsContainer" class="margin-top">  
	<span class="float-right" style="margin:-46px 0px 4px 0;"><a class="button icon-plus-round green-gradient glossy compact" href="javascript:void(0)" onclick="addMultiSKUOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>
	<table width="100%" style="margin-top:-8px;" id="multiSKUOptionsTable" class="simple-table">
	  <thead>
	    <tr>
	      <th scope="col" class="align-center">&nbsp;</th>
	      <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_option_set_name'); ?></th>
	      <th scope="col" class="align-center">&nbsp;</th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_weight'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sku'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_qoh'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_price'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_img'); ?></th>        	      
        <th scope="col" class="align-center hide-below-480"><?php echo $lC_Language->get('table_heading_status'); ?></th>
	      <th scope="col" class="align-right" width="50px"><?php echo $lC_Language->get('table_heading_action'); ?></th>
	    </tr>
	  </thead>
	  <tbody id="multiSKUOptionsTbody" class="sorted_table"><?php echo ((isset($pInfo)) ? lC_Products_Admin_Pro::getComboOptionsContent($pInfo->get('variants')) : null); ?></tbody>
	</table>       	
</div>

<div id="subProductsContainer" class="margin-top">    
  <table width="100%" id="subProductsTable" class="simple-table">
    <thead>
      <tr>
        <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_name'); ?></th>
        <th scope="col" class="align-center hide-below-480"><?php echo $lC_Language->get('table_heading_status'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_weight'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sku'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_qoh'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_base_price'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_img'); ?></th>
        <th scope="col" class="align-right" width="50px"><?php echo $lC_Language->get('table_heading_action'); ?></th>
      </tr>
    </thead>      
    <tbody id="subProductsTbody" class=""></tbody>
  </table>          
</div>       
<script>
$(document).ready(function() {
  var edit = '<?php echo (isset($pInfo)) ? '1' : '0'; ?>';
  var has_subproducts = '<?php echo (isset($pInfo) && $pInfo->get('has_subproducts') == '1') ? '1' : '0'; ?>';
  var has_variants = '<?php echo (isset($pInfo) && $pInfo->get('has_children') == '1') ? '1' : '0'; ?>';
  var optionsDiv = $("input:radio[name=multi_sku_type_radio_group]").val();
  toggleMultiSkuTypeRadioGroup(optionsDiv);
  
  if (edit == '1') { 	
    if (has_subproducts == '1') {
      getSubProductsRows();
      _updateInvControlType('2');
      $('#type_radio_2').click();
      $('label[for=\'type_radio_1\']').addClass('disabled');
      $('#type_radio_1').removeAttr('onclick');
    } else if (has_variants == '1') {
    	_updateInvControlType('2');
    	$('#lbl-radio-1').removeClass('disabled');
      $('#type_radio_1').click();
      $('label[for=\'type_radio_2\']').addClass('disabled');
      $('#type_radio_2').removeAttr('onclick');      
    }
  } else {	
    $('#type_radio_1').click();
  }
  addSubProductsRow(false, false, false);  
  $("#subProductsTable tr:last-child td:first-child").find('input').focus();  
}); 

function getSubProductsRows() {
  var subproducts = <?php echo (isset($pInfo)) ? json_encode($pInfo->get('subproducts')) : json_encode(array()); ?>;
  var editLink = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'products=PID&subproduct=1&cID=' . $_GET['cID'] . '&action=save'); ?>'; 
  var output = '';
  $.each(subproducts, function(key, val) {
    output += '<tr id="tr-' + key + '">'+
              '  <td><input type="text" class="input" onblur="addSubProductsRow(true, this, \'' + key + '\');" onfocus="this.select();" tabindex="' + key + '1" id="sub_products_name_' + key + '" name="sub_products_name[' + key + ']" value="' + val.products_name + '"></td>'+
              '  <td class="align-center align-middle"><input type="hidden" name="sub_products_id[' + key + ']"  value="' + val.products_id + '">'+
              '    <a onclick="setSubProductDefault(\'' + key + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_set_as_default'); ?>" href="javascript:void(0);"><span id="sub_products_default_span_' + key + '" class="icon-star icon-size2 margin-right ' + ((val.is_subproduct == 2) ? "icon-orange" : "icon-grey") + '"></span></a>'+
              '    <a onclick="setSubProductStatus(\'' + key + '\');" class="with-tooltip" id="sub_products_status_link[' + key + ']" title="<?php echo $lC_Language->get('text_sub_products_enable_disable'); ?>" href="javascript:void(0);"><span id="sub_products_status_span_' + key + '" class="icon-tick icon-size2 icon-green"></span></a>'+
              '    <input type="hidden" id="sub_products_default_' + key + '" name="sub_products_default[' + key + ']" class="sub_products_default" value="' + ((val.is_subproduct == 2) ? "1" : "0") + '">'+
              '    <input type="hidden" id="sub_products_status_' + key + '" name="sub_products_status[' + key + ']" value="1">'+
              '  </td>'+
              '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + key + '2" name="sub_products_weight[' + key + ']" value="' + val.products_weight + '"></td>'+
              '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + key + '3" name="sub_products_sku[' + key + ']" value="' + val.products_sku + '"></td>'+
              '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + key + '4" name="sub_products_qoh[' + key + ']" value="' + val.products_quantity + '"></td>'+
              '  <td style="white-space:nowrap;"><div class="inputs" style="display:inline; padding:8px 0;"><span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span><input type="text" class="input-unstyled" style="width:87%;" onchange="$(\'#sub_products_price_1_' + key + '\').val(this.value);" onfocus="this.select();" tabindex="' + key + '5" name="sub_products_price[' + key + ']" value="' + val.products_price + '"></div></td>'+
              '  <td class="align-center align-middle">'+
              '    <input style="display:none;" type="file" id="sub_products_image_' + key + '" name="sub_products_image[' + key + ']" onchange="setSubProductImage(\'' + key + '\');" multiple />'+
              '    <span class="icon-camera icon-size2 cursor-pointer with-tooltip ' + ((val.image != '' && val.image != null) ? 'icon-green' : 'icon-grey') + '" title="' + ((val.image != '' && val.image != null) ? val.image : null) + '" id="fileSelectButton-' + key + '" onclick="document.getElementById(\'sub_products_image_' + key + '\').click();"></span>'+
              '  </td>'+
              '  <td class="align-right align-middle">'+
        //      '    <a href="' + editLink.replace('PID', val.products_id) + '" class="with-tooltip margin-right" title="<?php echo $lC_Language->get('text_sub_products_edit'); ?>"><span class="icon-pencil icon-size2 icon-blue"></span></a>'+
              '    <a onclick="removeSubProductRow(\'' + key + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_remove'); ?>" href="javascript:void(0)"><span class="icon-cross icon-size2 icon-red"></span></a>'+
              '  </td>'+
              '</tr>';
  });            
  $('#subProductsTable> tbody').append(output);
}

function addSubProductsRow(include_price_row, e, key) {
  if($("#subProductsTable tbody").children().length > 0) {
    var id = $('#subProductsTable tr:last').attr('id').replace('tr-', '');
  } else {
    var id = 0;
  }
  if (e.value != undefined) $('#name-td-' + key).text(e.value);                
  if($('#sub_products_name_' + id).val() == '') return false;

  var prevName = $('#sub_products_name_' + id).val();
  var nextId = parseInt(id) + 1;
  var row = '<tr id="tr-' + nextId + '">'+
            '  <td><input type="text" class="input" onblur="addSubProductsRow(true, this, \'' + key + '\');" onfocus="this.select();" tabindex="' + nextId + '1" id="sub_products_name_' + nextId + '" name="sub_products_name[' + nextId + ']" value=""></td>'+
            '  <td class="align-center align-middle">'+
            '    <a onclick="setSubProductDefault(\'' + nextId + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_set_as_default'); ?>" href="javascript:void(0);"><span id="sub_products_default_span_' + nextId + '" class="icon-star icon-size2 margin-right icon-grey"></span></a>'+
            '    <a onclick="setSubProductStatus(\'' + nextId + '\');" class="with-tooltip" id="sub_products_status_link[' + nextId + ']" title="<?php echo $lC_Language->get('text_sub_products_enable_disable'); ?>" href="javascript:void(0);"><span id="sub_products_status_span_' + nextId + '" class="icon-tick icon-size2 icon-green"></span></a>'+
            '    <input type="hidden" id="sub_products_default_' + nextId + '" name="sub_products_default[' + nextId + ']" class="sub_products_default" value="">'+
            '    <input type="hidden" id="sub_products_status_' + nextId + '" name="sub_products_status[' + nextId + ']" value="1">'+
            '  </td>'+
            '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + nextId + '2" name="sub_products_weight[' + nextId + ']" value=""></td>'+
            '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + nextId + '3" name="sub_products_sku[' + nextId + ']" value=""></td>'+
            '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + nextId + '4" name="sub_products_qoh[' + nextId + ']" value=""></td>'+
            '  <td style="white-space:nowrap;"><div class="inputs" style="display:inline; padding:8px 0;"><span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span><input type="text" class="input-unstyled" style="width:87%;" onchange="$(\'#sub_products_price_1_' + nextId + '\').val(this.value);" onfocus="this.select();" tabindex="' + nextId + '5" name="sub_products_price[' + nextId + ']" value="0.0000"></div></td>'+
            '  <td class="align-center align-middle">'+
            '    <input style="display:none;" type="file" id="sub_products_image_' + nextId + '" name="sub_products_image[' + nextId + ']" onchange="setSubProductImage(\'' + nextId + '\');" multiple />'+
            '    <span class="icon-camera icon-size2 icon-grey cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_select_image'); ?>" id="fileSelectButton-' + nextId + '" onclick="document.getElementById(\'sub_products_image_' + nextId + '\').click();"></span>'+
            '  </td>'+
            '  <td class="align-right align-middle">'+
            '    <a onclick="removeSubProductRow(\'' + nextId + '\', \'' + id + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_remove'); ?>" href="javascript:void(0)"><span class="icon-cross icon-size2 icon-red"></span></a>'+
            '  </td>'+
          '</tr>';
          
  $('#subProductsTable > tbody').append(row);
   
  if (include_price_row == '1') {       
    var ok = '<?php echo (defined('ADDONS_SYSTEM_LOADED_7_PRO_STATUS') && ADDONS_SYSTEM_LOADED_7_PRO_STATUS == '1') ? '1' : '0'; ?>';
    var groups = <?php echo json_encode(lC_Customer_groups_Admin::getAll()); ?>;
    $.each(groups.entries, function( key, val ) {
      $('#no-options-' + val.customers_group_id).remove();
      var prow = '<tr class="trp-' + nextId + '">'+
                 '  <td id="name-td-' + nextId + '" class="element">' + prevName + '</td>'+
                 '  <td>'+
                 '    <div class="inputs' + ((val.customers_group_id == '1' || ok == '1') ? '' : ' disabled') + '" style="display:inline; padding:8px 0;">'+
                 '      <span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>'+
                 '      <input type="text" class="input-unstyled" onfocus="$(this).select()" value="0.0000" id="sub_products_price_' + val.customers_group_id + '_' + id + '" name="sub_products_price[' + val.customers_group_id + '][' + id + ']" ' + ((val.customers_group_id == '1' || ok == '1') ? '' : ' DISABLED') + '>'+
                 '    </div>'+
                 '  </td>'+
                 '</tr>';                    
                 
      $('#tbody-' + val.customers_group_id).append(prow);
    });
  }
}   

function removeSubProductRow(id) {
  $('#tr-' + id).remove();
  $('.trp-' + id).remove();
  if($("#subProductsTable tbody").children().length == 0) addSubProductsRow(false, false, false);
}

function setSubProductDefault(id) {
  $('.icon-star').removeClass('icon-orange').addClass('icon-grey');
  $('#sub_products_default_span_' + id).removeClass('icon-grey').addClass('icon-orange');
  $('.sub_products_default').val('0');
  $('#sub_products_default_' + id).val('1');
} 

function setSubProductStatus(id) {
  var v = $('#sub_products_status_' + id).val();
  if (v == '1') {
    $('#sub_products_status_' + id).val('0');
    $('#sub_products_status_span_' + id).removeClass('icon-green icon-tick').addClass('icon-red icon-cross');
  } else {
    $('#sub_products_status_' + id).val('1');
    $('#sub_products_status_span_' + id).removeClass('icon-red icon-cross').addClass('icon-green icon-tick');
  } 
}  

function setSubProductImage(id) {
  setTimeout(function(){
    var v = ($('#sub_products_image_' + id).val());
    if (v != '') {
      $('#fileSelectButton-' + id).removeClass('icon-grey').addClass('icon-green').prop('title', v);
    } else {                                                              
      var title = '<?php echo $lC_Language->get('text_sub_products_select_image'); ?>';
      $('#fileSelectButton-' + id).removeClass('icon-green').addClass('icon-grey').prop('title', title);
    }
  }, 500);
} 

/* 
 * Multi SKU functions 
 */
function removeMultiSKUOptionsRow(id) {
  $.modal.confirm('<?php echo $lC_Language->get('text_remove_row'); ?>', function() {
    $('#trmso-' + id).remove();
  //  $('.trpmso-' + id).remove();
  
	  // check if no rows and activate/deactivate sub products button
	  var hasInfo = $('#multiSKUOptionsTbody').children().length;
	  if (hasInfo == 0) {
	  	$('label[for=\'type_radio_2\']').removeClass('disabled');
	    $('#type_radio_2').attr('onclick', 'toggleMultiSkuTypeRadioGroup(this.value)'); 	  
	  } else {
	  	$('label[for=\'type_radio_2\']').addClass('disabled');
	  	$('#type_radio_2').removeAttr('onclick'); 	  
	  }
  }, function() {
    return false;
  }); 	
}

function toggleMultiSKUOptionsStatus(id) {
	var current = $('#variants_status_' + id).val();
  if (current == '1') {
    $('#variants_status_' + id).val('0');
    $('#variants_status_span_' + id).removeClass('icon-green icon-tick').addClass('icon-red icon-cross');
  } else {
    $('#variants_status_' + id).val('1');
    $('#variants_status_span_' + id).removeClass('icon-red icon-cross').addClass('icon-green icon-tick');
  } 
}  

function toggleMultiSKUOptionsFeatured(id) {
	$('.default-combo').val('0');
	$('.default-combo-span').removeClass('icon-orange').addClass('icon-grey');	
	$('#variants_default_combo_span_' + id).removeClass('icon-grey').addClass('icon-orange');
	$('#variants_default_combo_' + id).val('1');
}

function setMultiSKUImage(id) {
  setTimeout(function(){
    var v = ($('#multi_sku_image_' + id).val());
    if (v != '') {
      $('#fileSelectButtonMultiSKU-' + id).removeClass('icon-grey').addClass('icon-green').prop('title', v);
    } else {                                                              
      var title = '<?php echo $lC_Language->get('text_sub_products_select_image'); ?>';
      $('#fileSelectButtonMultiSKU-' + id).removeClass('icon-green').addClass('icon-grey').prop('title', title);
    }
  }, 500);
} 

function toggleMultiSkuTypeRadioGroup(val) {
  if (val == '1') {
    $('#multiSKUOptionsContainer').slideDown();  
    $('#subProductsContainer').slideUp();
  } else {
    $('#multiSKUOptionsContainer').slideUp();  
    $('#subProductsContainer').slideDown();            
  }
  
}  
</script>