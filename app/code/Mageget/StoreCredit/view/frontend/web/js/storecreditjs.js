require([
    'jquery'
], function ($) {

        $(document).ready(function() {
                    setTimeout(function() {
                        $(".checkout-cart-index .discount-credit-form-container strong#block-discount-heading, .checkout-cart-index .discount-credit-form-container strong#block-discount-heading:after").click(function(){
                            $(".checkout-cart-index .discount-credit-form-container .discount-credit-form-content form#discount-credit-form, .checkout-cart-index .discount-credit-form-container strong#block-discount-heading").toggleClass('accordion');
                        });
                    }, 2000);
                });
    });