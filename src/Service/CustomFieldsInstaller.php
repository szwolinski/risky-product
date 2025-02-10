<?php declare(strict_types=1);

namespace RiskyPlugin\Service;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\CustomField\CustomFieldTypes;

use function array_map;

final readonly class CustomFieldsInstaller
{
    /** @var string */
    private const string CUSTOM_FIELDSET_NAME = 'risky_product_set';

    private const array CUSTOM_FIELDSET = [
        'name' => self::CUSTOM_FIELDSET_NAME,
        'config' => [
            'label' => [
                'en-GB' => 'Risky product',
                'pl-PL' => 'Niebezpieczny produkt',
                Defaults::LANGUAGE_SYSTEM => 'Risky Product',
            ]
        ],
        'customFields' => [
            [
                'name' => 'is_risky',
                'type' => CustomFieldTypes::BOOL,
                'config' => [
                    'label' => [
                        'en-GB' => 'Is product risky',
                        'pl-PL' => 'Czy produkt jest ryzykowny?',
                        Defaults::LANGUAGE_SYSTEM => 'Is product risky',
                    ],
                    'customFieldPosition' => 1
                ]
            ]
        ]
    ];

    public function __construct(
        private EntityRepository $customFieldSetRepository,
        private EntityRepository $customFieldSetRelationRepository
    ) {
    }

    public function install(Context $context): void
    {
        $this->customFieldSetRepository->upsert([
            self::CUSTOM_FIELDSET
        ], $context);
    }

    public function addRelations(Context $context): void
    {
        $this->customFieldSetRelationRepository->upsert(array_map(fn(string $customFieldSetId) => [
            'customFieldSetId' => $customFieldSetId,
            'entityName' => 'product',
        ], $this->getCustomFieldSetIds($context)), $context);
    }

    /**
     * @return string[]
     */
    private function getCustomFieldSetIds(Context $context): array
    {
        $criteria = new Criteria();

        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELDSET_NAME));

        return $this->customFieldSetRepository->searchIds($criteria, $context)->getIds();
    }
}
