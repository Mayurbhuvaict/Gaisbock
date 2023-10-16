<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\Mail;

use NetInventors\NetiNextStoreLocator\Constants\MailConstants;

class Mails
{
    final public const CONTACT = [
        'name'              => MailConstants::MAIL_CONTACT_NAME,
        'technicalName'     => MailConstants::MAIL_CONTACT_TECHNICAL_NAME,
        'description'       => [
            'de-DE' => 'NetiStoreLocator: Kontaktanfrage',
            'en-GB' => 'NetiStoreLocator: Contact request',
        ],
        'subject'           => [
            'de-DE' => 'Kontaktanfrage',
            'en-GB' => 'Contact request',
        ],
        'availableEntities' => [
            'store'  => 'store',
            'values' => 'values',
        ],
        'senderName'        => '{{ shopName }}',
    ];
}
