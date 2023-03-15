<?php
namespace Webkul\Bestseller\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class ProductSlidersManagement implements \Webkul\Bestseller\Api\ProductSlidersManagementInterface
{
    const PAGINATION = 10;
    const CURRENTPAGE = 1;

    protected $dataObjectFactory;    
    protected $_collectionFactory;
    protected $storeManager;
    protected $productRepository;
    protected $storeManagerInterface;

    public function __construct(
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManagerInterface
    ) {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->storeManagerInterface = $storeManagerInterface;
    }

    public function getBestseller($currentPage = 1, $pageSize = 10) 
    {
        $result = $this->dataObjectFactory->create();
        if($pageSize == 0) {
            $pageSize = self::PAGINATION;
        }
        if($currentPage == 0) {
            $currentPage = self::CURRENTPAGE;
        }

        $storeId = $this->storeManager->getStore()->getId();
        $mediaUrl = $this->storeManagerInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $bestSellerProdcutCollection = $this->_collectionFactory->create()
        ->setModel('Magento\Catalog\Model\Product')
        ->setPeriod('month')
        ->setPageSize(
            $pageSize
        )->setCurPage(
            $currentPage
        ); //you can add period daily,yearly
        //->setPeriod('year');
        //->setPeriod('day');

        $productData = [];
        $i = 0;
        $productCount = count($bestSellerProdcutCollection);
        foreach ($bestSellerProdcutCollection as $value) {
            $productId = $value->getProductId();
            $product = $this->productRepository->getById($productId);

            $productData[$i]['product_id'] = $product->getEntityId();
            $productData[$i]['name'] = $product->getName();
            $productData[$i]['price'] = $product->getPrice();
            $productData[$i]['sku'] = $product->getSku();  
            $productData[$i]['image'] = ($product->getImage())?$mediaUrl.'catalog/product'.$product->getImage():'';
            $i++;   
        }
        $result->setData('bestseller_product', $productData);
        $result->setData('product_count', $productCount);
        return $result;
    }
    
}