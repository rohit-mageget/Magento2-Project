<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageget\OfflinePayment\Block\Info;

class Simple extends \Magento\Payment\Block\Info
{
    // // /**
    // //  * @var string
    // //  */
    // // protected $_payableTo;

    // /**
    //  * @var string
    //  */
    // protected $_companyName;
    // protected $_bankName;
    // protected $_chequeNumber;
    // protected $_gstNumber;

    /**
     * @var string
     */
    protected $_template = 'Mageget_OfflinePayment::info/simple.phtml';

    // /**
    //  * Enter description here...
    //  *
    //  * @return string
    //  */
    // public function getBankName()
    // {
    //     if ($this->_bankName === null) {
    //         $this->_convertAdditionalData();
    //     }
    //     return $this->_bankName;
    // }

    // public function getChequeNumber()
    // {
    //     if ($this->_chequeNumber === null) {
    //         $this->_convertAdditionalData();
    //     }
    //     return $this->_chequeNumber;
    // }

    // public function getGstNumber()
    // {
    //     if ($this->_gstNumber === null) {
    //         $this->_convertAdditionalData();
    //     }
    //     return $this->_gstNumber;
    // }

    // /**
    //  * Enter description here...
    //  *
    //  * @return string
    //  */
    // public function getCompanyName()
    // {
    //     if ($this->_companyName === null) {
    //         $this->_convertAdditionalData();
    //     }
    //     return $this->_companyName;
    // }

    // /**
    //  * @deprecated 100.1.1
    //  * @return $this
    //  */
    // protected function _convertAdditionalData()
    // {
    //     $this->_bankName = $this->getInfo()->getAdditionalInformation('bank_name');
    //     $this->_chequeNumber = $this->getInfo()->getAdditionalInformation('cheque_number');
    //     $this->_gstNumber = $this->getInfo()->getAdditionalInformation('gst_number');
    //     $this->_companyName = $this->getInfo()->getAdditionalInformation('company_name');
    //     return $this;
    // }

    /**
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('Mageget_OfflinePayment::info/pdf/simple.phtml');
        return $this->toHtml();
    }
}
