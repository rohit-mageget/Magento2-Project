<?php
namespace Magecomp\Extrafee\Observer;

use Magecomp\Extrafee\Helper\Data;

class AfterAddToCart implements \Magento\Framework\Event\ObserverInterface
{

    protected $helper;

    public function __construct(
        Data $helper
    )
    {
        $this->helper = $helper;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //Your code to run when event is fired.
        $Fee = $this->helper->getExtrafee();
        $quote = $observer->getQuote();
        $quote->setData('fee', $Fee);

        return;
    }
}