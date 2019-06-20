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

class Appmerce_Stripe_Model_Observer
{
    /**
     * @param $observer
     * @return mixed
     */
    public function addStatusToSalesOrderGrid($observer)
    {
        $block = $observer->getEvent()->getBlock();

        if($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {

            $block->addColumnAfter('appmerce_stripe_status', array(
                'header' => Mage::helper('stripe')->__('Stripe Security'),
                'index' => 'appmerce_stripe_status',
                'align' => 'center',
                'width' => '80px',
                'filter' => false,
                'renderer' => 'stripe/adminhtml_sales_order_grid_renderer_state',
                'sortable' => false,
            ), 'real_order_id');
        }

        return $observer;
    }

}
