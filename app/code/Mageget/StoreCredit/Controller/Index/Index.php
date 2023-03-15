<?php
namespace Mageget\StoreCredit\Controller\Index;

use Magento\Authorization\Model\UserContextInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory as BestSellersCollectionFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_helper;
    protected $_customerSession; 
    protected $_productVisibility;
    protected $userContext;
    protected $userCollectionFactory;
    protected $authSession;
    protected $_bestSellersCollectionFactory;
    protected $_productCollectionFactory;
    

    /**
     * @var \Magento\Reports\Model\Product\Index\Factory
     */
    protected $_indexFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Mageget\StoreCredit\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        UserContextInterface $userContext,
        UserCollectionFactory $userCollectionFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        CollectionFactory $productCollectionFactory,
        BestSellersCollectionFactory $bestSellersCollectionFactory, 
        \Magento\Reports\Model\Product\Index\FactoryFactory $indexFactory
        )
	{
		$this->_pageFactory = $pageFactory;
		$this->_helper = $helper;
        $this->_customerSession = $customerSession;
        $this->_productVisibility = $productVisibility;
        $this->_indexFactory = $indexFactory;
        $this->userContext = $userContext;
        $this->authSession = $authSession;
        $this->userCollectionFactory = $userCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_bestSellersCollectionFactory = $bestSellersCollectionFactory;
		return parent::__construct($context);
	}

	public function execute(){


        $productIds = [];
        $bestSellers = $this->_bestSellersCollectionFactory->create()
            ->setPeriod('month');
        foreach ($bestSellers as $product) {
            // $product->getProductId();
            print_r($product->getData());
        }
        die("bestseller");
        // $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
        // $collection->addMinimalPrice()
        //     ->addFinalPrice()
        //     ->addTaxPercents()
        //     ->addAttributeToSelect('*')
        //     ->addStoreFilter($this->getStoreId())->setPageSize($this->getProductsCount());


    //     $admincollection = $this->userCollectionFactory->create();
    //     $userId = $this->userContext->getUserId();
    //     $admincollection->addFieldToFilter('user_id', 1);
    //     foreach($admincollection as $data){
    //         if($data['user_id'] == 1){
    //             $admin_user = $data['username'];
    //             break;
    //         } 
    //     }
    // echo "<pre>";
    // print_r($admin_user);
    // die("  hlo dear");

    //     $data = $this->_helper->getCreditBalance();
    //     $customerId = $this->getCustomerId();
    //     print_r($this->_customerSession->getCustomer()->getName());
        


        // $message = "";
        // $error = false;
        // try{

        //     $message = "File has been successfully uploaded";
        //     $error = false;
        // } catch (\Exception $e) {
        //     $error = true;
        //     $message = $e->getMessage();
        // }
        return $this->_pageFactory->create();
    }
    public function getCustomerId()
                    {
                         if($this->_customerSession->isLoggedIn()):
                              return $this->_customerSession->getCustomer()->getId();                        
                         endif;
                    }
}
