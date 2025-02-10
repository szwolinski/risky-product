<?php

namespace RiskyPlugin\Provider;

use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\Context;

interface ProductsProviderInterface
{
    /**
     * @param string[] $ids
     */
    public function getByIds(array $ids, Context $context): ProductCollection;
}