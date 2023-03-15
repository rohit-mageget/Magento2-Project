<?php

namespace Webkul\MagentoTopProducts\Model;

use Magento\Framework\Api\AbstractSimpleObject;
use Webkul\MagentoTopProducts\Api\Data\ProductSearchResultsInterface;

/**
 * Class ProductSearchResults
 *
 * @package Webkul\MagentoTopProducts\Model
 */
class ProductSearchResults extends AbstractSimpleObject implements ProductSearchResultsInterface
{
    const KEY_ITEMS           = 'items';
    const KEY_SEARCH_CRITERIA = 'search_criteria';
    const KEY_TOTAL_COUNT     = 'total_count';

    /**
     * Get items
     *
     * @return \Webkul\MagentoTopProducts\Api\Data\ProductInterface[]
     */
    public function getItems()
    {
        return $this->_get(self::KEY_ITEMS) === null ? [] : $this->_get(self::KEY_ITEMS);
    }

    /**
     * Set items
     *
     * @param \Webkul\MagentoTopProducts\Api\Data\ProductInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items)
    {
        return $this->setData(self::KEY_ITEMS, $items);
    }

    /**
     * Get search criteria
     *
     * @return \Webkul\MagentoTopProducts\Model\ProductSearchCriteria
     */
    public function getSearchCriteria()
    {
        return $this->_get(self::KEY_SEARCH_CRITERIA);
    }

    /**
     * Set search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return $this
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this->setData(self::KEY_SEARCH_CRITERIA, $searchCriteria);
    }

    /**
     * Get total count
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->_get(self::KEY_TOTAL_COUNT);
    }

    /**
     * Set total count
     *
     * @param int $count
     *
     * @return $this
     */
    public function setTotalCount($count)
    {
        return $this->setData(self::KEY_TOTAL_COUNT, $count);
    }
}
