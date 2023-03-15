<?php

namespace Mageget\OfflinePayment\Observer\Frontend\Sales;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\OfflinePayments\Model\Purchaseorder;
use Magento\Framework\App\Request\DataPersistorInterface;

class OrderPaymentSaveBefore implements \Magento\Framework\Event\ObserverInterface
{
    protected $order;
    protected $logger;
    protected $_serialize;
    protected $quoteRepository;

    public function __construct(
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Serialize\Serializer\Serialize $serialize
    ) {
        $this->order = $order;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->_serialize = $serialize;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderids = $observer->getEvent()->getOrderIds();
        $payment = $observer->getEvent()->getPayment();
        // $data = $observer->getEvent()->getData('company_name');
        
            print_r($orderids);
            print_r($payment);
            die('rohit');
        if($orderids){
            
            foreach ($orderids as $orderid) {
                $order = $this->order->load($orderid);
                $method = $order->getPayment()->getMethod();
                if($method == 'simple') {
                    $quote_id = $order->getQuoteId();
                    $quote = $this->quoteRepository->get($quote_id);
                    $paymentQuote = $quote->getPayment();
                    $paymentOrder = $order->getPayment();
                    // echo "<pre>";
                    // print_r($paymentOrder);
                    // print_r($method);
                    // die('kjhjkd');
                    print_r($paymentQuote->getCompanyName());
                    // print_r($method);
                    // die("esfef");
                    $paymentOrder->setData('company_name',$paymentQuote->getCompanyName());
                    $paymentOrder->setAdditionalInformation('company_name', $paymentQuote->getCompanyName());
                    // $paymentOrder->setData('bank_name',$paymentQuote->getBankName());
                    // $paymentOrder->setData('cheque_number',$paymentQuote->getChequeNumber());
                    // $paymentOrder->setData('gst_number',$paymentQuote->getGstNumber());
                    $paymentOrder->save();
                }
            }
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('text message 2');
            $logger->info(print_r($paymentQuote));
        }
    }
}