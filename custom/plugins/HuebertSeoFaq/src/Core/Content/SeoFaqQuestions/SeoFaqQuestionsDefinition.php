<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqQuestions;

use Huebert\SeoFaq\Core\Content\SeoFaqGroup\SeoFaqGroupDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\Aggregate\SeoFaqQuestionsTranslation\SeoFaqQuestionsTranslationDefinition;

class SeoFaqQuestionsDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'hueb_seo_faq_questions';
    }

    public function getEntityClass(): string
    {
        return SeoFaqQuestionsEntity::class;
    }

    public function getCollectionClass(): string
    {
        return SeoFaqQuestionsCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new BoolField('active', 'active')),
            (new IntField('question_position', 'questionPosition')),
            (new IdField('group', 'group')),
            (new StringField('name','name')),
            (new TranslatedField('question')),
            (new TranslatedField('answer')),
            (new TranslatedField('metaTitle')),
            (new TranslatedField('keywords')),
            (new TranslatedField('metaDescription')),
            (new TranslationsAssociationField(SeoFaqQuestionsTranslationDefinition::class, 'hueb_seo_faq_questions_id'))
        ]);
    }
}
