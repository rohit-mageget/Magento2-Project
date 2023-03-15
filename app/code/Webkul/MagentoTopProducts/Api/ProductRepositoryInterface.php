<?php

namespace Webkul\MagentoTopProducts\Api;

/**
 * Interface ProductRepositoryInterface
 *
 * @api
 * @package Webkul\MagentoTopProducts\Api
 */
interface ProductRepositoryInterface
{
    const FILTER_TYPE_TOP_SELLING = 'selling';
    const FILTER_TYPE_TOP_FREE    = 'free';
    const FILTER_TYPE_TOP_RATED   = 'rated';

    /**
     * @param string                                                       $type Type of source
     * @param \Webkul\MagentoTopProducts\Api\ProductSearchCriteriaInterface $searchCriteria
     *
     * @return \Webkul\MagentoTopProducts\Api\Data\ProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList($type, ProductSearchCriteriaInterface $searchCriteria = null);
}
