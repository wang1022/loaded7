<?php
/**
  @package    catalog::modules::variants
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: file_upload.php v1.0 2013-08-08 datazen $
*/
class lC_Variants_file_upload extends lC_Variants_Abstract {
  const ALLOW_MULTIPLE_VALUES = false;
  const HAS_CUSTOM_VALUE = true;

  static public function parse($data) {
    global $lC_Currencies;
    
    $default_value = null;
      
    if (isset($data['simple_option']) && $data['simple_option'] !== false) {
      $options = array();
      $group_id = '';
      $group_title = '';
      unset($data['simple_option']);
      
      foreach($data as $key => $val) {
        if (isset($val['group_title']) && empty($val['group_title']) === false) {
          $group_title = $val['group_title'];
          $group_id = $val['group_id'];
          break;
        }
      }      
      
      $string = '<style>.file-inputs { .margin-top-neg(); }</style><div class="margin-left margin-bottom">' .
                '  <table class="full-width">' . 
                '    <tr>' .
                '      <td valign="top" class="third-width"><label class="margin-right margin-top">' . $group_title . '</label></td>' .
                '      <td valign="top"><div id="file_upload_container_' . $group_id . '">';

      reset($data);
      $cnt = 0;
      foreach($data as $key => $val) {  
        $price_ind = ((float)$val['price_modifier'] < 0.00) ? '-' : '+';
        $price_formatted = ((float)$val['price_modifier'] != 0.00) ? $price_ind . $lC_Currencies->format(number_format($val['price_modifier'], DECIMAL_PLACES), $lC_Currencies->getCode()) : null;
        $options[$val['value_id']] = $val['price_modifier']; 
        $group_id = $val['group_id'];
        $group_title = $val['group_title'];       
                    
        $string .= '        <div id="file_upload_div_' . $group_id . '_' . $val['value_id'] . '_' . $cnt . '" class="no-margin-top small-margin-bottom small-padding-left small-margin-left">' .
                   '         <label>' .
                   '           <input type="file" htitle="" group-id="' . $group_id . '" value-id="' . $val['value_id'] . '" class="file-inputs btn-primary btn-file" data-filename-placement="inside" title="' . $val['value_title'] . '" default="' . $val['value_title'] . '" name="simple_options_upload[]" value="' . $val['value_id'] . '" modifier="' . $val['price_modifier'] . '" onchange="refreshPrice();" id="simple_options_upload_' . $group_id . '_' . $val['value_id'] . '_' . $cnt . '">' .
                   '           <input type="hidden" name="simple_options[' . $group_id . '][' . $val['value_id'] . '][]" value="' . $val['value_id'] . '" modifier="' . $val['price_modifier'] . '" id="simple_options_' . $cnt . '">' .
                   '         </label><i id="simple_options_remove_' . $group_id . '_' . $val['value_id'] . '_' . $cnt . '" class="fa fa-times margin-left red hidden" style="cursor:pointer;" onclick="removeFileUploadRow(\'file_upload_div_' . $group_id . '_' . $val['value_id'] . '_' . $cnt . '\');"></i>' .
                   '       </div>';
        $cnt++;
      }                 
       
      $string .= '       </div></td>' .   
                 '    </tr>' .
                 '  </table>' .
                 '</div>';                     
      
    } else {      

      $string = '<div id="file_upload_container_' . $group_id . '">';
      $cnt = 0;
      foreach ( $data['data'] as $field ) {
        $string .= '       <div id="file_upload_div_' . $data['group_id'] . '_' . $field['id'] . '_' . $cnt . '" class="form-group margin-left">' .
                   '         <label class="label-control" style="width:29%;">' . $data['title'] . '</label>' . 
                   '         <input type="file" htitle="' . $data['title'] . '" modifier="variant" group-id="' . $data['group_id'] . '" value-id="' . $field['id'] . '" class="file-inputs btn-primary btn-file mid-margin-left" data-filename-placement="inside" title="' . $field['text'] . '" default="' . $field['text'] . '" name="variants_upload[]" value="' . $field['id'] . '" onchange="refreshPrice();" id="variants_upload_' . $data['group_id'] . '_' . $field['id'] . '_' . $cnt . '">' .
                   '         <input type="hidden" name="variants[' . $data['group_id'] . ']" value="' . $field['id'] . '" id="variants_' . $data['group_id'] . '_' . $field['id'] . '">' .
                   '         <i id="variants_remove_' . $group_id . '_' . $val['value_id'] . '_' . $cnt . '" class="fa fa-times margin-left red hidden" style="cursor:pointer;" onclick="removeFileUploadRow(\'file_upload_div_' . $data['group_id'] . '_' . $field['id'] . '_' . $cnt . '\');"></i>' .
                   '       </div>';        
        $cnt++;
      }
      $string .= '</div>'; 
    }             

    return $string;
  }

  static public function allowsMultipleValues() {
    return self::ALLOW_MULTIPLE_VALUES;
  }

  static public function hasCustomValue() {
    return self::HAS_CUSTOM_VALUE;
  }
}
?>