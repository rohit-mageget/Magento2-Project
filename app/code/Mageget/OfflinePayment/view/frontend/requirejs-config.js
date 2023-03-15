var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Mageget_OfflinePayment/js/order/place-order-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Mageget_OfflinePayment/js/order/set-payment-information-mixin': true
            }
        }
    }
};