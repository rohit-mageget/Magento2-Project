<?php
namespace Mageget\StoreCredit\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Authorization\Model\UserContextInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

class CustomerDataPost implements ObserverInterface
{
    protected $request;
    protected $customerRepository;
    protected $gridFactory;
    protected $userContext;
    protected $userCollectionFactory;


    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Mageget\StoreCredit\Model\GridFactory $gridFactory,
        UserContextInterface $userContext,
        UserCollectionFactory $userCollectionFactory, 
        CustomerRepositoryInterface $customerRepository

    )
    {
        $this->request = $request;
        $this->gridFactory = $gridFactory;
        $this->userContext = $userContext;
        $this->userCollectionFactory = $userCollectionFactory;
        $this->customerRepository = $customerRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $customerId = $customer->getId();
        $gridData = $this->gridFactory->create();

        $admincollection = $this->userCollectionFactory->create();
        $userId = $this->userContext->getUserId();
        $admincollection->addFieldToFilter('user_id', $userId);
        foreach($admincollection as $data){
            if($data['user_id'] == $userId){
                $admin_user = $data['username'];
                break;
            } 
        }

        // $admin_user = $admincollection->getUser();
        // echo "<pre>";
        // print_r($admin_user);
        // print_r($userId);
        // die("  hlo dear");



      
        $attr = $customer->getCustomAttribute('store_credit');
        if($attr->getValue() !== 0 && !empty($attr->getValue())){

            $value = $attr->getValue();
    
        }else{
            $value = 0;
     
        }
 
        $storecredit = $this->request->getPost('store_credit');

        $finalAmount = ($value + $storecredit);

        $customer->setCustomAttribute('store_credit', $finalAmount);
        $action = "Credited by ".$admin_user;

        $gridData->setCustomerId($customerId);
        $gridData->setCreditChange(("+".$storecredit));
        $gridData->setCurrentCredit($finalAmount);
        $gridData->setAction($action);
        $gridData->save();

        $this->customerRepository->save($customer);

        return $this;

    }
}