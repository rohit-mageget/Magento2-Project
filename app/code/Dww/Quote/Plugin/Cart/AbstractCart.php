<?php
namespace Dww\Quote\Plugin\Cart;
class AbstractCart
{
    /*
    *   Override cart/item/default.phtml file
    *   \Magento\Checkout\Block\Cart\AbstractCart $subject
    *   $result
    */
    public function afterGetItemRenderer(\Magento\Checkout\Block\Cart\AbstractCart $subject, $result)
    {
        $result->setTemplate('Dww_Quote::cart/item/default.phtml');
        return $result;
    }
} ?>