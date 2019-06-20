<?php
/**
 * Appmerce - Applications for Ecommerce
 * http://www.appmerce.com
 *
 * @extension   Stripe
 * @type        Payment method
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Magento
 * @package     Appmerce_Stripe
 * @copyright   Copyright (c) 2011-2019 Appmerce (http://www.appmerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Appmerce_Stripe_Block_Adminhtml_Sales_Order_Grid_Renderer_State extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $result = '';
        $order = Mage::getModel('sales/order')->load($row->getId());
        $payment = $order->getPayment();
        $method = $payment->getMethodInstance()->getCode();

        if($method === 'stripe_cc') {

            //Get CVC result
            $cvc_result = $payment->getCcCidStatus();

            //Get AVS result (stored in two parts: line1/zip)
            $avs_status = explode('/', $payment->getCcAvsStatus());

            //Get declined orders
            $declined_orders = Mage::helper('stripe')->getDeclinedOrdersCount($order->getIncrementId());

            //Show the status icon
            if($cvc_result !== 'pass' || $avs_status[1] !== 'pass' || $declined_orders > 0) {
                $result = '<i class="icon-warning"></i>';
            } else {
                $result = '<i class="icon-pass"></i>';
            }

        }

        return $result;
    }

}
