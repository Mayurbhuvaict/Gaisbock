<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\ReservedIndividualPromotionCode;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ReservedIndividualPromotionCodeDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'reserved_individual_promotion_code';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ReservedIndividualPromotionCodeCollection::class;
    }

    public function getEntityClass(): string
    {
        return ReservedIndividualPromotionCodeEntity::class;
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
