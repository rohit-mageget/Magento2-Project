<?php

namespace Mageget\OfflinePayment\Model\Payment;

class Simple extends \Magento\Payment\Model\Method\AbstractMethod
{
const PAYMENT_METHOD_CUSTOM_SIMPLE_CODE = 'simple';
// const PAYMENT_METHOD_COMPANY_NAME_CODE = 'company_name';
// const PAYMENT_METHOD_CUSTOM_SIMPLE_CODE = 'simple';
/**
* Payment method code
*
* @var string
*/
protected $_code = self::PAYMENT_METHOD_CUSTOM_SIMPLE_CODE;

/**
     * @var string
     */
    protected $_formBlockType = \Mageget\OfflinePayment\Block\Form\Simple::class;

    /**
     * @var string
     */
    protected $_infoBlockType = \Mageget\OfflinePayment\Block\Info\Simple::class;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;


    /**
     * Assign data to info model instance
     *
     * @param \Magento\Framework\DataObject|mixed $data
     * @return $this
     * @throws LocalizedException
     */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        // $this->getInfoInstance()->setBankName($data->getBankName());
        // $this->getInfoInstance()->setChequeNumber($data->getChequeNumber());
        // $this->getInfoInstance()->setGstNumber($data->getGstNumber());
        // print_r($data);
        // die("dfvdsf");
            $cdata = $data->getData('additional_data');
            $adata = $cdata['company_name'];
            // $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
            // $logger = new \Zend_Log();
            // $logger->addWriter($writer);
            // $logger->info('text message 12');
            // $logger->info(print_r($cdata, true));
            // $logger->info($adata);
        $this->getInfoInstance()->setData('company_name', $adata);

        return $this;
    }

    // public function assignData(\Magento\Framework\DataObject $data)
    // {
    //     $this->_eventManager->dispatch(
    //         'payment_method_assign_data_' . $this->getCode(),
    //         [
    //             AbstractDataAssignObserver::METHOD_CODE => $this,
    //             AbstractDataAssignObserver::MODEL_CODE => $this->getInfoInstance(),
    //             AbstractDataAssignObserver::DATA_CODE => $data
    //         ]
    //     );

    //     $this->_eventManager->dispatch(
    //         'payment_method_assign_data',
    //         [
    //             AbstractDataAssignObserver::METHOD_CODE => $this,
    //             AbstractDataAssignObserver::MODEL_CODE => $this->getInfoInstance(),
    //             AbstractDataAssignObserver::DATA_CODE => $data
    //         ]
    //     );

    //     return $this;
    // }
    /**
     * Validate payment method information object
     *
     * @return $this
     * @throws LocalizedException
     * @api
     * @since 100.2.3
     */
    public function validate()
    {
        parent::validate();

        return $this;
    }


    // /**
    //  * @return string
    //  */
    // public function getBankName()
    // {
    //     $configValue = $this->getConfigData('bank_name');
    //     $miltiselectValues = explode(',', $configValue);
    //     return $miltiselectValues;
    // }

    // /**
    //  * @return string
    //  */
    // public function getChequeNumber()
    // {
    //     return $this->getConfigData('cheque_number');
    // }

    //  /**
    //  * @return string
    //  */
    // public function getGstNumber()
    // {
    //     return $this->getConfigData('gst_number');
    // }

    //  /**
    //  * @return string
    //  */
    // public function getCompanyName()
    // {
    //     $data = new DataObject();
    //     $cdata = $data->getData('additional_data');
    //     $adata = $cdata['company_name'];
    //     $companyname = $adata ? $adata : "magegetsoftware";
    //     return $companyname;
    // }

}

