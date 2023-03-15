<?php
namespace Mageget\StoreCredit\Controller\Customer; 

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_helper;
    protected $_customerSession; 

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Mageget\StoreCredit\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession
        )
	{
		$this->_pageFactory = $pageFactory;
		$this->_helper = $helper;
        $this->_customerSession = $customerSession;
		return parent::__construct($context);
	}

	public function execute(){

    $this->_view->loadLayout(); 
    // $this->_view->renderLayout();


        // $data = $this->_helper->getCreditBalance();
        // $customerId = $this->getCustomerId();
        // print_r($this->_customerSession->getCustomer()->getName());
        // die("  hlo dear");


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

