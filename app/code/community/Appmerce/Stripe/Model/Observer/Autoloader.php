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

class Appmerce_Stripe_Model_Observer_Autoloader extends Varien_Event_Observer
{
    /**
     * This an observer function for the event 'controller_front_init_before'.
     * It prepends our autoloader, so we can load the extra libraries.
     *
     * @param Varien_Event_Observer $event
     */
    public function controllerFrontInitBefore( $event )
    {
        spl_autoload_register( array($this, 'load'), true, true );
    }

    /**
     * Autoload the Stripe library
     *
     * @param string $class
     */
    public static function load()
    {
        require_once( Mage::getBaseDir('lib') . '/Stripe/' . 'init.php' );
    }
}
