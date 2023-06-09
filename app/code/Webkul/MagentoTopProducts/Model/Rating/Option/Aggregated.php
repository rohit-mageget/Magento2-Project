<?php

namespace Webkul\MagentoTopProducts\Model\Rating\Option;

use Magento\Framework\Model\AbstractModel;
use Webkul\MagentoTopProducts\Model\ResourceModel\Rating\Option\Aggregated as AggregatedResourceModel;

/**
 * Aggregated vote model
 *
 * @api
 * @package Webkul\MagentoTopProducts\Model\Rating\Option
 */
class Aggregated extends AbstractModel
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(AggregatedResourceModel::class);
    }
}
