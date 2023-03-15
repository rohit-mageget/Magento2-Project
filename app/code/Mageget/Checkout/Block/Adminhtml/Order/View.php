<?php

namespace Mageget\Checkout\Block\Adminhtml\Order;
class View extends \Magento\Backend\Block\Template
{
    
    public function getCustomBlock()
    {
        // $this->request->getParams();
        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // $order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($orderId);
        // return $this->request->getParams('order_id');
        return "Customer Information";
    }
}