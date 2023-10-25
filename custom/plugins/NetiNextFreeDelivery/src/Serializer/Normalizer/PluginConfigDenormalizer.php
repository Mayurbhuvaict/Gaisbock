<?php

declare(strict_types=1);

namespace NetInventors\NetiNextFreeDelivery\Serializer\Normalizer;

use NetInventors\NetiNextFreeDelivery\Struct\PluginConfigStruct;
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
                if (isset($data['subshopActivated'])) {
                    $denormalizedObject->subshopActivated = (bool) $data['subshopActivated'];
                }

                if (isset($data['displayFrom'])) {
                    $denormalizedObject->displayFrom = (float) $data['displayFrom'];
                }

                if (isset($data['showInArticle'])) {
                    $denormalizedObject->showInArticle = (bool) $data['showInArticle'];
                }

                if (isset($data['articlePosition']) && '' !== $data['articlePosition']) {
                    $denormalizedObject->articlePosition = (string) $data['articlePosition'];
                }

                if (isset($data['showInHeader'])) {
                    $denormalizedObject->showInHeader = (bool) $data['showInHeader'];
                }

                if (isset($data['showInModal'])) {
                    $denormalizedObject->showInModal = (bool) $data['showInModal'];
                }

                if (isset($data['showProgressBar'])) {
                    $denormalizedObject->showProgressBar = (bool) $data['showProgressBar'];
                }

                if (isset($data['hideDisplayForShippingFree'])) {
                    $denormalizedObject->showProgressBar = (bool) $data['hideDisplayForShippingFree'];
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
