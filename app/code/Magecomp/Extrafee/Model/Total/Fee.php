<?php

namespace Magecomp\Extrafee\Model\Total;

class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    protected $helperData;
    protected $skuStatus;

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
                                \Magecomp\Extrafee\Helper\Data $helperData)
    {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
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
        // $methodTitle = $this->shippingMethod->getMethodTitle();
        // echo "shipping method : ".$methodTitle;
        // die("rohit");
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('Total Fee : ');

            $items = $shippingAssignment->getItems();
            foreach($items as $item) {

                        $configSku = $this->helperData->getIsProductSku();
                        $skuarr = explode(",",$configSku);

                        $itemSku = $item->getSku();

                        if(in_array($itemSku, $skuarr)){

                            $this->skuStatus = true; 

                        }else{

                            $this->skuStatus = false;

                        }            
            }

        $enabled = $this->helperData->isModuleEnabled();
        $minimumOrderAmount = $this->helperData->getMinimumOrderAmount();
        $subtotal = $total->getTotalAmount('subtotal');

        // die("mageget");

        if($this->skuStatus){

        if ($enabled && $minimumOrderAmount <= $subtotal) {
            $fee = $quote->getFee();
            $total->setTotalAmount('fee', $fee);
            $total->setBaseTotalAmount('fee', $fee);
            $total->setFee($fee);
            $quote->setFee($fee);
            $total->setGrandTotal($total->getGrandTotal() + $fee);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() + $fee);
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

        if($skuStatus){

        if ($enabled && $minimumOrderAmount <= $subtotal && $fee) {
            return [
                'code' => 'fee',
                'title' => 'Custom Fee',
                'value' => $fee
            ];
        } else {
            return array();
        }
    }
    
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Custom Fee');
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
}
