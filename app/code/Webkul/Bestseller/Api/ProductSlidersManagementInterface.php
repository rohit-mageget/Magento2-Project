<?php

namespace Webkul\Bestseller\Api;

interface ProductSlidersManagementInterface
{
     /**
     * Get Bestseller Product Slider api
     * @param int $currentPage
     * @param int $pageSize
     * @return \Webkul\Bestseller\Api\Data\ProductSlidersInterface
     */
    public function getBestseller($currentPage = 1, $pageSize = 10);
}