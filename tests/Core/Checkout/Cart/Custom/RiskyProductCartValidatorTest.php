<?php

namespace RiskyPlugin\Tests\Core\Checkout\Cart\Custom;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RiskyPlugin\Core\Checkout\Cart\Custom\RiskyProductCartValidator;
use RiskyPlugin\Reducer\RiskyProductsReducerInterface;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\Error\ErrorCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

final class RiskyProductCartValidatorTest extends TestCase
{
    private Cart|MockObject $cart;
    private LineItem|MockObject $item1;
    private LineItem|MockObject $item2;
    private ErrorCollection|MockObject $errors;
    private SalesChannelContext|MockObject $salesChannelContext;
    private Context|MockObject $context;
    private RiskyProductsReducerInterface|MockObject $riskyProductsReducer;

    public function testPassValidationWhenCartIsEmpty(): void
    {
        //Arrange
        $this->riskyProductsReducer->method('reduce')->willReturn([]);
        $this->cart->method('getLineItems')->willReturn(
            new LineItemCollection()
        );

        $riskyProductCartValidator = new RiskyProductCartValidator(
            $this->riskyProductsReducer,
        );

        //Act & Assert
        $this->errors->expects(self::never())->method('add');

        $riskyProductCartValidator->validate(
            $this->cart,
            $this->errors,
            $this->salesChannelContext,
        );
    }

    public function testPassValidationWhenCartHasNoRiskyProducts(): void
    {
        //Arrange
        $this->riskyProductsReducer->method('reduce')->willReturn([]);

        $this->cart->method('getLineItems')->willReturn(
            new LineItemCollection([$this->item1, $this->item2])
        );

        $riskyProductCartValidator = new RiskyProductCartValidator(
            $this->riskyProductsReducer,
        );

        //Act & Assert
        $this->errors->expects(self::never())->method('add');

        $riskyProductCartValidator->validate(
            $this->cart,
            $this->errors,
            $this->salesChannelContext,
        );
    }

    public function testPassValidationNotPassWhenCartHasTwoRiskyProducts(): void
    {
        //Arrange
        $this->riskyProductsReducer->method('reduce')->willReturn(
            ['111', '222']
        );

        $this->cart->method('getLineItems')->willReturn(
            new LineItemCollection([$this->item1, $this->item2])
        );

        $riskyProductCartValidator = new RiskyProductCartValidator(
            $this->riskyProductsReducer,
        );

        //Act & Assert
        $this->errors->expects(self::once())->method('add');

        $riskyProductCartValidator->validate(
            $this->cart,
            $this->errors,
            $this->salesChannelContext,
        );
    }

    public function testPassValidationNotPassWhenCartHasRiskyProductWithQuantityGreaterThanOne(): void
    {
        //Arrange
        $this->riskyProductsReducer->method('reduce')->willReturn(
            ['111']
        );

        $this->item1->method('getQuantity')->willReturn(2);

        $this->cart->method('getLineItems')->willReturn(
            new LineItemCollection([$this->item1])
        );

        $riskyProductCartValidator = new RiskyProductCartValidator(
            $this->riskyProductsReducer,
        );

        //Act & Assert
        $this->errors->expects(self::once())->method('add');

        $riskyProductCartValidator->validate(
            $this->cart,
            $this->errors,
            $this->salesChannelContext,
        );
    }

    protected function setUp(): void
    {
        $this->item1 = $this->createMock(LineItem::class);
        $this->item1->method('getReferencedId')->willReturn('111');
        $this->item1->method('getId')->willReturn('112');
        $this->item1->method('getType')->willReturn(LineItem::PRODUCT_LINE_ITEM_TYPE);

        $this->item2 = $this->createMock(LineItem::class);
        $this->item2->method('getReferencedId')->willReturn('222');
        $this->item2->method('getId')->willReturn('223');
        $this->item2->method('getType')->willReturn(LineItem::PRODUCT_LINE_ITEM_TYPE);

        $this->cart = $this->createMock(Cart::class);
        $this->errors = $this->createMock(ErrorCollection::class);
        $this->salesChannelContext = $this->createMock(SalesChannelContext::class);
        $this->context = $this->createMock(Context::class);
        $this->riskyProductsReducer = $this->createMock(
            RiskyProductsReducerInterface::class
        );

        $this->salesChannelContext->method('getContext')->willReturn($this->context);
    }
}