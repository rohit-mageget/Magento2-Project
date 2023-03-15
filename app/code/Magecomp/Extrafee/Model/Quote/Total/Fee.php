<?php
namespace Magecomp\Extrafee\Model\Quote\Total;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;


class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    protected $helperData;
	protected $_priceCurrency;
	protected $taxHelper;
    private $taxCalculator;
    protected $skuStatus;

    protected $shippingMethod;
    protected $quoteRepository;

    /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    protected $quoteValidator = null;

    public function __construct(\Magento\Quote\Model\QuoteValidator $quoteValidator,
								\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
                                \Magecomp\Extrafee\Helper\Data $helperData,
                                \Magecomp\Extrafee\Helper\Tax $helperTax,
                                \Magento\Tax\Model\Calculation $taxCalculator,
                                \Magento\Quote\Api\Data\ShippingMethodInterface $shippingMethod,
                                \Magento\Quote\Model\QuoteRepository $quoteRepository

    )
    {
        $this->quoteValidator = $quoteValidator;
		$this->_priceCurrency = $priceCurrency;
        $this->helperData = $helperData;
        $this->taxHelper = $helperTax;
        $this->taxCalculator = $taxCalculator;
        $this->shippingMethod = $shippingMethod;
        $this->quoteRepository = $quoteRepository;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

            $items = $shippingAssignment->getItems();
            $getItemsQty = 0;
            foreach($items as $item) {

                $configSku = $this->helperData->getIsProductSku();
                $getIsShippingMethod = $this->helperData->getIsShippingMethod();
                // $getIsMessage = $this->helperData->getIsMessage();

                $skuarr = explode(",",$configSku);
                $getIsShippingMethod = explode(",",$getIsShippingMethod);

                // print_r($getIsShippingMethod);
                // die("rohit");

                $itemSku = $item->getSku();

                if(in_array($itemSku, $skuarr)){

                    $this->skuStatus = true; 
                    $getItemsQty += (int)$item->getQty();

                }else{

                    $this->skuStatus = false;

                }        
            }

         

        $enabled = $this->helperData->isModuleEnabled();
        $minimumOrderAmount = $this->helperData->getMinimumOrderAmount();
        $subtotal = $total->getTotalAmount('subtotal');
        if($this->skuStatus){

            if ($enabled && $minimumOrderAmount <= $subtotal) {

                $getProductFee = $this->helperData->getProductFee();
                $getProductFeeAmount = $this->helperData->getProductFeeAmount();
         
                if($quote->getId()){

                $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/rohits.log');
                $logger = new \Zend_Log();
                $logger->addWriter($writer);
                $logger->info('shipping method');

                $currentShippingMethod = $quote->getShippingAddress()->getShippingMethod();
                $logger->info($currentShippingMethod);

                if(in_array($currentShippingMethod, $getIsShippingMethod)){

                    $defaultFee = NULL;
    
                }else{

                    $defaultFee = $this->helperData->getExtrafee();

                }

                $logger->info("fee = ");
                $logger->info($defaultFee);
                
            //    $a == 0 ? $defaultFee = $this->helperData->getExtrafee() : $defaultFee = NULL;

              
                }else{

                    $defaultFee = $this->helperData->getExtrafee();
                
                }
               


                // $getExtrafee = $this->helperData->getExtrafee();
                //  $defaultFee = $quote->getFee();
                //  is_null($defaultFee) ? $defaultFee : $defaultFee = $getExtrafee;
                 $getQuoteItemsQty = $quote->getItemsQty();

                $getProductFee ? $fees = $getProductFeeAmount : $fees= $defaultFee;

                // isset($fees) ? $fee = ((int)$fees*(int)$getQuoteItemsQty) : $fee = NULL;
                $fee = ((int)$fees*(int)$getQuoteItemsQty);
                
                //Try to test with sample value
                // $fee=60;
                $total->setTotalAmount('fee', $fee);
                $total->setBaseTotalAmount('fee', $fee);
                $total->setFee($fee);
                $quote->setFee($fee);
    
    
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
                $version = (float)$productMetadata->getVersion();
    
                if($version > 2.1)
                {
                    //$total->setGrandTotal($total->getGrandTotal() + $fee);
                }
                else
                {
                    $total->setGrandTotal($total->getGrandTotal() + $fee);
                }
    
                if ($this->taxHelper->isTaxEnabled()) {
                    $address = $this->_getAddressFromQuote($quote);
                    $this->_calculateTax($address, $total);
    
                    $extraTaxables = $address->getAssociatedTaxables();
                    $extraTaxables[] = [
                        'code' => 'fee',
                        'type' => 'fee',
                        'quantity' => 1,
                        'tax_class_id' => $this->taxHelper->getTaxClassId(),
                        'unit_price' => $fee,
                        'base_unit_price' => $fee,
                        'price_includes_tax' => false,
                        'associated_item_code' => false
                    ];
    
                    $address->setAssociatedTaxables($extraTaxables);
                }
    
            }
        }

        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {

        $enabled = $this->helperData->isModuleEnabled();
        $minimumOrderAmount = $this->helperData->getMinimumOrderAmount();
        $subtotal = $quote->getSubtotal();
        $fee = $quote->getFee();
        $address = $this->_getAddressFromQuote($quote);

        // echo $fee;
        // die("dfsd");

        $result = [];

       
            $items = $quote->getAllItems();
            foreach($items as $item) {

                $configSku = $this->helperData->getIsProductSku();
                $skuarr = explode(",",$configSku);

                $itemSku = $item->getSku();

                if(in_array($itemSku, $skuarr)){

                    $skuStatus = true; 

                }else{

                    $skuStatus = false;

                }              
            }
            
            // $skuStatus ? $skuStatus : $skuStatus =false;
        if($skuStatus){

        if ($enabled && ($minimumOrderAmount <= $subtotal) && $fee) {
            $result = [
                'code' => 'fee',
                'title' => $this->helperData->getFeeLabel(),
                'value' => $fee
            ];

            if ($this->taxHelper->isTaxEnabled() && $this->taxHelper->displayInclTax()) {
                 $result [] = [
                    'code' => 'fee',
                    'value' => $fee + $address->getFeeTax(),
                    'title' => __($this->helperData->getFeeLabel()),
                ];
            }
            if ($this->taxHelper->isTaxEnabled() && $this->taxHelper->displayBothTax()) {
                $result [] = [
                    'code' => 'fee',
                    'value' => $fee + $address->getFeeTax(),
                    'title' => __($this->helperData->getFeeLabel()),
                ];
            }
        }
    }

        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Extra Fee');
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     */
    protected function clearValues(\Magento\Quote\Model\Quote\Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);

    }
    protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }

    protected function _calculateTax(Address $address, Total $total)
    {
        $taxClassId = $this->taxHelper->getTaxClassId();
        if (!$taxClassId) {
            return $this;
        }

        $taxRateRequest = $this->_getAddressTaxRequest($address);
        $taxRateRequest->setProductClassId($taxClassId);

        $rate = $this->taxCalculator->getRate($taxRateRequest);



        $baseTax = $this->taxCalculator->calcTaxAmount(
            $total->getBaseTotalAmount('fee'),
            $rate,
            false,
            true
        );
        $tax = $this->taxCalculator->calcTaxAmount(
            $total->getTotalAmount('fee'),
            $rate,
            false,
            true
        );



        //$total->setBaseMcPaymentfeeTaxAmount($baseTax);
        $total->setFeeTax($tax);

        $appliedRates = $this->taxCalculator->getAppliedRates($taxRateRequest);
        $this->_saveAppliedTaxes($address, $appliedRates, $tax, $baseTax, $rate);

        $total->addBaseTotalAmount('tax', $baseTax);
        $total->addTotalAmount('tax', $tax);

        return $this;
    }

    protected function _getAddressTaxRequest($address)
    {
        $addressTaxRequest = $this->taxCalculator->getRateRequest(
            $address,
            $address->getQuote()->getBillingAddress(),
            $address->getQuote()->getCustomerTaxClassId(),
            $address->getQuote()->getStore()
        );
        return $addressTaxRequest;
    }

    protected function _saveAppliedTaxes(
        Address $address,
        $applied,
        $amount,
        $baseAmount,
        $rate
    ) {
        $previouslyAppliedTaxes = $address->getAppliedTaxes();
        $process = 0;
        if(is_array($previouslyAppliedTaxes)) {
            $process = count($previouslyAppliedTaxes);
        }
        foreach ($applied as $row) {
            if ($row['percent'] == 0) {
                continue;
            }
            if (!isset($previouslyAppliedTaxes[$row['id']])) {
                $row['process'] = $process;
                $row['amount'] = 0;
                $row['base_amount'] = 0;
                $previouslyAppliedTaxes[$row['id']] = $row;
            }

            if ($row['percent'] !== null) {
                $row['percent'] = $row['percent'] ? $row['percent'] : 1;
                $rate = $rate ? $rate : 1;

                $appliedAmount = $amount / $rate * $row['percent'];
                $baseAppliedAmount = $baseAmount / $rate * $row['percent'];
            } else {
                $appliedAmount = 0;
                $baseAppliedAmount = 0;
                foreach ($row['rates'] as $rate) {
                    $appliedAmount += $rate['amount'];
                    $baseAppliedAmount += $rate['base_amount'];
                }
            }

            if ($appliedAmount || $previouslyAppliedTaxes[$row['id']]['amount']) {
                $previouslyAppliedTaxes[$row['id']]['amount'] += $appliedAmount;
                $previouslyAppliedTaxes[$row['id']]['base_amount'] += $baseAppliedAmount;
            } else {
                unset($previouslyAppliedTaxes[$row['id']]);
            }
        }
        $address->setAppliedTaxes($previouslyAppliedTaxes);
    }
}
