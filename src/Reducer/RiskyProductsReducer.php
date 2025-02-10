<?php

namespace RiskyPlugin\Reducer;

use RiskyPlugin\Enum\ProductCustomFieldsEnum;
use RiskyPlugin\Provider\ProductsProviderInterface;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;

final readonly class RiskyProductsReducer implements RiskyProductsReducerInterface
{
    public function __construct(private ProductsProviderInterface $productsProvider)
    {
    }

    /**
     * @param string[] $productIds
     * @return string[]
     */
    public function reduce(array $productIds, Context $context): array
    {
        if (empty($productIds)) {
            return [];
        }

        $products = $this->productsProvider->getByIds($productIds, $context);

        return $products->filter(
            static fn(ProductEntity $product) => $product->getCustomFieldsValue(ProductCustomFieldsEnum::IS_RISKY_PRODUCT->value)
        )->getIds();
    }
}