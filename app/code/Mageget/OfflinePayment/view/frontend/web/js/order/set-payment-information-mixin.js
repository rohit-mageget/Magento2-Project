define([
    'jquery',
    'mage/utils/wrapper',
    'Mageget_OfflinePayment/js/order/custom-assigner'
], function ($, wrapper, ordercommentAssigner) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, messageContainer, paymentData) {
            ordercommentAssigner(paymentData);

            return originalAction(messageContainer, paymentData);
        });
    };
});