<?php

declare(strict_types=1);

namespace NetInventors\NetiNextLanguageDetector\Serializer\Normalizer;

use NetInventors\NetiNextLanguageDetector\Struct\PluginConfigStruct;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use function is_array;

class PluginConfigDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = []): PluginConfigStruct
    {
        if (!is_array($data)) {
            throw new NotNormalizableValueException();
        }

        $denormalizedObject = new PluginConfigStruct();

        $closureFn = \Closure::bind(
            /** @psalm-suppress InaccessibleProperty */
            static function (PluginConfigStruct $denormalizedObject) use ($data): void {
                if (isset($data['active'])) {
                    $denormalizedObject->active = (bool) $data['active'];
                }

                if (isset($data['allSalesChannels'])) {
                    $denormalizedObject->allSalesChannels = (bool) $data['allSalesChannels'];
                }

                if (isset($data['noCookieIps'])) {
                    $denormalizedObject->noCookieIps = (string) $data['noCookieIps'];
                }

                if (isset($data['logAddresses'])) {
                    $denormalizedObject->logAddresses = (string) $data['logAddresses'];
                }

                if (isset($data['defaultLanguage'])) {
                    $denormalizedObject->defaultLanguage = (string) $data['defaultLanguage'];
                }
            },
            null,
            PluginConfigStruct::class,
        );

        if (!$closureFn) {
            throw new RuntimeException();
        }

        $closureFn($denormalizedObject);

        return $denormalizedObject;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return PluginConfigStruct::class === $type;
    }
}
