<?php

namespace Magecomp\Extrafee\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class Options implements ArrayInterface
{

    protected $_productCollectionFactory;

    public function __construct(       
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory

    )
    {  
        $this->_productCollectionFactory = $productCollectionFactory->create();
        $this->_productCollectionFactory->addFieldToSelect('*');

    }

    public function toOptionArray()
    {

        $products = $this->_productCollectionFactory;

        foreach ($products as $product) 
        {
            $this->_options[] = ['label' => $product->getSku(), 'value' => $product->getSku()];
        }

        return $this->_options;
    }
}