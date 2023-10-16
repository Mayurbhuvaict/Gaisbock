<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ConversionBar;

use Neno\MarketingEssentials\Core\Content\ConversionBar\Aggregate\ConversionBarTranslation\ConversionBarTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;

class ConversionBarDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neno_marketing_essentials_conversion_bar';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ConversionBarCollection::class;
    }

    public function getEntityClass(): string
    {
        return ConversionBarEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new IdField('sales_channel_id', 'salesChannelId')),

            (new BoolField('active', 'active')),
            (new IntField('slider_max_width', 'sliderMaxWidth')),
            (new StringField('background_color', 'backgroundColor')),
            (new StringField('text_color', 'textColor')),
            (new StringField('link_color', 'linkColor')),

            (new BoolField('text_01_clickable', 'text01Clickable')),
            (new BoolField('text_01_primary_active', 'text01PrimaryActive')),
            (new BoolField('text_02_clickable', 'text02Clickable')),
            (new BoolField('text_02_primary_active', 'text02PrimaryActive')),
            (new BoolField('text_03_clickable', 'text03Clickable')),
            (new BoolField('text_03_primary_active', 'text03PrimaryActive')),

            // translatable fields
            new TranslatedField('text01'),
            new TranslatedField('text01Url'),
            new TranslatedField('text01Primary'),
            new TranslatedField('text01PrimaryUrl'),
            new TranslatedField('text02'),
            new TranslatedField('text02Url'),
            new TranslatedField('text02Primary'),
            new TranslatedField('text02PrimaryUrl'),
            new TranslatedField('text03'),
            new TranslatedField('text03Url'),
            new TranslatedField('text03Primary'),
            new TranslatedField('text03PrimaryUrl'),

            new FkField('text_01_media_id', 'text01MediaId', MediaDefinition::class),
            new ManyToOneAssociationField('text01Media', 'text_01_media_id', MediaDefinition::class, 'id', true),

            new FkField('text_02_media_id', 'text02MediaId', MediaDefinition::class),
            new ManyToOneAssociationField('text02Media', 'text_02_media_id', MediaDefinition::class, 'id', true),

            new FkField('text_03_media_id', 'text03MediaId', MediaDefinition::class),
            new ManyToOneAssociationField('text03Media', 'text_03_media_id', MediaDefinition::class, 'id', true),

            (new TranslationsAssociationField(
                ConversionBarTranslationDefinition::class,
                'neno_marketing_essentials_conversion_bar_id'
            ))->addFlags(new Required()),
        ]);
    }
}
