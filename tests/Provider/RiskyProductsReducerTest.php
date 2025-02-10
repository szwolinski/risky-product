<?php

namespace Provider;

use PHPUnit\Framework\TestCase;
use RiskyPlugin\Enum\ProductCustomFieldsEnum;
use RiskyPlugin\Provider\ProductsProviderInterface;
use RiskyPlugin\Reducer\RiskyProductsReducer;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;

class RiskyProductsReducerTest extends TestCase
{
    public function testReduce()
    {
        //Arrange
        $productProvider = $this->createMock(ProductsProviderInterface::class);
        $context = $this->createMock(Context::class);

        $product1 = $this->createMock(ProductEntity::class);
        $product1->method('getId')->willReturn('111');
        $product1->method('getUniqueIdentifier')->willReturn('111');
        $product1->method('getCustomFieldsValue')
            ->with(ProductCustomFieldsEnum::IS_RISKY_PRODUCT->value)
            ->willReturn(true);

        $product2 = $this->createMock(ProductEntity::class);
        $product2->method('getId')->willReturn('222');
        $product2->method('getUniqueIdentifier')->willReturn('222');
        $product2->method('getCustomFieldsValue')
            ->with(ProductCustomFieldsEnum::IS_RISKY_PRODUCT->value)
            ->willReturn(false);

        $productProvider->method('getByIds')->willReturn(
            new ProductCollection([$product1, $product2])
        );

        $provider = new RiskyProductsReducer($productProvider);

        //Act
        $riskyProducts = $provider->reduce(['111', '222'], $context);

        //Assert
        $this->assertSame(['111' => '111'], $riskyProducts);
    }
}