<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqGroup;

use Huebert\SeoFaq\Core\Content\SeoFaqGroup\Aggregate\SeoFaqGroupTranslation\SeoFaqGroupTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class SeoFaqGroupDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'hueb_seo_faq_group';
    }

    public function getEntityClass(): string
    {
        return SeoFaqGroupEntity::class;
    }

    public function getCollectionClass(): string
    {
        return SeoFaqGroupCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new BoolField('active', 'active')),
            (new IntField('position', 'position')),
            (new StringField('name_old', 'nameOld')),
            new TranslatedField('name'),
            new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class),
            new TranslationsAssociationField(SeoFaqGroupTranslationDefinition::class, 'hueb_seo_faq_group_id')
        ]);
    }
}
