<?php

namespace RiskyPlugin\Reducer;

use Shopware\Core\Framework\Context;

interface RiskyProductsReducerInterface
{
    /**
     * @param string[] $productIds
     * @return string[]
     */
    public function reduce(array $productIds, Context $context): array;
}