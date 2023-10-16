<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Elasticsearch\Product;

use OpenSearchDSL\Query\Compound\BoolQuery;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Log\Package;
use Shopware\Elasticsearch\Framework\AbstractElasticsearchDefinition;
use Shopware\Elasticsearch\Product\ElasticsearchProductDefinition;

#[Package('core')]
class ElasticsearchProductDefinitionDecorated extends ElasticsearchProductDefinition
{
    private AbstractElasticsearchDefinition $parent;

    public function __construct(AbstractElasticsearchDefinition $parent)
    {
        $this->parent = $parent;
    }

    public function getEntityDefinition(): EntityDefinition
    {
        return $this->parent->getEntityDefinition();
    }

    public function getMapping(Context $context): array
    {
        $mapping = $this->parent->getMapping($context);

        return $this->assignBlockCategoryCustomerGroupMapping($mapping);
    }

    public function extendDocuments(array $documents, Context $context): array
    {
        return $this->parent->extendDocuments($documents, $context);
    }

    public function buildTermQuery(Context $context, Criteria $criteria): BoolQuery
    {
        return $this->parent->buildTermQuery($context, $criteria);
    }

    public function fetch(array $ids, Context $context): array
    {
        return $this->parent->fetch($ids, $context);
    }

    private function assignBlockCategoryCustomerGroupMapping(array $mapping): array
    {
        if (!array_key_exists('properties', $mapping) || !is_array($mapping['properties']) || !array_key_exists('categories', $mapping['properties']) || !is_array($mapping['properties']['categories']) || !array_key_exists('properties', $mapping['properties']['categories']) || !is_array($mapping['properties']['categories']['properties'])) {
            return $mapping;
        }

        $mapping['properties']['categories']['properties']['customerGroup'] = [
            'type' => 'nested',
            'properties' => [
                'id' => ElasticsearchProductDefinition::KEYWORD_FIELD,
                'name' => ElasticsearchProductDefinition::KEYWORD_FIELD + self::SEARCH_FIELD,
                '_count' => ElasticsearchProductDefinition::INT_FIELD,
            ],
        ];

        return $mapping;
    }
}
