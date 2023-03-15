<?php

/**

* Copyright © Magento, Inc. All rights reserved.

* See COPYING.txt for license details.

*/

namespace Md\Blog\Block\Adminhtml\Grid;

/**

* Adminhtml block grid demo records grid block

*

*/

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

/**

* @var \Magento\Framework\Module\Manager

*/

protected $moduleManager;

/**

* @var \Md\Blog\Model\BlogFactory

*/

protected $_blogFactory;

/**

* @var \Md\Blog\Model\Status

*/

protected $_status;

/**

* @param \Magento\Backend\Block\Template\Context $context

* @param \Magento\Backend\Helper\Data            $backendHelper

* @param \Md\Blog\Model\BlogFactory              $blogFactory

* @param \Md\Blog\Model\Status                   $status

* @param \Magento\Framework\Module\Manager       $moduleManager

* @param array                                   $data

*/

public function __construct(

\Magento\Backend\Block\Template\Context $context,

\Magento\Backend\Helper\Data $backendHelper,

\Md\Blog\Model\BlogFactory $blogFactory,

\Md\Blog\Model\Status $status,

\Magento\Framework\Module\Manager $moduleManager,

array $data = []

) {

$this->_blogFactory = $blogFactory;

$this->_status = $status;

$this->moduleManager = $moduleManager;

parent::__construct($context, $backendHelper, $data);



}

/**

* @return void

*/

protected function _construct() {

parent::_construct();

$this->setId('gridGrid');

$this->setDefaultSort('id');

$this->setDefaultDir('DESC');

$this->setSaveParametersInSession(true);

$this->setUseAjax(true);

$this->setVarNameFilter('grid_record');



}

/**

* @return $this

*/

protected function _prepareCollection() {

$collection = $this->_blogFactory->create()->getCollection();


$this->setCollection($collection);

parent::_prepareCollection();

return $this;

}

/**

* @return $this

*/

protected function _prepareColumns() {



$this->addColumn(

'id',

[

'header' => __('ID'),

'type' => 'number',

'index' => 'id',

'header_css_class' => 'col-id',

'column_css_class' => 'col-id',

]

);

$this->addColumn(

'name',

[

'header' => __('Name'),

'index' => 'name',

]

);

$this->addColumn(

'title',

[

'header' => __('Title'),

'index' => 'title',

]

);

$this->addColumn(

'status',

[

'header' => __('Status'),

'index' => 'status',

'type' => 'options',

'options' => $this->_status->getOptionArray(),

]

);

$this->addColumn(

'created_at',

[

'header' => __('Created At'),

'index' => 'created_at',

]

);

$this->addColumn(

'edit',

[

'header' => __('Edit'),

'type' => 'action',

'getter' => 'getId',

'actions' => [

[

'caption' => __('Edit'),

'url' => [

'base' => 'blog/*/edit',

],

'field' => 'id',

],

],

'filter' => false,

'sortable' => false,

'index' => 'stores',

'header_css_class' => 'col-action',

'column_css_class' => 'col-action',

]

);

$this->addColumn(

'delete',

[

'header' => __('Delete'),

'type' => 'action',

'getter' => 'getId',

'actions' => [

[

'caption' => __('Delete'),

'url' => [

'base' => 'blog/*/delete',

],

'field' => 'id',

],

],

'filter' => false,

'sortable' => false,

'index' => 'stores',

'header_css_class' => 'col-action',

'column_css_class' => 'col-action',

]

);

$block = $this->getLayout()->getBlock('grid.bottom.links');

if ($block) {

$this->setChild('grid.bottom.links', $block);

}



return parent::_prepareColumns();

}

/**

* @return $this

*/

protected function _prepareMassaction() {

    

$this->setMassactionIdField('id');

$this->getMassactionBlock()->setFormFieldName('id');

$this->getMassactionBlock()->addItem(

'delete',

[

'label' => __('Delete'),

'url' => $this->getUrl('blog/*/massDelete'),

'confirm' => __('Are you sure?'),

]

);

$statuses = $this->_status->toOptionArray();

array_unshift($statuses, ['label' => '', 'value' => '']);

$this->getMassactionBlock()->addItem(

'status',

[

'label' => __('Change status'),

'url' => $this->getUrl('blog/*/massStatus', ['_current' => true]),

'additional' => [

'visibility' => [

'name' => 'status',

'type' => 'select',

'class' => 'required-entry',

'label' => __('Status'),

'values' => $statuses,

],

],

]

);


return parent::_prepareMassaction();

}

/**

* @return string

*/

public function getGridUrl() {
  
return $this->getUrl('blog/grid/grid', ['_current' => true]);

}

/**

* @return string

*/

public function getRowUrl($row) {

return $this->getUrl('blog/grid/edit', ['id' => $row->getId()]);

}

}