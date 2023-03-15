<?php
namespace Magecomp\Extrafee\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
// use Magecomp\Extrafee\Helper\Data;

class AddFeeToOrderObserver implements ObserverInterface
{

    protected $helper;
    protected $quoteRepository;

    public function __construct(
        \Magecomp\Extrafee\Helper\Data $helper,
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    )
    {
        $this->helper = $helper;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('AddFeeToOrderObserver : ');

        $quote = $observer->getQuote();
        $ExtrafeeFee = $quote->getFee();
        // $ExtrafeeFee = 20;
        $logger->info($ExtrafeeFee);
        if (!$ExtrafeeFee) {
            return $this;
        }
        //Set fee data to order
        $order = $observer->getOrder();
        $order->setData('fee', $ExtrafeeFee);

        // $quoter = $this->quoteRepository->getActive($quote->getId());
        // // $Fee = $this->helper->getExtrafee();
        // $Fee = 1;
        // $logger->info($Fee);
        // $quoter->setFee($Fee);
        // $quoter->getShippingAddress()->setData('fee', $Fee);
        // $quoter->getBillingAddress()->setData('fee', $Fee);
        // // $logger->info($Fee);
        
		return $this;
    }
}
