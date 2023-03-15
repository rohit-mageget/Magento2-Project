<?php
declare(strict_types=1);
namespace Mageget\OfflinePayment\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
//use Dww\Rewards\Helper\Data;
class OrderData  implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
      
        $order = $observer->getEvent()->getPayment()->getMethod();

        $logger->info('text message df');
        $logger->info(print_r($order, true));
       
    }

}