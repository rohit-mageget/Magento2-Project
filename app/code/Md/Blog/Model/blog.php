<?php
/**
* Copyright © 2015 Magento. All rights reserved.
* See COPYING.txt for license details.
*/
namespace Md\Blog\Model;
class Blog extends \Magento\Framework\Model\AbstractModel {
protected function _construct() {
$this->_init('Md\Blog\Model\ResourceModel\Blog');
}
}