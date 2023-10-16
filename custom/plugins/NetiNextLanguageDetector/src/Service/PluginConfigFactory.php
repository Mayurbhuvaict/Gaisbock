<?php

declare(strict_types=1);

namespace NetInventors\NetiNextLanguageDetector\Service;

use Doctrine\DBAL\Exception;
use NetInventors\NetiNextLanguageDetector\Serializer\Normalizer\PluginConfigDenormalizer;
use NetInventors\NetiNextLanguageDetector\Struct\PluginConfigStruct;
use Shopware\Core\PlatformRequest;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class PluginConfigFactory
{
    final public const CONFIG_DOMAIN = 'NetiNextLanguageDetector.config';

    private readonly Serializer $serializer;

    /**
     * @var array<string, PluginConfigStruct>
     */
    private array $configs = [];

    /**
     * @throws ExceptionInterface
     */
    public function __construct(
        private readonly SystemConfigService $systemConfig,
        private readonly RequestStack        $requestStack
    ) {
        $this->serializer = new Serializer([ new PluginConfigDenormalizer() ]);

        try {
            $this->create();
        } catch (Exception) {
            // kernel booted without database connection

            return;
        }
    }

    /**
     * @throws ExceptionInterface
     */
    public function create(string $salesChannelId = null): PluginConfigStruct
    {
        $salesChannelId ??= $this->getDefaultSalesChannelId();
        $cacheKey       = $salesChannelId ?? 'null';

        if (isset($this->configs[$cacheKey])) {
            return $this->configs[$cacheKey];
        }

        /** @var PluginConfigStruct $denormalizedObject */
        $denormalizedObject = $this->serializer->denormalize(
            (array) $this->systemConfig->get(self::CONFIG_DOMAIN, $salesChannelId),
            PluginConfigStruct::class
        );

        $this->configs[$cacheKey] = $denormalizedObject;

        return $denormalizedObject;
    }

    private function getDefaultSalesChannelId(): ?string
    {
        // Create new request if no exists (e.g. by console commands)
        $request        = $this->requestStack->getCurrentRequest() ?? new Request();
        $salesChannelId = $request->attributes->get(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_ID);

        if ($salesChannelId !== null) {
            return (string) $salesChannelId;
        }

        return null;
    }
}
