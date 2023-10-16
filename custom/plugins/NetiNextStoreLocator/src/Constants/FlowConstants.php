<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Constants;

use NetInventors\NetiNextStoreLocator\Components\FlowEvent\ContactCopyEvent;
use NetInventors\NetiNextStoreLocator\Components\FlowEvent\ContactEvent;

abstract class FlowConstants
{
    final public const EVENTS        = [
        ContactEvent::EVENT_NAME      => [
            MailConstants::MAIL_CONTACT_TECHNICAL_NAME,
        ],
        ContactCopyEvent::EVENT_NAME => [
            MailConstants::MAIL_CONTACT_TECHNICAL_NAME,
        ],
    ];

    final public const EVENT_CLASSES = [
        ContactEvent::class,
        ContactCopyEvent::class,
    ];
}
