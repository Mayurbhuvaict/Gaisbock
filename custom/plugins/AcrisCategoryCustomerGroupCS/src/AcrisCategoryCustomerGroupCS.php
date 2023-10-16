<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Shopware\Core\System\Snippet\SnippetEntity;

class AcrisCategoryCustomerGroupCS extends Plugin
{
    const CUSTOM_FIELD_SET_NAME_CATEGORY_CUSTOMER_GROUP = 'acris_category_customer_group';

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);
        $this->removeTableAndFields($connection);
        $this->removeCustomFields($context->getContext(), [self::CUSTOM_FIELD_SET_NAME_CATEGORY_CUSTOMER_GROUP]);
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
        $connection->executeUpdate('DROP TABLE IF EXISTS `acris_category_customer_group`');
        $connection->executeUpdate('ALTER TABLE `category` DROP COLUMN `customerGroup`');

        $connection->executeUpdate('ALTER TABLE `customer_group` DROP COLUMN `category`');

    }

    private function addCustomFields(Context $context): void
    {
        /* Check for snippets if they exist for custom fields */
        $this->checkForExistingCustomFieldSnippets($context);

        $customFieldSet = $this->container->get('custom_field_set.repository');
        if($customFieldSet->search((new Criteria())->addFilter(new EqualsFilter('name', self::CUSTOM_FIELD_SET_NAME_CATEGORY_CUSTOMER_GROUP)), $context)->count() == 0) {
            $customFieldSet->create([[
                'name' => self::CUSTOM_FIELD_SET_NAME_CATEGORY_CUSTOMER_GROUP,
                'config' => [
                    'label' => [
                        'en-GB' => 'Category customer group',
                        'de-DE' => 'Category customer group'
                    ]
                ],
                'customFields' => [
                    ['name' => 'acris_category_customer_group_exclude_sitemap', 'type' => CustomFieldTypes::BOOL,
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
        $criteria->addFilter(new MultiFilter(MultiFilter::CONNECTION_OR, [
            new EqualsFilter('translationKey', 'customFields.' . 'acris_category_customer_group_exclude_sitemap')
        ]));

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
