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

$installer = $this;
$installer->startSetup();
$installer->run("
    CREATE TABLE `{$installer->getTable('stripe/customer')}` (
        `id` int(10) unsigned NOT NULL auto_increment,
        `customer_id` int(10) unsigned NOT NULL,
        `token` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE `{$installer->getTable('stripe/order_failed')}` (
        `id` int(10) unsigned NOT NULL auto_increment,
        `date` datetime NOT NULL default '0000-00-00 00:00:00',
        `order_id` int(10) unsigned NOT NULL,
        `customer_id` int(10) unsigned default NULL,
        `cc_type` varchar(255),
        `cc_last4` varchar(10),
        `amount` decimal(12,4),
        `message` text,
        `type` varchar(255),
        `code` varchar(255),
        `token` varchar(255),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();
