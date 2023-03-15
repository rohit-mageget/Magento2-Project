<?php

namespace Webkul\MagentoTopProducts\Api\Data;

/**
 * Search results interface.
 *
 * @api
 * @package Webkul\MagentoTopProducts\Api\Data
 */
interface ProductSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Gets collection items.
     *
     * @return \Webkul\MagentoTopProducts\Api\Data\ProductInterface[] Array of collection items.
     */
    public function getItems();

    /**
     * Set collection items.
     *
     * @param \Webkul\MagentoTopProducts\Api\Data\ProductInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
