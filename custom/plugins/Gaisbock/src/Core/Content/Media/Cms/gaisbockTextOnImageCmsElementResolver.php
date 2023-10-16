<?php

declare(strict_types=1);

namespace Gaisbock\Core\Content\Media\Cms;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\FieldConfig;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\EntityResolverContext;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\SalesChannel\Struct\ImageStruct;
use Shopware\Core\Content\Cms\SalesChannel\Struct\TextStruct;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Feature;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\Framework\Util\HtmlSanitizer;
use Shopware\Core\Framework\Uuid\Uuid;

class gaisbockTextOnImageCmsElementResolver extends AbstractCmsElementResolver
{

    public function __construct(private readonly HtmlSanitizer $sanitizer)
    {

    }

    public function getType(): string
    {
        return 'gaisbock-custom-text-on-image';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $mediaConfig = $slot->getFieldConfig()->get('media');

        if (
            $mediaConfig === null
            || $mediaConfig->getValue() === null
        ) {
            return null;
        }

        $criteria = new Criteria([$mediaConfig->getStringValue()]);

        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add('media_' . $slot->getUniqueIdentifier(), MediaDefinition::class, $criteria);

        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $data = new ArrayEntity();
        $data->setUniqueIdentifier(Uuid::randomHex());
        $slot->setData($data);
        $config = $slot->getFieldConfig()->get('content');

        if ($config === null) {
            return;
        }

        $content = null;

        if ($config->isStatic()) {
            if ($resolverContext instanceof EntityResolverContext) {
                $content = (string) $this->resolveEntityValues($resolverContext, $config->getStringValue());
            } else {
                $content = $config->getStringValue();
            }
        }

        if ($content !== null) {
                $data->set('content',$content);
        }
        $image = new ImageStruct();
        $mediaConfig = $slot->getFieldConfig()->get('media');
        if ($mediaConfig && $mediaConfig->getValue()) {
            $this->addMediaEntity($slot, $image, $result, $mediaConfig, $resolverContext);
        }
        $data->set('media', $image);
    }

    private function addMediaEntity(
        CmsSlotEntity $slot,
        ImageStruct $image,
        ElementDataCollection $result,
        FieldConfig $config,
        ResolverContext $resolverContext
    ): void {
        if ($config->isStatic()) {
            $image->setMediaId($config->getStringValue());

            $searchResult = $result->get('media_' . $slot->getUniqueIdentifier());
            if (!$searchResult) {
                return;
            }

            $media = $searchResult->get($config->getStringValue());
            if (!$media instanceof MediaEntity) {
                return;
            }
            $image->setMedia($media);
        }
    }
}