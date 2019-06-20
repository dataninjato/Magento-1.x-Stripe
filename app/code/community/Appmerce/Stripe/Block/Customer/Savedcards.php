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

class Appmerce_Stripe_Block_Customer_Savedcards extends Mage_Core_Block_Template
{
    /**
     * Get saved cards
     *
     * @return bool
     */
    public function getCards()
    {
        //If customer exists in our Stripe table
        if($cust = Mage::helper('stripe')->retrieveCustomer()) {

            //Get stored cards
            $cards = $cust->sources->data;

            //If there are stored cards, set saved cards flag
            if(sizeof($cards) > 0) {
                return $cards;
            }
        }

        return false;
    }

    /**
     * Get save url
     *
     * @return mixed
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('_current'=>true, 'back'=>null));
    }

    /**
     * Get back url
     *
     * @return mixed
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/', array('_current'=>false, 'back'=>null));
    }
}
