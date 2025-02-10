<?php

namespace RiskyPlugin\Core\Checkout\Cart\Custom;

use RiskyPlugin\Core\Checkout\Cart\Custom\Error\TooMuchRiskyProductsError;
use RiskyPlugin\Reducer\RiskyProductsReducerInterface;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartValidatorInterface;
use Shopware\Core\Checkout\Cart\Error\ErrorCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

use function in_array;

final readonly class RiskyProductCartValidator implements CartValidatorInterface
{
    public function __construct(
        private RiskyProductsReducerInterface $riskyProductsReducer,
    ) {
    }

    public function validate(Cart $cart, ErrorCollection $errors, SalesChannelContext $context): void
    {
        $productItems = $cart->getLineItems()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE);

        $riskyProductsIds = $this->riskyProductsReducer->reduce(
            $productItems->getReferenceIds(),
            $context->getContext()
        );

        $hasRiskyProduct = false;

        foreach ($productItems as $productItem) {
            if ($this->isProductRisky($productItem->getReferencedId(), $riskyProductsIds)
                && ($hasRiskyProduct || $productItem->getQuantity() > 1)
            ) {
                $errors->add(new TooMuchRiskyProductsError($productItem->getId()));
                return;
            }

            $hasRiskyProduct |= $this->isProductRisky($productItem->getReferencedId(), $riskyProductsIds);
        }
    }

    private function isProductRisky(string $productId, array $riskyProductsIds): bool
    {
        return in_array($productId, $riskyProductsIds, true);
    }
}