<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Core\Content\Sitemap\Provider;

use Shopware\Core\Content\Sitemap\Provider\AbstractUrlProvider;
use Shopware\Core\Content\Sitemap\Struct\UrlResult;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class ProductUrlProvider extends \Shopware\Core\Content\Sitemap\Provider\ProductUrlProvider
{
    private EntityRepository $productRepository;

    private AbstractUrlProvider $productUrlProvider;

    public function __construct(
        EntityRepository $productRepository,
        AbstractUrlProvider $productUrlProvider
    ) {
        $this->productRepository = $productRepository;
        $this->productUrlProvider = $productUrlProvider;
    }

    public function getDecorated(): AbstractUrlProvider
    {
        return $this->productUrlProvider;
    }

    public function getName(): string
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function getUrls(SalesChannelContext $context, int $limit, ?int $offset = null): UrlResult
    {
        $result = $this->productUrlProvider->getUrls($context, $limit, $offset);

        $productIds = $this->getProductIds($context);

        if (empty($productIds)) return $result;
        $originalUrls = $result->getUrls();
        $nextOffset = $result->getNextOffset();
        $urls = [];
        foreach ($originalUrls as $url) {
            if (in_array($url->getIdentifier(), $productIds)) continue;
            $urls[] = $url;
        }
        return new UrlResult($urls, $nextOffset);
    }

    private function getProductIds(SalesChannelContext $salesChannelContext): ?array
    {
        $criteria = new Criteria();
        $criteria->addFilter(New MultiFilter(MultiFilter::CONNECTION_AND, [
            new NotFilter(MultiFilter::CONNECTION_AND, [new EqualsFilter('customFields.acris_product_customer_group_exclude_sitemap', null)]),
            new EqualsFilter('customFields.acris_product_customer_group_exclude_sitemap', true)
        ]));
        $searchIds = $this->productRepository->searchIds($criteria, $salesChannelContext->getContext());

        return $searchIds->getIds();
    }
}

