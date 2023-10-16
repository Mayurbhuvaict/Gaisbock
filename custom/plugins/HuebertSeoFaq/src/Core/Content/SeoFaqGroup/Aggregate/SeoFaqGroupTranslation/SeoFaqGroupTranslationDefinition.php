<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqGroup\Aggregate\SeoFaqGroupTranslation;

use Huebert\SeoFaq\Core\Content\SeoFaqGroup\SeoFaqGroupDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;

class SeoFaqGroupTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'hueb_seo_faq_group_translation';
    }

    public function getCollectionClass(): string
    {
        return SeoFaqGroupTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return SeoFaqGroupTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return SeoFaqGroupDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new LongTextField('name', 'name'))
        ]);
    }
}
