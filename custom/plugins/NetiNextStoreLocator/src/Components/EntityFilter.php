<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components;

use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;

class EntityFilter
{
    final public const AFFECTED_KEYS = [
        'street',
        'streetNumber',
        'zipCode',
        'city',
        'countryId',
    ];

    public function getAffectedIDs(EntityWrittenEvent $event): array
    {
        $ids = [];

        // The payload only contains attributes which were changed so we need to check for the related existence
        /** @var array $payload */
        foreach ($event->getPayloads() as $payload) {
            if (false === array_key_exists('id', $payload)) {
                continue;
            }

            /** @var string $id */
            $id       = $payload['id'];
            $affected = false;

            /**
             * @var string $key
             * @var mixed  $_
             */
            foreach ($payload as $key => $_) {
                if (in_array($key, self::AFFECTED_KEYS, true)) {
                    $affected = true;
                    break 1;
                }
            }

            if ($affected) {
                $ids[] = $id;
            }
        }

        // Check for new inserts
        foreach ($event->getExistences() as $existence) {
            if (!$existence->exists()) {
                $ids[] = (string) $existence->getPrimaryKey()['id'];
            }
        }

        // We need to filter duplicates which are generated on new inserts
        $ids = array_unique($ids);

        return $ids;
    }
}
