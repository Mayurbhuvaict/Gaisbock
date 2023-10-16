<?php

declare(strict_types=1);

namespace NetInventors\NetiNextLanguageDetector\Storefront\Controller;

use NetInventors\NetiNextLanguageDetector\Service\LanguageDetectorService;
use NetInventors\NetiNextLanguageDetector\Service\LogService;
use NetInventors\NetiNextLanguageDetector\Service\PluginConfigFactory;
use Shopware\Core\Framework\Adapter\Translation\Translator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\Locale\LocaleEntity;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor Set up by parent */
#[Route(defaults: ['_routeScope' => ['storefront']])]
class LanguageDetectorController extends StorefrontController
{
    public function __construct(
        private readonly EntityRepository        $domainRepository,
        private readonly PluginConfigFactory     $config,
        private readonly LanguageDetectorService $languageDetectorService,
        private readonly LogService              $logger,
        private readonly Translator              $translator
    ) {
    }

    #[Route(path: '/LanguageDetector/checkLanguage', name: 'frontend.language_detector.check_language', defaults: [ 'XmlHttpRequest' => true], methods: ['GET'])]
    public function checkLanguage(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        $pluginConfig = $this->config->create($salesChannelContext->getSalesChannelId());
        /** @var array<string, mixed>|null $data */
        $data         = $this->getLanguageSwitchData($request, $salesChannelContext);

        if (null !== $data) {
            return new JsonResponse(
                [
                    'success'   => true,
                    'data'      => $data,
                    'setCookie' => !in_array(
                        $request->getClientIp(),
                        explode(',', $pluginConfig->getNoCookieIps()),
                        true
                    ),
                    'html'      => $this->renderStorefront(
                        '@Storefront/storefront/component/neti-language-detector/pseudo-modal.html.twig',
                        $data
                    )->getContent(),
                ]
            );
        }

        return new JsonResponse(
            [
                'success' => false, // do nothing
            ]
        );
    }

