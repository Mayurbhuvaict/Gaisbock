<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Constants;

abstract class MailConstants
{
    final public const MAIL_CONTACT_NAME           = 'NetiStoreLocator_Contact';

    final public const MAIL_CONTACT_TECHNICAL_NAME = 'neti.store_locator.contact';

    /**
     * @return array<string>
     */
    public static function getMailTechnicalNames(): array
    {
        $reflection     = new \ReflectionClass(self::class);
        $constants      = $reflection->getConstants();
        $technicalNames = [];

        /**
         * @var string $constantValue
         */
        foreach ($constants as $constantName => $constantValue) {
            if (\is_int(\mb_strpos($constantName, 'TECHNICAL_NAME'))) {
                $technicalNames[] = $constantValue;
            }
        }

        return $technicalNames;
    }
}
