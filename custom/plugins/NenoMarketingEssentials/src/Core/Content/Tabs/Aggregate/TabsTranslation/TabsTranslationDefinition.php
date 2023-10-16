<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\Tabs\Aggregate\TabsTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Neno\MarketingEssentials\Core\Content\Tabs\TabsDefinition;

class TabsTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'neno_marketing_essentials_tabs_translation';
    }

    public function getCollectionClass(): string
    {
        return TabsTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return TabsTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return TabsDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new LongTextField('text', 'text'))
        ]);
    }
}
