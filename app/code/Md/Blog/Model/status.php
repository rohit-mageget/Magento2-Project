<?php

/**

* Copyright © 2015 Magento. All rights reserved.

* See COPYING.txt for license details.

*/

namespace Md\Blog\Model;

class Status implements \Magento\Framework\Option\ArrayInterface {

const STATUS_ENABLED = 1;

const STATUS_DISABLED = 2;

public function toOptionArray() {

return [

[

self::STATUS_ENABLED => __('Enabled'),

self::STATUS_DISABLED => __('Disabled'),

],

];

}

public static function getOptionArray()

{

return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];

}

}