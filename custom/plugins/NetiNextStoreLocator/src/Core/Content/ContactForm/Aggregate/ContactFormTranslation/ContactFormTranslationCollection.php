<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\Aggregate\ContactFormTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class ContactFormTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ContactFormTranslationEntity::class;
    }
}