    #[Route(path: '/LanguageDetector/redirect/{domainId}/{route}', name: 'frontend.language_detector.redirect', requirements: ['route' => '.+'], methods: ['GET'])]
    public function redirectCustomer(
        string              $domainId,
        string              $route,
        Request             $request,
        SalesChannelContext $context
    ): Response {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $domainId));

        $domain = $this->domainRepository->search($criteria, $context->getContext())->first();

        if (!$domain instanceof SalesChannelDomainEntity) {
            return new Response();
        }

        $routeAvailable = false;

        if (str_contains($route, '/detail/')) {
            $detailId = str_replace('/detail/', '', $route);

            if (Uuid::isValid($detailId)) {
                $routeAvailable = $this->languageDetectorService->isDetailVisibleInSalesChannel(
                    $detailId,
                    $domain->getSalesChannelId(),
                    $context->getContext()
                );
            }
        }

        if ($routeAvailable) {
            $response = $this->redirect($domain->getUrl() . $route);
        } else {
            $response = $this->redirect($domain->getUrl());
        }

        return $response;
    }

    private function getLanguageSwitchData(Request $request, SalesChannelContext $salesChannelContext): ?array
    {
        $pluginConfig   = $this->config->create($salesChannelContext->getSalesChannelId());
        $context        = $salesChannelContext->getContext();
        $acceptLanguage = $request->headers->get('Accept-Language');
        $debugIPs       = \explode(',', $pluginConfig->getLogAddresses());

        if (!\is_string($acceptLanguage)) {
            $this->addLogEntry(
                $request,
                'Request header "Accept-Language" is missing or invalid',
                [
                    'acceptLanguage' => $acceptLanguage,
                ],
                $debugIPs
            );

            return null;
        }

        /** @var empty|string[] $userLocales */
        $userLocales = \explode(',', $acceptLanguage);

        if ([] === $userLocales) {
            $this->addLogEntry(
                $request,
                'Request header "Accept-Language" is invalid',
                [
                    'acceptLanguage' => $acceptLanguage,
                ],
                $debugIPs
            );

            return null;
        }

        $userLocale = $userLocales[0];

        if (!$pluginConfig->isActive()) {
            $this->addLogEntry(
                $request,
                'Plugin not active',
                [],
                $debugIPs
            );

            return null;
        }

        $currentLanguage = $this->languageDetectorService->getLanguageById(
            $salesChannelContext->getLanguageId(),
            $context
        );

        if (!$currentLanguage instanceof LanguageEntity) {
            return null;
        }

        $currentLocale = $currentLanguage->getLocale();
        if (!$currentLocale instanceof LocaleEntity) {
            return null;
        }

        $locale = $currentLocale->getCode();

        if ($locale === $userLocale) {
            $this->addLogEntry(
                $request,
                'Shop locale is User-Locale',
                [
                    'shopLocale' => $locale,
                    'userLocale' => $userLocale,
                ],
                $debugIPs
            );

            return null;
        }

        $salesChannelId = $salesChannelContext->getSalesChannelId();

        $domains = $this->languageDetectorService->getSalesChannelsDomains($salesChannelId, $context)->getElements();
        if ($pluginConfig->isAllSalesChannels()) {
            $notSalesChannelsDomains =
                $this->languageDetectorService->getOtherSalesChannelsDomains($domains, $salesChannelId, $context);

            /** @var SalesChannelDomainEntity $notSalesChannelsDomain */
            foreach ($notSalesChannelsDomains as $notSalesChannelsDomain) {
                $domains[$notSalesChannelsDomain->getId()] = $notSalesChannelsDomain;
            }
        }

        if ([] === $domains) {
            $this->addLogEntry(
                $request,
                'No domains found',
                [
                    'shopLocale' => $locale,
                    'userLocale' => $userLocale,
                ],
                $debugIPs
            );

            return null;
        }

        $targetDomain = null;

        /** @var SalesChannelDomainEntity $domain */
        foreach ($domains as $domain) {
            $domainLanguage = $domain->getLanguage();

            if (!$domainLanguage instanceof LanguageEntity) {
                continue;
            }

            $domainLocal = $domainLanguage->getLocale();

            if (!$domainLocal instanceof LocaleEntity) {
                continue;
            }

            $domainLocalCode = $domainLocal->getCode();
            if (\str_starts_with($domainLocalCode, $userLocale)) {
                $targetDomain = $domain;
            }
        }

        $netiLanguageDetector = [];

        if (null === $targetDomain && '' !== $pluginConfig->getDefaultLanguage()) {
            //we want to search for the default language in the active salesChannel first
            $targetDomain =
                $this->languageDetectorService->getDefaultTargetDomain($pluginConfig->getDefaultLanguage(), $context, $salesChannelId);

            if (null === $targetDomain && $pluginConfig->isAllSalesChannels()) {
                $this->languageDetectorService->getDefaultTargetDomain($pluginConfig->getDefaultLanguage(), $context);
            }
        }

        if (!$targetDomain instanceof SalesChannelDomainEntity) {
            $this->addLogEntry(
                $request,
                'No target domain found',
                [
                    'shopLocale' => $locale,
                    'userLocale' => $userLocale,
                ],
                $debugIPs
            );

            return null;
        }

        $targetLanguage = $targetDomain->getLanguage();
        if (!$targetLanguage instanceof LanguageEntity) {
            return null;
        }

        $targetLocale = $targetLanguage->getLocale();
        if (!$targetLocale instanceof LocaleEntity) {
            return null;
        }

        if ($locale === $targetLocale->getCode()) {
            $this->addLogEntry(
                $request,
                'No redirect needed',
                [
                    'shopLocale'   => $locale,
                    'userLocale'   => $userLocale,
                    'targetLocale' => $targetLocale->getCode(),
                ],
                $debugIPs
            );

            return null;
        }

        $netiLanguageDetector['targetLanguage'] = $targetLanguage;

        /** @var SalesChannelDomainEntity $domain */
        foreach ($domains as $domain) {
            $domainLanguage = $domain->getLanguage();
            if (!$domainLanguage instanceof LanguageEntity) {
                continue;
            }

            $domainLocale = $domainLanguage->getLocale();
            if (!$domainLocale instanceof LocaleEntity) {
                continue;
            }

            $foundLanguage = $this->languageDetectorService->getLanguageByLocale(
                $domainLocale->getCode(),
                $context
            );

            if (!$foundLanguage instanceof LanguageEntity) {
                continue;
            }

            $foundLanguageId = $foundLanguage->getId();
            $locale          = $foundLanguage->getLocale();
            if (!$locale instanceof LocaleEntity) {
                continue;
            }

            $localeCode = $locale->getCode();

            $this->translator->injectSettings($domain->getSalesChannelId(), $foundLanguageId, $localeCode, $context);

            $netiLanguageDetector['languages'][$foundLanguageId] = [
                'id'            => $foundLanguageId,
                'route'         => $request->query->get('redirectRoute'),
                'domain'        => $targetDomain->getId(),
                'headline'      => $this->translator->trans('neti-next-language-detector.modal.headline'),
                'text'          => str_replace(
                    '%LANG%',
                    $targetLanguage->getName(),
                    $this->translator->trans('neti-next-language-detector.modal.text')
                ),
                'buttonAccept'  => $this->translator->trans('neti-next-language-detector.modal.buttonAccept'),
                'buttonDecline' => $this->translator->trans('neti-next-language-detector.modal.buttonDecline'),
                'name'          => $foundLanguage->getName(),
                'locale'        => $localeCode,
                'language'      => $foundLanguage,
            ];
        }

        if (!isset($netiLanguageDetector['languages'][$targetLanguage->getId()])) {
            $this->addLogEntry(
                $request,
                'Switching to another sales channel was disabled',
                [
                    'shopLocale' => $locale,
                    'userLocale' => $userLocale,
                ],
                $debugIPs
            );

            return null;
        }

        return $netiLanguageDetector;
    }

    private function addLogEntry(Request $request, string $message, array $parameters = [], array $debugIPs = []): void
    {
        $clientIP = $request->getClientIp();
        if (\in_array($clientIP, $debugIPs, true)) {
            $this->logger->debug(
                $message,
                \array_merge([
                    'IP'    => $clientIP,
                    'class' => __CLASS__,
                ], $parameters)
            );
        }
    }
}
