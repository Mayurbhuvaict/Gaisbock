<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Store;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\Aggregate\StoreTag\StoreTagDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\Aggregate\StoreTranslation\StoreTranslationDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\StoreCms\StoreCmsDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\StoreSalesChannel\StoreSalesChannelDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateDefinition;
use Shopware\Core\System\Country\CountryDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Shopware\Core\System\Tag\TagDefinition;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StoreDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neti_store_locator';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return StoreCollection::class;
    }

    public function getEntityClass(): string
    {
        return StoreEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new StringField('label', 'label'))->addFlags(
                    new Required(),
                    new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)
                ),
                (new StringField('street', 'street'))->addFlags(
                    new Required(),
                    new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)
                ),
                (new StringField('street_number', 'streetNumber'))->addFlags(
                    new SearchRanking(SearchRanking::LOW_SEARCH_RANKING)
                ),
                (new StringField('zip_code', 'zipCode'))->addFlags(
                    new Required(),
                    new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)
                ),
                (new StringField('city', 'city'))->addFlags(
                    new Required(),
                    new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)
                ),
                (new FloatField('longitude', 'longitude')),
                (new FloatField('latitude', 'latitude')),
                (new StringField('timezone', 'timezone')),

                (new StringField('phone', 'phone'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
                (new StringField('fax', 'fax'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
                (new StringField('url', 'url'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
                (new StringField('email', 'email'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),

                (new FkField('country_id', 'countryId', CountryDefinition::class))->addFlags(new Required()),
                (new ManyToOneAssociationField('country', 'country_id', CountryDefinition::class)),

                new FkField('country_state_id', 'countryStateId', CountryStateDefinition::class),
                new ManyToOneAssociationField('countryState', 'country_state_id', CountryStateDefinition::class, 'id', false),

                (new BoolField('active', 'active')),
                (new BoolField('contact_form_enabled', 'contactFormEnabled')),
                (new BoolField('contact_form_detail', 'contactFormDetail')),
                (new BoolField('hidden', 'hidden')),
                (new StringField('notification_email', 'notificationMailAddress'))->addFlags(
                    new SearchRanking(SearchRanking::LOW_SEARCH_RANKING)
                ),
                (new StringField('show_always', 'showAlways')),
                (new IntField('zoom', 'zoom')),
                (new BoolField('exclude_from_sync', 'excludeFromSync')),
                (new StringField('google_place_id', 'googlePlaceID'))->addFlags(
                    new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)
                ),
                (new BoolField('featured', 'featured')),
                (new IntField('radius', 'radius')),

                (new BoolField('detail_page_enabled', 'detailPageEnabled')),
                (new StringField('detail_content_type', 'detailContentType')),

                // Associations
                new ManyToManyAssociationField(
                    'salesChannels',
                    SalesChannelDefinition::class,
                    StoreSalesChannelDefinition::class,
                    'store_id',
                    'sales_channel_id'
                ),

                (new FkField('picture_media_id', 'pictureMediaId', MediaDefinition::class)),
                new ManyToOneAssociationField('pictureMedia', 'picture_media_id', MediaDefinition::class, 'id', false),

                (new FkField('details_picture_media_id', 'detailsPictureMediaId', MediaDefinition::class)),
                new ManyToOneAssociationField('detailsPictureMedia', 'details_picture_media_id', MediaDefinition::class, 'id', false),

                (new FkField('icon_media_id', 'iconMediaId', MediaDefinition::class)),
                new ManyToOneAssociationField('iconMedia', 'icon_media_id', MediaDefinition::class, 'id', false),

                new ManyToManyAssociationField(
                    'tags',
                    TagDefinition::class,
                    StoreTagDefinition::class,
                    'store_id',
                    'tag_id'
                ),

                (new OneToManyAssociationField('cmsPages', StoreCmsDefinition::class, 'store_id', 'id'))
                    ->addFlags(new CascadeDelete()),

                new CustomFields(),
                new StringField('external_id', 'externalId'),

                // Translations
                (new TranslatedField('description'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
                (new TranslatedField('additionalInformation'))->addFlags(
                    new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)
                ),

                (new TranslatedField('seoTitle')),
                (new TranslatedField('seoUrl')),
                (new TranslatedField('seoDescription')),
                (new TranslatedField('detailTitle')),
                (new TranslatedField('detailDescription')),
                (new TranslatedField('openingTimes')),

                (new TranslationsAssociationField(
                    StoreTranslationDefinition::class, 'neti_store_locator_id'
                ))->addFlags(new Required()),
            ]
        );
    }
}
