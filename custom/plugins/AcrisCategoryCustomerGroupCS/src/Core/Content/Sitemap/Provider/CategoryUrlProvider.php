<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Core\Content\Sitemap\Provider;

use Shopware\Core\Content\Sitemap\Provider\AbstractUrlProvider;
use Shopware\Core\Content\Sitemap\Struct\UrlResult;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CategoryUrlProvider extends \Shopware\Core\Content\Sitemap\Provider\CategoryUrlProvider
{
    private EntityRepository $categoryRepository;

    private AbstractUrlProvider $categoryUrlProvider;

    public function __construct(
        EntityRepository $categoryRepository,
        AbstractUrlProvider $categoryUrlProvider
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryUrlProvider = $categoryUrlProvider;
    }

    public function getDecorated(): AbstractUrlProvider
    {
        return $this->categoryUrlProvider;
    }

    public function getName(): string
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function getUrls(SalesChannelContext $context, int $limit, ?int $offset = null): UrlResult
    {
        $result = $this->categoryUrlProvider->getUrls($context, $limit, $offset);
        $categoryIds = $this->getCategoryIds($context);

        if (empty($categoryIds)) return $result;

        $originalUrls = $result->getUrls();
        $nextOffset = $result->getNextOffset();

        $urls = [];
        foreach ($originalUrls as $url) {
            if (in_array($url->getIdentifier(), $categoryIds)) continue;
            $urls[] = $url;
        }
        return new UrlResult($urls, $nextOffset);
    }

    private function getCategoryIds(SalesChannelContext $salesChannelContext): ?array
    {
        $criteria = new Criteria();
        $criteria->addFilter(New MultiFilter(MultiFilter::CONNECTION_AND, [
            new NotFilter(MultiFilter::CONNECTION_AND, [new EqualsFilter('customFields.acris_category_customer_group_exclude_sitemap', null)]),
            new EqualsFilter('customFields.acris_category_customer_group_exclude_sitemap', true)
        ]));
        $searchIds = $this->categoryRepository->searchIds($criteria, $salesChannelContext->getContext());

        return $searchIds->getIds();
    }
}

