<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\Tabs;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Neno\MarketingEssentials\Core\Content\Tabs\Aggregate\TabsTranslation\TabsTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Content\Media\MediaDefinition;

class TabsDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neno_marketing_essentials_tabs';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return TabsCollection::class;
    }

    public function getEntityClass(): string
    {
        return TabsEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new BoolField('is_global', 'isGlobal')),
            (new StringField('display', 'display')),
            (new IdField('category_id', 'categoryId')),
            (new IdField('product_id', 'productId')),

            // translatable fields
            new TranslatedField('name'),
            new TranslatedField('text'),
            new TranslationsAssociationField(TabsTranslationDefinition::class, 'neno_marketing_essentials_tabs_id'),

            new FkField('favicon_id', 'faviconId', MediaDefinition::class),
            new ManyToOneAssociationField('mediaImage', 'favicon_id', MediaDefinition::class, 'id', true),
        ]);
    }
}
