<?php

declare(strict_types=1);

namespace HatsLogic\HatsLogicSwStoreSurvey\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Event\StorefrontRenderEvent; 

class ShoppingExperienceSubscriber implements EventSubscriberInterface
{
    /**
     * @var SystemConfigService
     */
    private $systemConfigService; 

    public function __construct(
        SystemConfigService $systemConfigService
    ) {
        $this->systemConfigService = $systemConfigService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            StorefrontRenderEvent::class => 'onStorefrontRender'
        ];
    } 

    public function onStorefrontRender(StorefrontRenderEvent $event)
    {
        $hatsLogicSwStoreSurveyConfig = $this->systemConfigService->get('HatsLogicSwStoreSurvey.config');
        $event->setParameter('hatsLogicSwStoreSurveyConfig', $hatsLogicSwStoreSurveyConfig);
    }
}
