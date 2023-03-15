<?php
/**
* Copyright © 2015 Magento. All rights reserved.
* See COPYING.txt for license details.
*/
namespace Md\Blog\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Blog extends AbstractDb {
/**
* Initialize resource model
*
* @return void
*/
protected function _construct() {
$this->_init('Md_blog', 'id');
}
}