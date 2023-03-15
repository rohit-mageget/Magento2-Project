define([
    'jquery'
], function ($) {
    'use strict';

    return function (paymentData) {

        if (paymentData['extension_attributes'] === undefined) {
            paymentData['extension_attributes'] = {};
        }
        // alert(jQuery('[name="payment[company_name]"]').val());
        paymentData['extension_attributes']['company_name'] = jQuery('[name="payment[company_name]"]').val();
    };
});