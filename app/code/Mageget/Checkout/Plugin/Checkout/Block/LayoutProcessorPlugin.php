<?php
namespace Mageget\Checkout\Plugin\Checkout\Block;
 
class LayoutProcessorPlugin
{
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
  
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['comment'] = [
            'component' => 'Magento_Ui/js/form/element/textarea',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'options' => [],
                'id' => 'comment'
            ],
            'dataScope' => 'shippingAddress.custom_attributes.comment',
            'label' => 'Comment',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => true
            ],
            'sortOrder' => 250,
            /*'customEntry' => null,*/
            'id' => 'comment'
        ];
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['image'] = [
            'component' => 'Mageget_Checkout/js/file-uploader',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'Mageget_Checkout/form/element/newtempfile',
                'options' => [],
                'id' => 'image'
            ],
            'dataScope' => 'shippingAddress.custom_attributes.image',
            'label' => 'image',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => false
            ],
            'sortOrder' => 260,
            /*'customEntry' => null,*/
            'id' => 'image'
        ];
        return $jsLayout;
    }
}

