<?php
namespace Magecomp\Extrafee\Plugin\Checkout\Model;


class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Magecomp\Extrafee\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Magecomp\Extrafee\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magecomp\Extrafee\Helper\Data $dataHelper
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function afterSaveAddressInformation(

        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        
        $result,
        
        $cartId,
        
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
        
        ){
        
        // $quote = $this->quoteRepository->getActive($cartId);
        
        // $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        // $logger = new \Zend_Log();
        // $logger->addWriter($writer);
        // $logger->info('afterSaveAddressInformation');

        // $rates = $quote->getShippingAddress()->getShippingRatesCollection();
        //     foreach ($rates as $rate)
        //     {
        //        if($rate->getCarrier() == 'ups'){
        //             $carrier = true;
        //        }else{
        //         $carrier = false;
        //        }
        //         // $logger->info($rate->getCarrier());
        //         // $logger->info($rate->getMethodTitle());
               
        //     }
        //     if($carrier){
        //         $fee = NULL;
        //         $quote->setTotalsCollectedFlag(false);
        //         $quote->getShippingAddress()->unsetData('cached_items_all');
        //         $quote->getShippingAddress()->unsetData('cached_items_nominal');
        //         $quote->getShippingAddress()->unsetData('cached_items_nonnominal');
        //         $quote->getShippingAddress()->unsetData('fee');
        //         $quote->getBillingAddress()->unsetData('fee');
        //         $quote->collectTotals();
        //         $quote->setFee($fee);
        //         $quote->getShippingAddress()->setData('fee', $fee);
        //         $quote->getBillingAddress()->setData('fee', $fee);
        //         $quote->save();
        
        //         $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        //         $logger = new \Zend_Log();
        //         $logger->addWriter($writer);
        //         $logger->info('beforeSaveAddressInformation');
        //         $logger->info($quote->getFee());
        //         $logger->info($carrier);
        
        //         }
        //         else{
                    
        //             $fees = $this->dataHelper->getExtrafee();
        //             $getQuoteItemsQty = $quote->getItemsQty();
        //             $fee = ((int)$fees*(int)$getQuoteItemsQty);
        //             $quote->setTotalsCollectedFlag(false);
        //             $quote->getShippingAddress()->unsetData('cached_items_all');
        //             $quote->getShippingAddress()->unsetData('cached_items_nominal');
        //             $quote->getShippingAddress()->unsetData('cached_items_nonnominal');
        //             $quote->getShippingAddress()->unsetData('fee');
        //             $quote->getBillingAddress()->unsetData('fee');
        //             $quote->collectTotals();
        //             $quote->setFee($fee);
        //             $quote->getShippingAddress()->setData('fee', $fee);
        //             $quote->getBillingAddress()->setData('fee', $fee);
        //             $quote->save();
            
        //             $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        //             $logger = new \Zend_Log();
        //             $logger->addWriter($writer);
        //             $logger->info('beforeSaveAddressInformation');
        //             $logger->info($quote->getFee());
        //             $logger->info($carrier);
        
        //         }

        $Extrafee = $addressInformation->getExtensionAttributes()->getFee();
        $quote = $this->quoteRepository->getActive($cartId);

        if ($Extrafee) {
                $getProductFee = $this->helperData->getProductFee();
                $getProductFeeAmount = $this->helperData->getProductFeeAmount();
                $defaultFee = $this->helperData->getExtrafee();
                $getProductFee ? $fee = $getProductFeeAmount : $fee = $defaultFee;
            $quote->setFee($fee);
        } else {
            $quote->setFee(NULL);
        }
        
        return $result;
        
        }
   
}



