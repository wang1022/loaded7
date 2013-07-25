<?php
/*
  $Id: coupon.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_OrderTotal_coupon extends lC_OrderTotal {
  public $output;

  protected $_title,
            $_code = 'coupon',
            $_status = false,
            $_sort_order;

  function lC_OrderTotal_coupon() {
    global $lC_Language;

    $this->output = array();

    $this->_title = $lC_Language->get('order_total_coupon_title');
    $this->_description = $lC_Language->get('order_total_coupon_description');
    $this->_status = (defined('MODULE_ORDER_TOTAL_COUPON_STATUS') && (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') ? true : false);
    $this->_sort_order = (defined('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER') ? MODULE_ORDER_TOTAL_COUPON_SORT_ORDER : null);
  }

  function process() {
    global $lC_ShoppingCart, $lC_Currencies;

    $key = 'Discount Coupon';
    $value = -10.99;
 //   foreach ($lC_ShoppingCart->getCoupons() as $key => $value) {
 //     if ($value > 0) {
        $this->output[] = array('title' => $key . ':',
                                'text' => $lC_Currencies->format($value),
                                'value' => $value);
 //     }
 //   }
  }
}
?>