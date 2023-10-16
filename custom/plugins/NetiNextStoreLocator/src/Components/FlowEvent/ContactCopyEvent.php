<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\FlowEvent;

/**
 * @psalm-suppress DeprecatedInterface
 */
class ContactCopyEvent extends ContactEvent
{
    final public const EVENT_NAME = 'neti.store_locator.contact_copy';

    public function getName(): string
    {
        return self::EVENT_NAME;
    }
}
