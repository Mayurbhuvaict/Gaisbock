<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Service;

use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin\PluginEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class StorefrontConfigFactory
{
    public function __construct(
        private readonly PluginConfigFactory $pluginConfigFactory,
        private readonly EntityRepository    $mediaRepository,
        private readonly EntityRepository    $pluginRepository,
        private readonly SystemConfigService $systemConfig
    ) {
    }

    public static function getContactSubjectOptions(array $config): array
    {
        $options = (string) ($config['contactSubjectOptions'] ?? '');
        $options = trim($options);

        if (empty($options)) {
            return [];
        }

        return explode(PHP_EOL, $options);
    }

    public function getConfig(SalesChannelContext $context): array
    {
        $config = $this->pluginConfigFactory->getConfig($context->getSalesChannelId());

        /**
         * @var string|int|bool|float $value
         */
        foreach ($config as $key => $value) {
            unset($config[$key]);

            switch ($key) {
                case 'googleMapIcon':
                    $mediaId = (string) $value;

                    if (!empty($mediaId)) {
                        $criteria = new Criteria();
                        $criteria->addFilter(new EqualsFilter('id', $mediaId));

                        $result = $this->mediaRepository->search($criteria, $context->getContext());
                        /** @var MediaEntity|null $media */
                        $media = $result->first();

                        if ($media instanceof MediaEntity) {
                            $value = $media->getUrl();
                        } else {
                            throw new \Exception('The given mediaId does not exist.');
                        }
                    }
                    break;
                case 'googleMapIconSize':
                    if (!empty($value)) {
                        if (!preg_match('/^([0-9]+)x([0-9]+)$/', (string) $value)) {
                            throw new \Exception('The given icon size "' . $value . '" is invalid.');
                        }

                        [ $width, $height ] = array_map('intval', explode('x', (string) $value));
                        $value              = compact('width', 'height');
                    }
                    break;
            }

            $config[$key] = $value;
        }

        $config['_storePickupEnabled']   = $this->isStorePickupEnabled($context->getContext());
        $config['_cookieConsentEnabled'] = $this->systemConfig->get('core.basicInformation.useDefaultCookieConsent');

        // Remove api keys from public accessible config
        unset($config['googleWorkApiKey']);

        return $config;
    }

    private function isStorePickupEnabled(Context $context): bool
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'NetiNextStorePickup'));

        $result = $this->pluginRepository->search($criteria, $context);
        /** @var PluginEntity|null $plugin */
        $plugin = $result->first();

        return $plugin instanceof PluginEntity
            && $plugin->getInstalledAt() !== null
            && $plugin->getActive() === true;
    }
}
