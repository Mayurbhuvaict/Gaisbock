<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ReservedIndividualCode;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ReservedIndividualCodeDefinition extends EntityDefinition {
    final public const ENTITY_NAME = 'neno_nme_reserved_individual_code';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ReservedIndividualCodeCollection::class;
    }

    public function getEntityClass(): string
    {
        return ReservedIndividualCodeEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new IdField('promotion_id', 'promotionId'))->addFlags(new Required()),
            (new IdField('promotion_individual_code_id', 'promotionIndividualCodeId'))->addFlags(new Required()),
        ]);
    }
}
