<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqQuestions\Aggregate\SeoFaqQuestionsTranslation;

use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\SeoFaqQuestionsDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;

class SeoFaqQuestionsTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'hueb_seo_faq_questions_translation';
    }

    public function getCollectionClass(): string
    {
        return SeoFaqQuestionsTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return SeoFaqQuestionsTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return SeoFaqQuestionsDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new LongTextField('question', 'question'))->addFlags(new AllowHtml()),
            (new LongTextField('answer','answer'))->addFlags(new AllowHtml()),
            (new StringField('meta_title','metaTitle')),
            (new LongTextField('keywords','keywords')),
            (new LongTextField('meta_description','metaDescription')),
        ]);
    }
}
