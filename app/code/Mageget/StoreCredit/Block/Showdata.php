<?php

namespace Mageget\StoreCredit\Block;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Mageget\StoreCredit\Model\ResourceModel\Grid\CollectionFactory;
use Magento\Customer\Model\Session;

class Showdata extends Template
{

    public $collection;
    public $_customerSession;

    public function __construct(Context $context, CollectionFactory $collectionFactory, Session $customerSession, array $data = [])
    {
        $this->collection = $collectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
     parent::_prepareLayout();

    if ($this->getCollection()) {
     $pager = $this->getLayout()->createBlock(
    'Magento\Theme\Block\Html\Pager', 'custom.history.pager')->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])->setShowPerPage(true)->
    setCollection($this->getCollection()); $this->setChild('pager', $pager);$this->getCollection()->load();

    }
    return $this;
     }
    public function getPagerHtml()
    {
    return $this->getChildHtml('pager');
    }

    public function getCollection()
    {
        $cus_id = $this->_customerSession->getCustomer()->getId();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest(
        )->getParam('limit') : 5;
        $collection = $this->collection->create();
        $collection = $collection->addFieldToFilter('customer_id', $cus_id);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

}
