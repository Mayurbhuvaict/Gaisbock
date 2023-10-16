<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\Extension;

use ICTECHNewsletterWithPromotion\Core\Content\NewsletterPopup\Aggregate\NewsletterPopupTranslation\NewsletterPopupTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Language\LanguageDefinition;

class LanguageExtension extends EntityExtension
{
    public function getDefinitionClass(): string
    {
        return LanguageDefinition::class;
    }

    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToManyAssociationField(
                'ictPopupTranId',
                NewsletterPopupTranslationDefinition::class,
                'newsletter_popup_id',
                'id'
            )
        );
    }
}
