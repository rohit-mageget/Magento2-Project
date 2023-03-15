define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (Component,
              rendererList) {
        'use strict';
        rendererList.push(
            {
                type: 'simple',
                component: 'Mageget_OfflinePayment/js/view/payment/method-renderer/simple-method'
            }
        );
        return Component.extend({});
    }
);