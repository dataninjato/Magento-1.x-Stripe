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

class Appmerce_Stripe_SavedcardsController extends Mage_Core_Controller_Front_Action
{
    /**
     * Authenticate user
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * View cards
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Delete card
     */
    public function deleteAction()
    {
        //Get id of card
        if($id = $this->getRequest()->getParam('id')) {
            if(Mage::helper('stripe')->deleteCard($id)) {
                Mage::getSingleton('core/session')->addSuccess($this->__('Card was sucessfully deleted.'));
            } else {
                Mage::getSingleton('core/session')->addError($this->__('Card could not be deleted.'));
            }
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Card could not be deleted.'));
        }

        $this->_redirect('*/*/');
    }

    /**
     * Edit card
     */
    public function editAction()
    {
        //Get id of card
        if($id = $this->getRequest()->getParam('id')) {

            //Retrieve the card from Stripe
            if($card = Mage::helper('stripe')->retrieveCard($id)) {

                //Load the layout
                $this->loadLayout();
                $this->getlayout()->getBlock('appmerce_stripe_savedcards_edit')->assign('card', $card);
                $this->renderLayout();

            } else {
                Mage::getSingleton('core/session')->addError($this->__('Could not retrieve card from Stripe.'));
            }

        } else {
            Mage::getSingleton('core/session')->addError($this->__('Card does not exist.'));
        }
    }

    /**
     * Save card
     */
    public function saveAction()
    {
        //Get id of card
        if($id = $this->getRequest()->getParam('id')) {

            //Retrieve the card from Stripe
            if($card = Mage::helper('stripe')->retrieveCard($id)) {

                $card->address_line1 = $this->getRequest()->getPost('address_line1');
                $card->address_zip = $this->getRequest()->getPost('address_zip');
                $card->save();

                Mage::getSingleton('core/session')->addSuccess($this->__('Card was sucessfully saved.'));



            } else {
                Mage::getSingleton('core/session')->addError($this->__('Could not retrieve card from Stripe.'));
            }
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Card does not exist.'));
        }

        $this->_redirect('*/*/');
    }
}
