<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Core\Framework\DataAbstractionLayer\Write;

use Acris\ProductCustomerGroup\Components\TruncateBlockedCustomerGroupService;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteCommandExtractor as ParentClass;

class WriteCommandExtractor extends ParentClass
{
    private ParentClass $parent;

    private TruncateBlockedCustomerGroupService $truncateService;

    public function __construct(
        ParentClass $parent,
        TruncateBlockedCustomerGroupService $truncateService
    ) {
        $this->parent = $parent;
        $this->truncateService = $truncateService;
    }

    public function normalize(EntityDefinition $definition, array $rawData, WriteParameterBag $parameters): array
    {
        return $this->parent->normalize($definition, $rawData, $parameters);
    }

    public function normalizeSingle(EntityDefinition $definition, array $data, WriteParameterBag $parameters): array
    {
        return $this->parent->normalizeSingle($definition, $data, $parameters);
    }

    public function extract(array $rawData, WriteParameterBag $parameters): array
    {
        if (!$this->truncateService->allowTruncate()) return $this->parent->extract($rawData,$parameters);

        $definition = $parameters->getDefinition();

        if ($definition->getEntityName() !== 'product' || empty($rawData) || !array_key_exists('id', $rawData) || empty($rawData['id']) || !array_key_exists('acrisBlockCustomerGroup', $rawData) || empty($rawData['acrisBlockCustomerGroup']) || !is_array($rawData['acrisBlockCustomerGroup'])) return $this->parent->extract($rawData, $parameters);

        $this->truncateService->truncateBlockedCustomerGroups($rawData['id']);

        return $this->parent->extract($rawData,$parameters);
    }

    public function extractJsonUpdate($data, EntityExistence $existence, WriteParameterBag $parameters): void
    {
        $this->parent->extractJsonUpdate($data, $existence, $parameters);
    }
}
