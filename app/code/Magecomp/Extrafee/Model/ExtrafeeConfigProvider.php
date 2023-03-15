<?php
namespace Magecomp\Extrafee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;

class ExtrafeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magecomp\Extrafee\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    protected $taxHelper;
    protected $QuoteFactory;

    /**
     * @param \Magecomp\Extrafee\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magecomp\Extrafee\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger,
        \Magecomp\Extrafee\Helper\Tax $helperTax,
        QuoteFactory $QuoteFactory

    )
    {
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
        $this->taxHelper = $helperTax;
        $this->QuoteFactory = $QuoteFactory;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        // die("hlo");
        $getProductFee = $this->dataHelper->getProductFee();
        $getProductFeeAmount = $this->dataHelper->getProductFeeAmount();
        // $defaultFee = $this->dataHelper->getExtrafee();
        // $quote = $this->checkoutSession->getQuote();
        $quoteId = $this->checkoutSession->getQuote()->getId();
        $quote = $this->QuoteFactory->create()->load($quoteId);
        $defaultFee = $quote->getFee();
        $getProductFee ? $fee = $getProductFeeAmount : $fee = $defaultFee;


        $ExtrafeeConfig = [];
        $enabled = $this->dataHelper->isModuleEnabled();
        $minimumOrderAmount = $this->dataHelper->getMinimumOrderAmount();
        $ExtrafeeConfig['fee_label'] = $this->dataHelper->getFeeLabel();
        $ExtrafeeConfig['conf_message'] = $this->dataHelper->getIsMessage();
        

        // $itemQty = $quote->getItemsQty();

        // $fee = ((int)$itemQty*(int)$fees);

        $subtotal = $quote->getSubtotal();
        $ExtrafeeConfig['custom_fee_amount'] = $fee;
        if ($this->taxHelper->isTaxEnabled() && $this->taxHelper->displayInclTax()) {
            $address = $this->_getAddressFromQuote($quote);
            $ExtrafeeConfig['custom_fee_amount'] = $fee + $address->getFeeTax();
        }
        if ($this->taxHelper->isTaxEnabled() && $this->taxHelper->displayBothTax()) {

            $address = $this->_getAddressFromQuote($quote);
            $ExtrafeeConfig['custom_fee_amount'] = $fee;
            $ExtrafeeConfig['custom_fee_amount_inc'] = $fee + $address->getFeeTax();

        }
        $ExtrafeeConfig['displayInclTax'] = $this->taxHelper->displayInclTax();
        $ExtrafeeConfig['displayExclTax'] = $this->taxHelper->displayExclTax();
        $ExtrafeeConfig['displayBoth'] = $this->taxHelper->displayBothTax();
        $ExtrafeeConfig['exclTaxPostfix'] = __('Excl. Tax');
        $ExtrafeeConfig['inclTaxPostfix'] = __('Incl. Tax');
        $ExtrafeeConfig['TaxEnabled'] = $this->taxHelper->isTaxEnabled();
        $ExtrafeeConfig['show_hide_Extrafee_block'] = ($enabled && ($minimumOrderAmount <= $subtotal) && $quote->getFee()) ? true : false;
        $ExtrafeeConfig['show_hide_Extrafee_shipblock'] = ($enabled && ($minimumOrderAmount <= $subtotal)) ? true : false;
        return $ExtrafeeConfig;
    }

    protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }
}
