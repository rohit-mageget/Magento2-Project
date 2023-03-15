<?php
namespace Mageget\Checkout\Plugin\Checkout\Model;
 
use Magento\Quote\Model\QuoteRepository;
 
class ShippingInformationManagement
{
    protected $quoteRepository;
 
    public function __construct(QuoteRepository $quoteRepository) {
        $this->quoteRepository = $quoteRepository;
    }
 
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        if(!$extAttributes = $addressInformation->getExtensionAttributes())
        {
            return;
        }
        // var_dump($extAttributes);exit;
        $quote = $this->quoteRepository->getActive($cartId);
 
        $quote->setComment($extAttributes->getComment());
        $quote->setImage($extAttributes->getImage());
        $this->quoteRepository->save($quote);
    }
}