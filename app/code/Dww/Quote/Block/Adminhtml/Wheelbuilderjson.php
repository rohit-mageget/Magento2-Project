<?php

namespace Dww\Quote\Block\Adminhtml;

use Magento\Sales\Model\Order;
use Magento\Sales\Api\Data\OrderInterface;

class Wheelbuilderjson extends \Magento\Backend\Block\Template
{
    protected $order;
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        OrderInterface $orderInterface,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Sales\Model\Order $order,
        array $data = []
    ) {
        $this->orderInterface = $orderInterface;
        $this->order = $order;
        $this->json = $json;
        parent::__construct($context, $data);
    }
    public function getOrderDeatils()
    {
    
      $orderId = $this->getRequest()->getParam("order_id");

        $order = $this->order->load((int)$orderId);
        // echo "<pre>";
        // print_r($order->getData());
        // die("mageget");
        foreach ($order->getItems() as $item) {

            $product_options = $item["product_options"]['additional_options'];
            if(isset($product_options) && !empty($product_options)){
                return $product_options;
            }
           
        }
        return $part= "";
       
    }
}
