<?php

namespace Dww\Quote\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\SerializerInterface;

class CheckoutCartAdd implements ObserverInterface
{
    protected $layout;
    protected $storeManager;
    protected $request;
    private $serializer;

    public function __construct(
        StoreManagerInterface $storeManager,
        LayoutInterface       $layout,
        RequestInterface      $request,
        \Magento\Framework\Serialize\Serializer\Json $json,
        SerializerInterface   $serializer
    )
    {
        $this->layout = $layout;
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->json = $json;
        $this->serializer = $serializer;
    }

    public function execute(EventObserver $observer)
    {
        $item = $observer->getQuoteItem();
        $post = $this->request->getPost();
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
      

        $customedata = array();
        if ($additionalOption = $item->getOptionByCode('additional_options')) {
            $customedata = $this->serializer->unserialize($additionalOption->getValue());
        }
        $part= '{"face": "#FFF333", "Accent": "#f00","Deepak": "#46F300","Mageget": "#0007F3","software": "#DE3163","pvt": "#FF7F50","rohit": "#40E0D0","rohit1": "#008000","rohit2": "#008080","rohit3": "#000000"}';
        $color = "";
        $jsonDecode = $this->json->unserialize($part);
        // foreach ($jsonDecode as $data => $value) {
        //     $color .= "<dd>".$data." : ";
        //     $color.="<dd style='background:$value;width:40px;height:20px;'></dd></dd>";
        // }


        $customedata[] = [
            'label' => 'Customized Parts',
            'value' => $part,
        ];

        if (count($customedata) > 0) {
            $item->addOption(array(
                'product_id' => $item->getProductId(),
                'code' => 'additional_options',
                'value' => $this->serializer->serialize($customedata)
            ));
        }
    }
}