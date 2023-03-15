<?php

namespace Webkul\Bestseller\Api\Data;

/**
 * ProductSlidersInterface interface.
 * @api
 */
interface ProductSlidersInterface
{
    /**
    * @return \Webkul\Bestseller\Api\Data\ProductSlidersInterface[]
     */
    public function getBestsellerProduct();

    /**
     * @param string $bestsellerProduct
     * @return $this
     */
    public function setBestsellerProduct($bestsellerProduct);

    /**
    * @return \Webkul\Bestseller\Api\Data\FeaturedSlidersInterface[]
     */
    public function getProductCount();

    /**
     * @param string $productCount
     * @return $this
     */
    public function setProductCount($productCount);  
}