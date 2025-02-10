<?php

namespace RiskyPlugin\Core\Checkout\Cart\Custom\Error;

use Shopware\Core\Checkout\Cart\Error\Error;

final class TooMuchRiskyProductsError extends Error
{
    private const string KEY = 'too-much-risky-products';

    public function __construct(private readonly string $lineItemId)
    {
        parent::__construct();
    }

    public function getId(): string
    {
        return $this->lineItemId;
    }

    public function getMessageKey(): string
    {
        return self::KEY;
    }

    public function getLevel(): int
    {
        return self::LEVEL_ERROR;
    }

    public function blockOrder(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function getParameters(): array
    {
        return [
            'lineItemId' => $this->lineItemId
        ];
    }
}