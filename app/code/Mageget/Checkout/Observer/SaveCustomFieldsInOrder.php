<?php
namespace Mageget\Checkout\Observer;
 
class SaveCustomFieldsInOrder implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        $order->setData('comment', $quote->getComment());
        $order->setData('image', $quote->getImage());
        $order->save($order);
        return $this;
    }
}