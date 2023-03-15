define(['jquery',
         'uiComponent',
         'ko',
         'Magento_Customer/js/model/customer',
         'Magento_Checkout/js/model/quote',
         'Magento_Checkout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/error-processor',
        'Magento_SalesRule/js/model/payment/discount-messages',
        'mage/storage',
        'mage/translate',
        'Magento_Checkout/js/action/get-payment-information',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/recollect-shipping-rates'
], function ($, Component, ko, customer, quote, urlManager, errorProcessor, messageContainer, storage, $t, getPaymentInformationAction,
    totals, fullScreenLoader, recollectShippingRates) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Mageget_StoreCredit/knockout-test'
        },
        isCustomerLoggedIn: customer.isLoggedIn,
        initialize: function () {
            this._super();
        },
        getValue : function() {
            var para = $(".page-header .header.panel .header-forms span span#creditprice").html();
            var val = "Store Credit Balance :: " + para + ".00";
            return val;
     },
     getCss : function() {
        $("strong#block-discount-heading").css("color", "#006bb4");
},

getClick : function(){

$(".content form#discount-credit-form, strong#block-discount-heading").toggleClass('accordion');

},

        applyCredit: function () {
            fullScreenLoader.startLoader();
            var form = $("#discount-credit-form").get(0);
                var data = new FormData(form);

                var inputprice = $(".checkout-index-index form#discount-credit-form.accordion input#storecredit").val();
                var creditvalue = $(".page-header .header.panel .header-forms #creditvalue").val();
                var totalcreditvalue = parseInt(creditvalue) - parseInt(inputprice);
                data.append('apply', 'apply');
                 $.ajax({
                      url: 'http://mage.magento.com/storecredit/cart/couponpost',
                      type: "POST",
                      data: data,
                      processData: false,
                      contentType: false,
                      showLoader: true,
                      message: "Store Credit Added Successfully",
                      success: function (response) {
                        //   alert(response.message);
                                var message = "Store Credit Added Successfully";
                                var deferred;
                                $(".page-header .header.panel .header-forms span span#creditprice").html(totalcreditvalue);
                                var storepriceval = "Store Credit Balance :: " + totalcreditvalue + ".00";
                                $(".checkout-index-index .component-wrapper.store-credit strong#block-discount-heading").html(storepriceval);
                                $(".page-header .header.panel .header-forms #creditvalue").val(totalcreditvalue);
                                $(".checkout-index-index form#discount-credit-form.accordion input#storecredit").prop('disabled', true);
                                    deferred = $.Deferred();
        
                                    totals.isLoading(true);
                                  
                                    recollectShippingRates();
                                    getPaymentInformationAction(deferred);
                                    $.when(deferred).done(function () {
                                        fullScreenLoader.stopLoader();
                                        totals.isLoading(false);
                                    });
                                    messageContainer.addSuccessMessage({
                                        'message': message
                                    });
                      },
                     error: function (response) {
                        //   alert(response.message);
                          fullScreenLoader.stopLoader();
                                totals.isLoading(false);
                                errorProcessor.process(response, messageContainer);
                     }
                  }); 
        }, 
        cancelCredit: function () {
            
            fullScreenLoader.startLoader();
            var form = $("#discount-credit-form").get(0);
                var data = new FormData(form);
                var inputprice = $(".checkout-index-index form#discount-credit-form.accordion input#storecredit").val();
                var creditvalue = $(".page-header .header.panel .header-forms #creditvalue").val();
                var totalcreditvalue = parseInt(creditvalue) + parseInt(inputprice);
                data.append('cancel', 'cancel');
                 $.ajax({
                      url: 'http://mage.magento.com/storecredit/cart/couponpost',
                      type: "POST",
                      data: data,
                      processData: false,
                      contentType: false,
                      showLoader: true,
                      message: "Store Credit Clear Successfully",
                      success: function (response) {
                        //   alert(response.message);
                        //   fullScreenLoader.stopLoader();
                          var deferred;
        
                          $(".page-header .header.panel .header-forms span span#creditprice").html(totalcreditvalue);
                          var storepriceval = "Store Credit Balance :: " + totalcreditvalue + ".00";
                          $(".checkout-index-index .component-wrapper.store-credit strong#block-discount-heading").html(storepriceval);
                          $(".page-header .header.panel .header-forms #creditvalue").val(totalcreditvalue);
                          $(".checkout-index-index form#discount-credit-form.accordion input#storecredit").prop('disabled', false);

                            deferred = $.Deferred();

                            totals.isLoading(true);
                            
                            recollectShippingRates();
                            getPaymentInformationAction(deferred);
                            $.when(deferred).done(function () {
                                fullScreenLoader.stopLoader();
                                totals.isLoading(false);
                            });
                            // messageContainer.addSuccessMessage({
                            //     'message': message
                            // });
                      },
                     error: function (response) {
                          alert(response.message);
                        //   fullScreenLoader.stopLoader();
                          fullScreenLoader.stopLoader();
                          totals.isLoading(false);
                        //   errorProcessor.process(response, messageContainer);
                     }
                  }); 
        }

    });
    
}
);
