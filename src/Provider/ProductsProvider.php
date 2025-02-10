<?php

namespace RiskyPlugin\Provider;

use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

final readonly class ProductsProvider implements ProductsProviderInterface
{
    public function __construct(private EntityRepository $productRepository)
    {
    }

    /**
     * @param string[] $ids
     */
    public function getByIds(array $ids, Context $context): ProductCollection
    {
        return $this->productRepository->search(
            new Criteria($ids),
            $context
        )->getEntities();
    }
}