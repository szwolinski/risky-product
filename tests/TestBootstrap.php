<?php declare(strict_types=1);

use Shopware\Core\TestBootstrapper;

$loader = (new TestBootstrapper())
    ->addCallingPlugin()
    ->addActivePlugins('RiskyProduct')
    ->setForceInstallPlugins(false)
    ->bootstrap()
    ->getClassLoader();

$loader->addPsr4('RiskyPlugin\\Tests\\', __DIR__);
