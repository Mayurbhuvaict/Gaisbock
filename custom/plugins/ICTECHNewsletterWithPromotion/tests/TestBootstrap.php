<?php declare(strict_types=1);

use Shopware\Core\TestBootstrapper;

$loader = (new TestBootstrapper())
    ->addCallingPlugin()
    ->addActivePlugins('ICTECHNewsletterWithPromotion')
    ->bootstrap()
    ->getClassLoader();

$loader->addPsr4('ICTECHNewsletterWithPromotion\\Tests\\', __DIR__);