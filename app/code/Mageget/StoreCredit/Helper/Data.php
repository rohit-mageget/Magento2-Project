<?php
namespace Mageget\StoreCredit\Helper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;
class Data extends AbstractHelper
{
    const XML_CONFIG_ENABLE = 'StoreCredit/general/enable';
    const XML_CONFIG_CREDITTAX = 'StoreCredit/general/credittax';
    const XML_CONFIG_ENABLE_CREDIT_CHECKOUT = 'StoreCredit/display/enable_credit_checkout';
    const XML_CONFIG_ENABLE_CREDIT_CART = 'StoreCredit/display/enable_credit_cart';
    const XML_CONFIG_SHOW_CREDIT_BALANCE = 'StoreCredit/display/show_credit_balance';
    const XML_CONFIG_HIDE_CREDIT_BALANCE = 'StoreCredit/display/hide_credit_balance';
    const XML_CONFIG_ADD_CREDIT_BALANCE = 'StoreCreditInCustomer/store_credit_balance/add_credit_balance';

    public function getIsEnable()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_ENABLE,ScopeInterface::SCOPE_STORE); // For Store
       
         return $configValue;
    }
    public function getCreditTax()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_CREDITTAX,ScopeInterface::SCOPE_STORE); // For Store
       
         return $configValue;
    }
    public function getEnableCreditCheckout()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_ENABLE_CREDIT_CHECKOUT,ScopeInterface::SCOPE_STORE); // For Store
       
         return $configValue;
    }
    public function getEnableCreditCart()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_ENABLE_CREDIT_CART,ScopeInterface::SCOPE_STORE); // For Store
       
         return $configValue;
    }
    public function getShowCreditBalance()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_SHOW_CREDIT_BALANCE,ScopeInterface::SCOPE_STORE); // For Store
       
         return $configValue;
    }
    public function getHideCreditBalance()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_HIDE_CREDIT_BALANCE,ScopeInterface::SCOPE_STORE); // For Store
       
         return $configValue;
    }
    public function getCreditBalance()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_ADD_CREDIT_BALANCE,ScopeInterface::SCOPE_STORE); // For Store
       
         return $configValue;
    }
    
}