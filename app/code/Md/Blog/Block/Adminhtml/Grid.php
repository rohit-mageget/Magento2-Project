<?php

/**

* Copyright © Magento, Inc. All rights reserved.

* See COPYING.txt for license details.

*/

namespace Md\Blog\Block\Adminhtml;

/**

* Backend grid container block

*/

class Grid extends \Magento\Backend\Block\Widget\Container {

/**

* @var string

*/

protected $_template = 'grid/view.phtml';

/**

* @param \Magento\Backend\Block\Widget\Context $context

* @param array                                 $data

*/

public function __construct(

\Magento\Backend\Block\Widget\Context $context,

array $data = []

) {

parent::__construct($context, $data);

}

/**

* {@inheritdoc}

*/

protected function _prepareLayout() {

$addButtonProps = [

'id' => 'add_new_grid',

'label' => __('Add New'),

'class' => 'add',

'button_class' => '',

'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',

'options' => $this->_getAddButtonOptions(),

];

$this->buttonList->add('add_new', $addButtonProps);

$this->setChild('grid', $this->getLayout()->createBlock('Md\Blog\Block\Adminhtml\Grid\Grid', 'grid.view.grid'));



return parent::_prepareLayout();

}

/**

* @return array

*/

protected function _getAddButtonOptions() {

$splitButtonOptions[] = [

'label' => __('Add New'),

'onclick' => "setLocation('" . $this->_getCreateUrl() . "')",

];

return $splitButtonOptions;

}

/**

* @return string

*/

protected function _getCreateUrl() {

return $this->getUrl('blog/*/new');

}

/**

* @return string

*/

public function getGridHtml() {

return $this->getChild('grid');

}

}