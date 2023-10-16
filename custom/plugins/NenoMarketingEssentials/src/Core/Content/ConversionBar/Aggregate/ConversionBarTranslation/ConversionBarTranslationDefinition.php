<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ConversionBar\Aggregate\ConversionBarTranslation;

use Neno\MarketingEssentials\Core\Content\ConversionBar\ConversionBarDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ConversionBarTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'neno_marketing_essentials_conversion_bar_translation';
    }

    public function getCollectionClass(): string
    {
        return ConversionBarTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return ConversionBarTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return ConversionBarDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('text_01', 'text01')),
            (new StringField('text_01_url', 'text01Url')),
            (new StringField('text_01_primary', 'text01Primary')),
            (new StringField('text_01_primary_url', 'text01PrimaryUrl')),
            (new StringField('text_02', 'text02')),
            (new StringField('text_02_url', 'text02Url')),
            (new StringField('text_02_primary', 'text02Primary')),
            (new StringField('text_02_primary_url', 'text02PrimaryUrl')),
            (new StringField('text_03', 'text03')),
            (new StringField('text_03_url', 'text03Url')),
            (new StringField('text_03_primary', 'text03Primary')),
            (new StringField('text_03_primary_url', 'text03PrimaryUrl')),
        ]);
    }
}
