<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Mageget\OfflinePayment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageget\OfflinePayment\Model\Payment\Simple;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Sets payment additional information.
 */
class BeforeOrderPaymentSaveObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {

        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $observer->getEvent()->getPayment();
       
        // $companyName = $payment->getData('company_name');
        // print_r($payment->debug());
        // die("esfef");
        
        // $dataA = $payment->getData('company_name');
        // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/logtestjhgjhgjh.log');
        //     $logger = new \Zend\Log\Logger();
        //     $logger->addWriter($writer);
        //     $logger->info('Simple Text Log'); 
        //     $logger->info('Array Log'.print_r($extName, true));
        //     $logger->info('Array Log'.print_r($dataA, true));
        //     $logger->info('Array Log'.print_r($payment, true));


        if ($payment->getMethod() === Simple::PAYMENT_METHOD_CUSTOM_SIMPLE_CODE) {
            $methodInstance = $payment->getMethodInstance();
            $additionalData = $payment->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
            // $storeId = $payment->getOrder()->getStoreId();
            // $paymentMethod = $payment->getMethod();

            // $paymentData = $payment->getData('additional_data');
            // $companyName = $paymentData['company_name'];
            $cdata = $additionalData['company_name'];
            // $cdata = $methodInstance->getCompanyName();

            // $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
            // $logger = new \Zend_Log();
            // $logger->addWriter($writer);
            // $logger->info('text message 121');
            // $logger->info(print_r($payment->getData(), true));
            // $logger->info(print_r($payment, true));
           
            // $bankName = $methodInstance->getConfigData('bank_name', $storeId);
            // $chequeNumber = $methodInstance->getConfigData('cheque_number', $storeId);
            // $gstNumber = $methodInstance->getConfigData('gst_number', $storeId);
            if (!empty($cdata)) {
                $payment->setAdditionalInformation('company_name', $cdata);
            }else{
                $payment->setAdditionalInformation('company_name', "TBI");
            }
            // if (!empty($bankName)) {
            //     $payment->setAdditionalInformation('bank_name', $bankName);
            // }
            // if (!empty($chequeNumber)) {
            //     $payment->setAdditionalInformation('cheque_number', $chequeNumber);
            // }
            // if (!empty($gstNumber)) {
            //     $payment->setAdditionalInformation('gst_number', $gstNumber);
            // }
        }
    }
}


