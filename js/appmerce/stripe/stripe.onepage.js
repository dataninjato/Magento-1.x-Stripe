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

var appmerce = appmerce || {};

appmerce.stripeCc = (function($) {

    var self = {},
        $inputs = {};

    /*
    * Initialize the form
    *
    */
    self.init = function(enabledCards) {

        //Append fields required for IWD Onepage
        $('#payment_form_stripe')
            .append('<input id="stripe_cc_owner" type="hidden" value="" />')
            .append('<input id="stripe_cc_expiration_month" type="hidden" value="" />')
            .append('<input id="stripe_cc_expiration_year" type="hidden" value="" />');

        //Shortcut to fields
        $inputs.cardNumber = $('#stripe_cc_number');
        $inputs.cardExpiry = $('#stripe_cc_exp');
        $inputs.cardCVC = $('#stripe_cc_cvc');
        $inputs.cardExpMonth = $('#stripe_cc_expiration_month');
        $inputs.cardExpYear = $('#stripe_cc_expiration_year');

        //Set input mask for each field
        $inputs.cardNumber.payment('formatCardNumber');
        $inputs.cardExpiry.payment('formatCardExpiry');
        $inputs.cardCVC.payment('formatCardCVC');

        //Break expiry out into month and year
        $inputs.cardExpiry.blur(function() {
            var expiry = $(this).val().split(' / ');
            $inputs.cardExpMonth.val(expiry[0]);
            $inputs.cardExpYear.val(expiry[1]);
        });

        //Toggle new card form
        $('#stripe-saved-card').change(function() {
            if($(this).val() === '') {
                $('#stripe-cards-select-new').show();
                $inputs.cardNumber.focus();
            } else {
                $('#stripe-cards-select-new').hide();
            }
        });

    };

    return self;

}(window.appmerce.$||window.jQuery));
