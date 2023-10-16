<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\Extension;

use ICTECHNewsletterWithPromotion\Core\Content\NewsletterPopup\NewsletterPopupDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MediaExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToManyAssociationField(
                'media',
                NewsletterPopupDefinition::class,
                'media_image_id',
                'id'
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return MediaDefinition::class;
    }
}
