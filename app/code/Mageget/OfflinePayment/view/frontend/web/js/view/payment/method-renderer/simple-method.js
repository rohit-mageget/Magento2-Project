define([
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'mage/validation'
], function (Component, $) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mageget_OfflinePayment/payment/simple',
            // bankName: '',
            // chequeNumber: '',
            // gstNumber: '',
        },

        initObservable: function () {
            this._super()
                .observe('companyName');
            return this;
        },
        getData: function () {
            return {
                "method": this.item.method,
                "additional_data": {
                    "company_name": this.companyName(),
                }
            };

        },

        // /** @inheritdoc */
        // initObservable: function () {
        //     // this._super().observe('bankName');
        //     // this._super().observe('chequeNumber');
        //     // this._super().observe('gstNumber');
        //     this._super().observe('companyName');

        //     return this;
        // },

        // /**
        //  * @return {Object}
        //  */
        // getData: function () {
        //     return {
        //         method: this.item.method,
        //         // 'bank_name': this.bankName(),
        //         // 'cheque_number': this.chequeNumber(),
        //         // 'gst_number': this.gstNumber(),
        //         'company_name': this.companyName(),
        //         'additional_data': null
        //     };
        // },

        /**
         * @return {jQuery}
         */
        validate: function () {
            var form = 'form[data-role=purchaseorder-form]';

            return $(form).validation() && $(form).validation('isValid');
        }
    });
});



// define(
//     [
//         'Magento_Checkout/js/view/payment/default'
//     ],
//     function (Component) {
//         'use strict';
//         return Component.extend({
//             defaults: {
//                 template: 'Mageget_OfflinePayment/payment/simple'
//             },
//             getBankName: function () {
//                 var text = "Bank Name : ";
//                 return text + window.checkoutConfig.payment.bank_name[this.item.method];
//             },
//             getChequeNumber: function () {
//                 var text = "Cheque Number : ";
//                 return text + window.checkoutConfig.payment.cheque_number[this.item.method];
//             },
//             getGstNumber: function () {
//                 var text = "Gst Number : ";
//                 return text + window.checkoutConfig.payment.gst_number[this.item.method];
//             },
//             getCompanyName: function () {
//                 var text = "Company Name : ";
//                 return text + window.checkoutConfig.payment.company_name[this.item.method];
//             },
//         });
//     }
// );
