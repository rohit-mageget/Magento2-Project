<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageget\OfflinePayment\Model\Payment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;

class InstructionsConfigProvider implements ConfigProviderInterface
{
    /**
     * @var string[]
     */
    protected $methodCodes = [
        Simple::PAYMENT_METHOD_CUSTOM_SIMPLE_CODE,
    ];

    /**
     * @var \Magento\Payment\Model\Method\AbstractMethod[]
     */
    protected $methods = [];

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param PaymentHelper $paymentHelper
     * @param Escaper $escaper
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper
    ) {
        $this->escaper = $escaper;
        foreach ($this->methodCodes as $code) {
            $this->methods[$code] = $paymentHelper->getMethodInstance($code);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [];
        foreach ($this->methodCodes as $code) {
            if ($this->methods[$code]->isAvailable()) {
                $config['payment']['company_name'][$code] = $this->getCompanyName($code);
                $config['payment']['bank_name'][$code] = $this->getBankName($code);
                $config['payment']['cheque_number'][$code] = $this->getChequeNumber($code);
                $config['payment']['gst_number'][$code] = $this->getGstNumber($code);
            }
        }
        return $config;
    }
    /**
     * Get instructions text from config
     *
     * @param string $code
     * @return string
     */
    protected function getBankName($code)
    {
        return $this->escaper->escapeHtml($this->methods[$code]->getBankName());
    }
    protected function getChequeNumber($code)
    {
        return nl2br($this->escaper->escapeHtml($this->methods[$code]->getChequeNumber()));
    }
    protected function getGstNumber($code)
    {
        return nl2br($this->escaper->escapeHtml($this->methods[$code]->getGstNumber()));
    }
    protected function getCompanyName($code)
    {
        return nl2br($this->escaper->escapeHtml($this->methods[$code]->getCompanyName()));
    }
}
