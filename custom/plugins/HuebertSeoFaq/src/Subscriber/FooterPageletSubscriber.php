<?php

namespace Huebert\SeoFaq\Subscriber;

use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class FooterPageletSubscriber implements EventSubscriberInterface
{

    /*
     * @var SystemConfigService
     */
    private $systemConfig;

    // add the `SystemConfigService` to your constructor
    public function __construct(SystemConfigService $systemConfig)
    {
        $this->systemConfig = $systemConfig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FooterPageletLoadedEvent::class => 'onPageLoaded'
        ];
    }

    public function onPageLoaded(FooterPageletLoadedEvent $pageLoadedEvent) {
        //assign HuebertSeoFaq config keys
        $config = $this->systemConfig->get('HuebertSeoFaq.config', $pageLoadedEvent->getSalesChannelContext()->getSalesChannel()->getId());
        if($config) {$pageLoadedEvent->getPagelet()->assign(['HuebertSeoFaq' => ['config' => $config]]);}
    }
}
