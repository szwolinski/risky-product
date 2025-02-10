<?php declare(strict_types=1);

namespace RiskyPlugin;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use RiskyPlugin\Service\CustomFieldsInstaller;

class RiskyProduct extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        $this->getCustomFieldsInstaller()->install($installContext->getContext());
    }

    public function activate(ActivateContext $activateContext): void
    {
        $this->getCustomFieldsInstaller()->addRelations($activateContext->getContext());
    }

    private function getCustomFieldsInstaller(): CustomFieldsInstaller
    {
        if ($this->container->has(CustomFieldsInstaller::class)) {
            return $this->container->get(CustomFieldsInstaller::class);
        }

        return new CustomFieldsInstaller(
            $this->container->get('custom_field_set.repository'),
            $this->container->get('custom_field_set_relation.repository')
        );
    }
}
