<?php

namespace Mageget\OfflinePayment\Plugin\Model;

use Magento\Checkout\Model\PaymentInformationManagement;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

class SavePaymentPlugin
{
    protected $quoteRepository;

    public function beforeSavePaymentInformationAndPlaceOrder(PaymentInformationManagement $subject,
                                                              $cartId, PaymentInterface $paymentMethod,
                                                              AddressInterface $billingAddress = null)
    {
        $orderCustom = $paymentMethod->getExtensionAttributes();
        // print_r($orderCustom);
        // die("dfdg");
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $quoteRepository = $objectManager->create('Magento\Quote\Api\CartRepositoryInterface');

        $quote = $quoteRepository->getActive($cartId);
        $custom = $quote->getCompanyName();
        $quote->setCompanyName($custom);
        $quote->save();
    }
}