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

class Appmerce_Stripe_Block_Adminhtml_Orderfailed extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Appmerce_Stripe_Block_Adminhtml_Orderfailed constructor.
     */
    public function __construct()
    {
        $this->_blockGroup = 'stripe';
        $this->_controller = 'adminhtml_orderfailed';
        $this->_headerText = $this->__('Failed Stripe Orders');
        parent::__construct();
        $this->_removeButton('add');
    }
}
