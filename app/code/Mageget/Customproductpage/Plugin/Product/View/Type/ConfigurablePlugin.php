<?php 
namespace Mageget\Customproductpage\Plugin\Product\View\Type; 
class ConfigurablePlugin { 
     protected $jsonEncoder; 
     protected $jsonDecoder; 

     public function __construct( 
        \Magento\Framework\Json\DecoderInterface $jsonDecoder, 
        \Magento\Framework\Json\EncoderInterface $jsonEncoder 
    ){ 
        $this->jsonEncoder = $jsonEncoder;
        $this->jsonDecoder = $jsonDecoder;
    }

    public function afterGetJsonConfig(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject, $result)
    {
        $result = $this->jsonDecoder->decode($result);
        $currentProduct = $subject->getProduct();

        // echo "<pre>";
        // print_r($result);
       

    //     if ($currentProduct->getName()) {
    //         $result['productName'] = $currentProduct->getName();
    //     }

    //     if ($currentProduct->getSku()) {
    //         $result['productSku'] = $currentProduct->getSku();
    //    }

    //    if ($currentProduct->getDescription()) {
    //         $result['productDescription'] = $currentProduct->getDescription();
    //     }

        foreach ($subject->getAllowProducts() as $product) {
            $result['names'][$product->getId()] = $product->getName();
            $result['skus'][$product->getId()] = $product->getSku();
            $result['desc'][$product->getId()] = $product->getDescription();
        }
        return $this->jsonEncoder->encode($result);
    }
}