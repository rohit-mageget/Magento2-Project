<?php 
namespace Mageget\Customproductpage\Plugin\Product\Type;

class ConfigurablePlugin
{
    public function afterGetUsedProductCollection(\Magento\ConfigurableProduct\Model\Product\Type\Configurable $subject, $result)
   {
       $result->addAttributeToSelect('description');
       return $result;
   }
}