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

class Appmerce_Stripe_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DASHBOARD_PAYMENTS_URL = 'https://dashboard.stripe.com/payments/';
    const API_VERSION = '2016-03-07';

    /**
     * Set Stripe API key
     */
    public function setApiKey()
    {
        try {
            \Stripe\Stripe::setApiKey(Mage::getStoreConfig('payment/stripe_cc/api_secret_key'));
            \Stripe\Stripe::setApiVersion(self::API_VERSION);
        } catch (Exception $e) {
            Mage::throwException($this->__('Cannot set Stripe API key'));
        }
    }

    /**
     * Retrieve customer from Stripe
     *
     * @return bool|\Stripe\Customer
     */
    public function retrieveCustomer()
    {
        $this->setApiKey();

        //Get the customer token from magento
        if($token = Mage::helper('stripe')->getCustomer()->getToken())
        {
            try {
                return \Stripe\Customer::retrieve(Mage::helper('core')->decrypt($token));
            } catch(Stripe\Error\Base $e) {

                $jsonBody = $e->getJsonBody();

                //If invalid request, customer doesn't exist so delete them from Magento
                if(isset($jsonBody['error']['type']) && $jsonBody['error']['type'] === 'invalid_request_error') {
                    $this->deleteCustomer();
                }

            } catch(Exception $e) {
                //Fail gracefully
                Mage::log($this->__('Could not retrieve customer'));
            }
        }

        return false;
    }

    /**
     * Get customer from database
     *
     * @return mixed
     */
    public function getCustomer()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $model = Mage::getModel('stripe/customer');

        return $model->load($customer->getId(), 'customer_id');
    }

    /**
     * Delete customer from database
     *
     * @return Mage_Core_Model_Abstract
     */
    public function deleteCustomer()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $model = Mage::getModel('stripe/customer');

        return $model->load($customer->getId(), 'customer_id')->delete();
    }

    /**
     * Get payments dashboard URL
     *
     * @return string
     */
    public function getPaymentsDashboardUrl()
    {
        return self::DASHBOARD_PAYMENTS_URL;
    }

    /**
     * Calculates amount based on currency
     * https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support
     *
     * @param $amount
     * @param $currencyCode
     * @return float
     */
    public function calculateCurrencyAmount($amount, $currencyCode)
    {
        $zeroDecimalCurrencies = array(
            'BIF', //Burundian Franc
            'CLP', //Chilean Peso
            'DJF', //Djiboutian Franc
            'GNF', //Guinean Franc
            'JPY', //Japanese Yen
            'KMF', //Comorian Franc
            'KRW', //South Korean Won
            'MGA', //Malagasy Ariary
            'PYG', //Paraguayan Guaraní
            'RWF', //Rwandan Franc
            'VND', //Vietnamese Đồng
            'VUV', //Vanuatu Vatu
            'XAF', //Central African Cfa Franc
            'XOF', //West African Cfa Franc
            'XPF', //Cfp Franc
        );

        if(in_array($currencyCode, $zeroDecimalCurrencies)) {
            $amount = round($amount, 0);
        } else {
            $amount = $amount * 100;
        }

        return $amount;
    }

    /**
     * Create customer
     *
     * @param $token
     * @return \Stripe\Customer
     */
    public function createCustomer($token)
    {
        //Set API Key
        $this->setApiKey();

        //Create the customer
        try {

            //Get customer object
            $customer = Mage::getSingleton('customer/session')->getCustomer();

            //Create customer in Stripe
            $stripeCustomer = \Stripe\Customer::create(array(
                'source' => $token,
                'email' => $customer->getEmail()
            ));

            //Create stripe customer in magento
            $model = Mage::getModel('stripe/customer');
            $model->setCustomerId($customer->getId());
            $model->setToken(Mage::helper('core')->encrypt($stripeCustomer->id));
            $model->save();

        } catch (Exception $e) {
            //Fail gracefully
            Mage::log($this->__('Could not create customer'));
        }

        return $stripeCustomer;
    }

    /**
     * Get Declined Orders Count
     *
     * @param $orderId
     * @param bool $fraudulent
     * @return mixed
     */
    public function getDeclinedOrdersCount($orderId, $fraudulent = false)
    {
        //Get any declined, or only fraudelent declined
        if($fraudulent) {
            $code = 'card_declined_fraudulent';
        } else {
            $code = '%card_declined%';
        }

        //Count failed orders
        return Mage::getModel('stripe/order_failed')
            ->getCollection()
            ->addFieldToFilter('order_id', array('eq' => $orderId))
            ->addFieldToFilter('code', array('like' => $code))
            ->getSize();
    }

    /**
     * Delete card
     *
     * @param $cardId
     * @return bool
     */
    public function deleteCard($cardId)
    {
        if($customer = $this->retrieveCustomer())
        {
            try {
                $customer->sources->retrieve($cardId)->delete();
            } catch(Exception $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieve card
     *
     * @param $cardId
     * @return mixed
     */
    public function retrieveCard($cardId)
    {
        if($customer = $this->retrieveCustomer())
        {
            return $customer->sources->retrieve($cardId);
        }

        return false;
    }

    /**
     * Get card description and class based on Magento card code
     *
     * @param $code
     * @return mixed
     */
    public function getCardInfoByCode($code)
    {
        $cards = array(
            'VI' => array(
                'label' => 'Visa',
                'class' => 'visa',
            ),
            'MC' => array(
                'label' => 'Mastercard',
                'class' => 'mastercard',
            ),
            'AE' => array(
                'label' => 'American Express',
                'class' => 'amex',
            ),
            'DI' => array(
                'label' => 'Discover',
                'class' => 'discover',
            ),
            'DC' => array(
                'label' => 'Diners Club',
                'class' => 'dinersclub',
            ),
            'JCB' => array(
                'label' => 'JCB',
                'class' => 'jcb',
            ),
        );

        return (isset($cards[$code])) ? $cards[$code] : false;
    }

    /**
     * @param $name
     * @return string
     */
    public function getClassByName($name)
    {
        $name = strtolower($name);

        $cards = array(
            'visa' => 'visa',
            'mastercard' => 'mastercard',
            'american express' => 'amex',
            'discover' => 'discover',
            'diners club' => 'dinersclub',
            'jcb' => 'jcb',
        );

        return (isset($cards[$name])) ? $cards[$name] : '';
    }

}
