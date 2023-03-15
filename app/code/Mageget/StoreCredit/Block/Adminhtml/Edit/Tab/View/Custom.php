<?php

namespace Mageget\StoreCredit\Block\Adminhtml\Edit\Tab\View;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Registry;
use Mageget\StoreCredit\Model\ResourceModel\Grid\CollectionFactory;

class Custom extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_coreRegistry = null;

    protected $_collectionFactory;
    protected $_gridFactory;

    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

protected
function _construct()
{
    parent::_construct();
    $this->setId('view_custom_grid');
    $this->setDefaultSort('created_at', 'desc');
    $this->setSortable(true);
    $this->setPagerVisibility(true);
    $this->setFilterVisibility(true);
    // $this->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20]);
}

protected function _prepareCollection()
{
    $collection = $this->_collectionFactory->create();
    

    $cus_id = $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);

    $grid = $collection->addFieldToFilter('customer_id', $cus_id);

    $this->setCollection($grid);
    return parent::_prepareCollection();
}


protected function _prepareColumns()
{

    //  echo "<pre>";
    // print_r($this->getCollection('credit_change'));
    // die("gvfd");

    $this->addColumn(
        'entity_id',
        ['header' => __('ID'), 'index' => 'entity_id', 'type' => 'number', 'width' => '100px']
    );
    $this->addColumn(
        'customer_id',
        ['header' => __('Customer Id'), 'index' => 'customer_id', 'type' => 'text', 'width' => '100px']
    );
    $this->addColumn(
        'credit_change',
        ['header' => __('Change Balance'), 'index' => 'credit_change', 'type' => 'text', 'width' => '100px']
    );
    $this->addColumn(
        'current_credit',
        ['header' => __('Current Balance'), 'index' => 'current_credit', 'type' => 'text', 'width' => '100px']
    );
    $this->addColumn(
        'action',
        ['header' => __('Action'), 'index' => 'action', 'type' => 'text', 'width' => '100px']
    );
    $this->addColumn(
        'created_at',
        ['header' => __('Date'), 'index' => 'created_at', 'type' => 'TYPE_TIMESTAMP', 'width' => '100px']
    );

    return parent::_prepareColumns();
}

public function getHeadersVisibility()
{
    return $this->getCollection()->getSize() >= 0;
}

// public function getRowUrl($row)
// {
//     return $this->getUrl('catalog/product/edit', ['id' => $row->getProductId()]);
// }
}