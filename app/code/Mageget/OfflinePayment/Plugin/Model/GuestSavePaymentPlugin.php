<?php

namespace Mageget\OfflinePayment\Plugin\Model;

use Magento\Checkout\Model\PaymentInformationManagement;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

class GuestSavePaymentPlugin
{
    protected $quoteRepository;

    public function beforeSavePaymentInformationAndPlaceOrder(PaymentInformationManagement $subject,
                                                              $cartId, $email, PaymentInterface $paymentMethod,
                                                              AddressInterface $billingAddress = null)
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        $quoteId = $quoteIdMask->getQuoteId();
        $orderCustom = $paymentMethod->getExtensionAttributes();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $quoteRepository = $objectManager->create('Magento\Quote\Api\CartRepositoryInterface');

        $quote = $quoteRepository->get($quoteId);
        $custom = $quote->getCompanyName();
        $quote->setCompanyName($custom);
        $quote->save();
    }
}