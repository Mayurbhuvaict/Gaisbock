<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\ContactForm;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class ContactFormCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ContactFormEntity::class;
    }
}
