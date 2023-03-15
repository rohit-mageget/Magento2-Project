<?php
namespace Mageget\StoreCredit\Observer\Data;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class OrderdataObserver implements ObserverInterface
{
    protected $customerRepository;
    protected $gridFactory;
    protected $_customerSession; 
    protected $_checkoutsession; 
    protected $quoteFactory; 


    public function __construct(
        \Mageget\StoreCredit\Model\GridFactory $gridFactory,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Checkout\Model\Session $checkoutsession
    )
    {
        $this->gridFactory = $gridFactory;
        $this->_customerSession = $customerSession;
        $this->_checkoutsession = $checkoutsession;
        $this->customerRepository = $customerRepository;
        $this->quoteFactory = $quoteFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {   

        $order = $observer->getEvent()->getOrder();
        // $quote = $order->getQuoteId();
        $incid = $order->getIncrementId();

        // $quoteId = $this->_checkoutsession->getQuote()->getId();
        $quoteId = $order->getQuoteId();
        $quoteData = $this->quoteFactory->create()->load($quoteId);
        $cchange = $quoteData->getStorecredit();

        // print_r($quoteId);
        // print_r($cchange);
        // die("order no.");

        // $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/test.log');
        // $logger = new \Zend_Log();
        // $logger->addWriter($writer);
        // $logger->info('Your text message');
        // $logger->info(print_r($cchange));
        // $logger->info(print_r($quoteId));

        // $enid = $order->getId();
        // die("place order");
        isset($cchange) ? $cchange : ($cchange = 0);
        $customerId = $this->_customerSession->getCustomer()->getId();
        $customer = $this->customerRepository->getById($customerId);

        if(null !== $customer->getCustomAttribute('store_credit')){

        $attr = $customer->getCustomAttribute('store_credit');
        $value = $attr->getValue();
        }
        $order->setData('storecredit', $cchange);
        $order->save();
        
        $gridData = $this->gridFactory->create();
        // // $gridData->addFieldToFilter('customer_id', $customerId);
        // // print_r($gridData->getData());
        // // die("hlo");
        // // $action = "Credited by admin";
        $gridData->setCustomerId($customerId);
        $gridData->setCreditChange(("-".$cchange));
        $gridData->setAction($incid);
        $gridData->setCurrentCredit($value);
        $gridData->save();

        // $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/test.log');
        // $logger = new \Zend_Log();
        // $logger->addWriter($writer);
        // $logger->info('Your text message');
        // $logger->info(print_r($incid));
        // $logger->info(print_r($customerId));
        // return $this;
    }
}
