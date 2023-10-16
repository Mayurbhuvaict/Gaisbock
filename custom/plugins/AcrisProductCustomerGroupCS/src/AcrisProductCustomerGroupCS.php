<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Shopware\Core\System\Snippet\SnippetEntity;

class AcrisProductCustomerGroupCS extends Plugin
{
    const CUSTOM_FIELD_SET_NAME_PRODUCT_CUSTOMER_GROUP = 'acris_product_customer_group';
    const CUSTOM_FIELD_NAME_PRODUCT_EXCLUDE_FROM_SITEMAP = 'acris_product_customer_group_exclude_sitemap';

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);
        $this->removeTableAndFields($connection);
        $this->removeCustomFields($context->getContext(), [self::CUSTOM_FIELD_SET_NAME_PRODUCT_CUSTOMER_GROUP]);
    }

    public function install(InstallContext $context): void
    {
        $this->addCustomFields($context->getContext());
    }

    public function update(UpdateContext $context): void
    {
        $this->addCustomFields($context->getContext());
    }

    public function removeTableAndFields(Connection $connection)
    {
        $connection->executeStatement('DROP TABLE IF EXISTS `acris_product_customer_group`');
        $connection->executeStatement('ALTER TABLE `product` DROP COLUMN `acrisBlockCustomerGroup`');
        $connection->executeStatement('ALTER TABLE `customer_group` DROP COLUMN `acrisBlockProduct`');

        try {
            $connection->executeStatement('ALTER TABLE `product` DROP COLUMN `customerGroup`');
            $connection->executeStatement('ALTER TABLE `customer_group` DROP COLUMN `product`;');
        } catch (\Throwable $e) {}
    }

    private function addCustomFields(Context $context): void
    {
        /* Check for snippets if they exist for custom fields */
        $this->checkForExistingCustomFieldSnippets($context);

        $customFieldSet = $this->container->get('custom_field_set.repository');
        if($customFieldSet->search((new Criteria())->addFilter(new EqualsFilter('name', self::CUSTOM_FIELD_SET_NAME_PRODUCT_CUSTOMER_GROUP)), $context)->count() == 0) {
            $customFieldSet->create([[
                'name' => self::CUSTOM_FIELD_SET_NAME_PRODUCT_CUSTOMER_GROUP,
                'config' => [
                    'label' => [
                        'en-GB' => 'Product customer group',
                        'de-DE' => 'Produkt-Kundengruppe'
                    ]
                ],
                'customFields' => [
                    ['name' => self::CUSTOM_FIELD_NAME_PRODUCT_EXCLUDE_FROM_SITEMAP, 'type' => CustomFieldTypes::BOOL,
                        'config' => [
                            'componentName' => 'sw-field',
                            'type' => 'checkbox',
                            'customFieldType' => 'checkbox',
                            'customFieldPosition' => 1,
                            'label' => [
                                'en-GB' => 'Exclude from sitemap',
                                'de-DE' => 'Von Sitemap ausnehmen'
                            ]
                        ]]
                ],
            ]], $context);
        }
    }

    private function removeCustomFields(Context $context, array $setNames): void
    {
        /* Check for snippets if they exist for custom fields */
        $this->checkForExistingCustomFieldSnippets($context);

        $customFieldSet = $this->container->get('custom_field_set.repository');
        foreach ($setNames as $setName) {
            $id = $customFieldSet->searchIds((new Criteria())->addFilter(new EqualsFilter('name', $setName)), $context)->firstId();
            if($id) $customFieldSet->delete([['id' => $id]], $context);
        }
    }

    private function checkForExistingCustomFieldSnippets(Context $context)
    {
        /** @var EntityRepository $snippetRepository */
        $snippetRepository = $this->container->get('snippet.repository');

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('translationKey', 'customFields.' . self::CUSTOM_FIELD_NAME_PRODUCT_EXCLUDE_FROM_SITEMAP));

        /** @var EntitySearchResult $searchResult */
        $searchResult = $snippetRepository->search($criteria, $context);

        if ($searchResult->count() > 0) {
            $snippetIds = [];
            /** @var SnippetEntity $snippet */
            foreach ($searchResult->getEntities()->getElements() as $snippet) {
                $snippetIds[] = [
                    'id' => $snippet->getId()
                ];
            }

            if (!empty($snippetIds)) {
                $snippetRepository->delete($snippetIds, $context);
            }
        }
    }
}
