<?php
namespace Mageget\StoreCredit\Block;

class Storecredit extends \Magento\Framework\View\Element\Template
{
        protected $_storeManager;
        protected $_urlInterface;
        public $_customerSession;
        protected $_helper;
        protected $customerRepository;
 
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Customer\Model\Session $customerSession, 
        \Mageget\StoreCredit\Helper\Data $helper, 
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        array $data = []
    )
    {        
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        $this->_customerSession = $customerSession;
        $this->_helper = $helper;
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $data);
    }

    public function getCustomerId()
    {
         if($this->_customerSession->isLoggedIn()){
            return $this->_customerSession->getCustomer()->getId();
         }                        
    }

    public function getCustomUrl(){
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }

    public function getStoreCreditBalance()
    {
        $customerId = $this->_customerSession->getCustomer()->getId();
        $customer = $this->customerRepository->getById($customerId);
        if(null !== $customer->getCustomAttribute('store_credit'))
            {
                $attr = $customer->getCustomAttribute('store_credit');
                $value = $attr->getValue();
                return $value;
            }
        // $attr = $customer->getCustomAttribute('store_credit');
        // $value = $attr->getValue();
        // return $value;
    }
}
?>