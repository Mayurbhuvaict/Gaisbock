<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components;

use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CmsPageRenderer
{
    public function __construct(
        private readonly SalesChannelCmsPageLoaderInterface $cmsPageLoader,
        private readonly Environment $twig,
        private readonly EntityRepository $cmsPageRepository
    ) {
    }

    /**
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function buildById(
        Request             $request,
        SalesChannelContext $salesChannelContext,
        string              $cmsPageId,
        array               $twigContext = []
    ): string {
        $criteria = new Criteria([ $cmsPageId ]);
        $result   = $this->cmsPageRepository->search($criteria, $salesChannelContext->getContext());

        /** @var CmsPageEntity|null $cmsPage */
        $cmsPage  = $result->first();

        if ($cmsPage instanceof CmsPageEntity) {
            return $this->build($request, $salesChannelContext, $cmsPage, $twigContext);
        }

        throw new \RuntimeException('The given cmsPageId was not found.');
    }

    /**
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(
        Request $request,
        SalesChannelContext $context,
        CmsPageEntity $cmsPage,
        array $twigContext = []
    ): string {
        $pages = $this->cmsPageLoader->load(
            $request,
            new Criteria([ $cmsPage->getId() ]),
            $context
        );

        return $this->twig->render(
            '@Storefront/storefront/page/content/detail.html.twig',
            array_replace_recursive(
                [
                    'cmsPage' => $pages->first(),
                ],
                $twigContext
            )
        );
    }
}
