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

class Appmerce_Stripe_OrderfailedController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('report/appmerce/orderfailed')
            ->_title($this->__('Appmerce'))->_title($this->__('Failed Stripe Orders'))
            ->_addBreadcrumb($this->__('Appmerce'), $this->__('Appmerce'))
            ->_addBreadcrumb($this->__('Failed Stripe Orders'), $this->__('Failed Stripe Orders'));
        return $this;
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('appmerce/orderfailed');
    }

    /**
     *
     */
    public function massDeleteAction()
    {
        $failedOrderIds = $this->getRequest()->getParam('failed_order_ids');

        if(!is_array($failedOrderIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select records to delete.'));
        } else {
            try {
                $model = Mage::getModel('stripe/order_failed');
                foreach ($failedOrderIds as $failedOrderId) {
                    $model->load($failedOrderId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('Total of %d record(s) were deleted.', count($failedOrderIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
}
