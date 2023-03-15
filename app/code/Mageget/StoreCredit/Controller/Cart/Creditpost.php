<?php
namespace Mageget\StoreCredit\Controller\Cart;

use Magento\Customer\Api\CustomerRepositoryInterface;

class Creditpost extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_helper;
    protected $_customerSession; 
    protected $customerRepository;
    protected $checkoutSession;
    protected $cart;
    protected $quoteRepository;
    protected $_quoteFactory;
    protected $resultJsonFactory;
    protected $gridFactory;
    protected $total;
    protected $orderCollectionFactory;


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
        \Mageget\StoreCredit\Model\GridFactory $gridFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
        )
	{
		$this->_pageFactory = $pageFactory;
		$this->_helper = $helper;
        $this->_customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->quoteRepository = $quoteRepository;
        $this->_quoteFactory = $quoteFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->total = $total;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
        $this->gridFactory = $gridFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
		return parent::__construct($context);
	}

	public function execute(){

        $gridData = $this->gridFactory->create();

        $resultRedirect = $this->resultRedirectFactory->create();
        
        $quoteId = $this->checkoutSession->getQuote()->getId();
        $quoteData = $this->_quoteFactory->create()->load($quoteId);

        $preAddedDiscount = $quoteData->getStorecredit();

        $cartQuote = $this->cart->getQuote();

        $storeCredit = $this->getStoreCreditBalance();

        $formData = $this->getRequest()->getPost('storecredit');
        $apply = $this->getRequest()->getPost('apply');
        $cancel = $this->getRequest()->getPost('cancel');

        // $order = $this->cart->getOrder()->getId();
        // $orderId=$order->getEntityId();reserved_order_id
        
        // $customerId = $this->_customerSession->getCustomer()->getId();
        // $customerOrder = $this->orderCollectionFactory->create()->load($quoteId);
        // foreach($customerOrder as $data){
        //     if($data->getQuoteId() == $quoteId){
        //         $incId = $data->getIncrementId();
        //         print_r($data->getQuoteId());
        //         print_r($data->getIncrementId());
        //         print_r($incId);
        //         break;
        //     }else{
        //         $quote = $data->getQuoteId();
        //         print_r($quote);
        //         print_r($quoteId);
        //     // print_r($data->getIncrementId());
        //     echo "<br/>";

        //     }
        // }
        // die("hlo");
        // $customerOrde = $customerOrder->addFieldToFilter('quote_id', $quoteId);
        // $incId = $quoteData->getReservedOrderId();
        // echo "<pre>";
        // echo $incId;
        // print_r($quoteData->getStorecredit());
        // die("rgdg");

            if(isset($storeCredit)){

                $grandTotal =$cartQuote->getSubtotal();
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
        
                        $order = $this->checkoutSession->getLastRealOrder();
                        // $incId = "Used credit by user";
                       
                        // $gridData->setCustomerId($customerId);
                        // $gridData->setCreditChange(("-".$DiscountData));
                        // $gridData->setCurrentCredit($storeprice);
                        // $gridData->setAction($incId);
                        // $gridData->save();
            
                        $message = __('Total Price has been updated successfully'); 
                        $this->messageManager->addSuccessMessage($message);
                       
                        return $resultRedirect->setPath('checkout/cart');
            
            
                    }else{
            
                            $message = __('Please enter valid amount'); 
                            $this->messageManager->addErrorMessage($message);
                        return $resultRedirect->setPath('checkout/cart');
            
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
                        // $gridData->setAction($incId);
                        // $gridData->setCurrentCredit($storeprice);
                        // $gridData->save();
            
                        $message = __('Credit Price has been cleared successfully'); 
                        $this->messageManager->addSuccessMessage($message);
                       
                        return $resultRedirect->setPath('checkout/cart');
            
                    }else{
            
                            $message = __('There is no StoreCredit discount'); 
                            $this->messageManager->addErrorMessage($message);
                        return $resultRedirect->setPath('checkout/cart');
            
                    }
        
                }else{
        
                        $message = __('Error'); 
                        $this->messageManager->addErrorMessage($message);
                        return $resultRedirect->setPath('checkout/cart');
        
        
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