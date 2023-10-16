<?php declare(strict_types=1);

namespace Pluszwei\FaqManager;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;

class PluszweiFaqManager extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
    }

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }
        // uninstall default values
        $this->deleteSeoUrlTemplate('pluszwei_faq_article', $context->getContext());
        $this->deleteSeoUrlTemplate('pluszwei_faq_category', $context->getContext());

        // drop tables
        $connection = $this->container->get(Connection::class);

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->executeStatement('DROP TABLE IF EXISTS `pluszwei_faq_article`');
        $connection->executeStatement('DROP TABLE IF EXISTS `pluszwei_faq_article_translation`');
        $connection->executeStatement('DROP TABLE IF EXISTS `pluszwei_faq_article_sales_channel`');
        $connection->executeStatement('DROP TABLE IF EXISTS `pluszwei_faq_category`');
        $connection->executeStatement('DROP TABLE IF EXISTS `pluszwei_faq_category_translation`');
        $connection->executeStatement('DROP TABLE IF EXISTS `pluszwei_faq_category_sales_channel`');

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function update(UpdateContext $updateContext): void
    {
        parent::update($updateContext);

//        if (version_compare($updateContext->getCurrentPluginVersion(), '1.0.0', '<')) {
//            // do something
//        }
    }

    private function deleteSeoUrlTemplate($entityName, Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('entityName', $entityName)
        );

        /** @var EntityRepositoryInterface $seoUrlTemplateRepository */
        $seoUrlTemplateRepository = $this->container->get('seo_url_template.repository');

        $seoUrlTemplateRepository->search($criteria, $context);

        $seoUrlTemplateIds = $seoUrlTemplateRepository->searchIds($criteria, $context)->getIds();

        if (!empty($seoUrlTemplateIds)) {
            $ids = array_map(static function ($id) {
                return ['id' => $id];
            }, $seoUrlTemplateIds);
            $seoUrlTemplateRepository->delete($ids, $context);
        }
    }
}