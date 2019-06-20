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

class Appmerce_Stripe_Block_Adminhtml_Orderfailed_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Appmerce_Stripe_Block_Adminhtml_Orderfailed_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('date');
        $this->setId('appmerce_stripe_orderfailed_grid');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'stripe/order_failed_collection';
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return Mage::helper('stripe')->getPaymentsDashboardUrl() . Mage::helper('core')->decrypt($row->getToken());
    }

    /**
     * @return mixed
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return mixed
     */
    protected function _prepareColumns()
    {
        $this->addColumn('order_id', array(
            'header'=> $this->__('Order ID'),
            'index' => 'order_id',
            'width' => '150px',
        ));

        $this->addColumn('date', array(
            'header'=> $this->__('Date'),
            'index' => 'date',
            'type' => 'datetime',
        ));

        $this->addColumn('customer_id', array(
            'header'=> $this->__('Customer'),
            'index' => 'customer_id',
            'width' => '150px',
        ));

        $this->addColumn('code', array(
            'header'=> $this->__('Code'),
            'index' => 'code',
        ));

        $this->addColumn('message', array(
            'header'=> $this->__('Message'),
            'index' => 'message',
        ));

        $this->addColumn('cc_type', array(
            'header'=> $this->__('Card Type'),
            'index' => 'cc_type',
        ));

        $this->addColumn('cc_last4', array(
            'header'=> $this->__('Last 4'),
            'index' => 'cc_last4',
        ));

        $this->addColumn('amount', array(
            'header'=> $this->__('Amount'),
            'index' => 'amount',
            'type' => 'currency',
            'currency' => 'base_currency_code',
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('order_id');
        $this->getMassactionBlock()->setFormFieldName('failed_order_ids');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete', array('' => '')),
            'confirm' => $this->__('Are you sure?'),
        ));

        return $this;
    }
}
