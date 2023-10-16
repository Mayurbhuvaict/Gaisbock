<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\Extension;

use ICTECHNewsletterWithPromotion\Core\Content\NewsletterPopup\NewsletterPopupDefinition;
use Shopware\Core\Checkout\Promotion\PromotionDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class PromotionExtension extends EntityExtension
{
    public function getDefinitionClass(): string
    {
        return PromotionDefinition::class;
    }

    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToOneAssociationField(
                'promotion',
                'id',
                'promotion_id',
                NewsletterPopupDefinition::class,
                false
            )
        );
    }
}