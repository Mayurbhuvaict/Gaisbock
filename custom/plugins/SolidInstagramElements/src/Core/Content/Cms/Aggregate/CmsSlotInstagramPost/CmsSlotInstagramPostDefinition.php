<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\Aggregate\CmsSlotInstagramPost;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CmsSlotInstagramPostDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'solid_ie_cms_slot_instagram_post';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return CmsSlotInstagramPostEntity::class;
    }

    public function getCollectionClass(): string
    {
        return CmsSlotInstagramPostCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey(), new ApiAware()),
            (new StringField('user_id', 'userId'))->addFlags(new Required(), new ApiAware()),
            (new StringField('username', 'username'))->addFlags(new Required(), new ApiAware()),
            (new StringField('post_id', 'postId'))->addFlags(new Required(), new ApiAware()),
            (new StringField('caption', 'caption', 16777214))->addFlags(new Required(), new ApiAware()),
            (new StringField('media_type', 'mediaType'))->addFlags(new Required(), new ApiAware()),
            (new StringField('media_url', 'mediaUrl', 65534))->addFlags(new Required(), new ApiAware()),
            (new StringField('permalink', 'permalink', 65534))->addFlags(new Required(), new ApiAware()),
            (new StringField('timestamp', 'timestamp'))->addFlags(new Required(), new ApiAware()),
        ]);
    }
}
