<?php
namespace Mageget\StoreCredit\Controller\Cart;

use Magento\Customer\Api\CustomerRepositoryInterface;

class Couponpost extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_helper;
    protected $_customerSession; 
    protected $customerRepository;
    protected $_checkoutSession;
    protected $cart;
    protected $quoteRepository;
    protected $_quoteFactory;
    protected $resultJsonFactory;
    protected $gridFactory;
    protected $total;


	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Mageget\StoreCredit\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, 
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Quote\Model\Quote\Address\Total $total,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Mageget\StoreCredit\Model\GridFactory $gridFactory
        )
	{
		$this->_pageFactory = $pageFactory;
		$this->_helper = $helper;
        $this->_customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->_checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->quoteRepository = $quoteRepository;
        $this->_quoteFactory = $quoteFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->total = $total;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
        $this->gridFactory = $gridFactory;
		return parent::__construct($context);
	}

	public function execute(){

        $gridData = $this->gridFactory->create();

        $resultRedirect = $this->resultRedirectFactory->create();
        
        $quoteId = $this->_checkoutSession->getQuote()->getId();
        $quoteData = $this->_quoteFactory->create()->load($quoteId);

        $preAddedDiscount = $quoteData->getStorecredit();

        $cartQuote = $this->cart->getQuote();

        $storeCredit = $this->getStoreCreditBalance();

        $formData = $this->getRequest()->getPost('storecredit');
        $apply = $this->getRequest()->getPost('apply');
        $cancel = $this->getRequest()->getPost('cancel');

        // $order = $this->_checkoutSession->getLastRealOrderId();
        // $orderId=$order->getEntityId();
        // $incId = $order->getIncrementId();
        // $quote = $observer->getEvent()->getQuote();
            // $incrementId = $quoteData->getReservedOrderId();

        // print_r($quoteData);
        // // print_r($order);
        // die("rgdg");

        if(isset($storeCredit)){

            $grandTotal =$quoteData->getSubtotal();
        if($apply == 'apply'){

            $DiscountData = ($formData + $preAddedDiscount);

            if($DiscountData <= $storeCredit && $DiscountData <= $grandTotal){

                $quoteData->setData('storecredit', $DiscountData);
                $quoteData->save();
                $storeprice = ($storeCredit - $formData);
    
                $customerId = $this->_customerSession->getCustomer()->getId();
                $customer = $this->customerRepository->getById($customerId);
                $customer->setCustomAttribute('store_credit', $storeprice);
                $this->customerRepository->save($customer);

                // $order = $this->_checkoutSession->getLastRealOrder();
                // $incId = $order->getIncrementId();
                // $incId = "Used credit by user";
                // $gridData->setCustomerId($customerId);
                // $gridData->setCreditChange(("-".$DiscountData));
                // $gridData->setCurrentCredit($storeprice);
                // $gridData->setAction($incId);
                // $gridData->save();
    
                $message = __('Total Price has been updated successfully'); 
                $this->messageManager->addSuccessMessage($message);
               
                return $resultRedirect->setPath('checkout/index/index');
    
    
            }else{
    
                    $message = __('Please enter valid amount'); 
                    $this->messageManager->addErrorMessage($message);
                return $resultRedirect->setPath('checkout/index/index');
    
            }

        }elseif($cancel == 'cancel'){


            if($preAddedDiscount){


                $storeprice = ($storeCredit + $preAddedDiscount);
                $customerId = $this->_customerSession->getCustomer()->getId();
                $customer = $this->customerRepository->getById($customerId);
                $customer->setCustomAttribute('store_credit', $storeprice);
                $this->customerRepository->save($customer);
    
    
                $preAddedDiscount = 0;
    
                $quoteData->setData('storecredit', $preAddedDiscount);
                $quoteData->save();
                // $incId = "Cancel applyed credit by user";
                // $gridData->setCustomerId($customerId);
                // $gridData->setCreditChange(("-"));
                // $gridData->setCurrentCredit($storeprice);
                // $gridData->setAction($incId);
                // $gridData->save();
    
                $message = __('Credit Price has been cleared successfully'); 
                $this->messageManager->addSuccessMessage($message);
               
                return $resultRedirect->setPath('checkout/index/index');
    
            }else{
    
                    $message = __('There is no StoreCredit discount'); 
                    $this->messageManager->addErrorMessage($message);
                return $resultRedirect->setPath('checkout/index/index');
    
            }

        }else{

                $message = __('Error'); 
                $this->messageManager->addErrorMessage($message);
                return $resultRedirect->setPath('checkout/index/index');


        }

        }else{

            $message = __('You have no credit amount'); 
            $this->messageManager->addErrorMessage($message);
            return $resultRedirect->setPath('checkout/cart');
        }


        

        return $this->_pageFactory->create();


    }
    public function getStoreCreditBalance()
    {
        $customerId = $this->_customerSession->getCustomer()->getId();
        $customer = $this->customerRepository->getById($customerId);

        if(null !== $customer->getCustomAttribute('store_credit')){

        $attr = $customer->getCustomAttribute('store_credit');
        $value = $attr->getValue();
        return $value;
        
        }
    }
}