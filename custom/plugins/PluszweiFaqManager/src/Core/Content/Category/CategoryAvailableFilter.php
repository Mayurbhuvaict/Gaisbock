<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;

class CategoryAvailableFilter extends MultiFilter
{
    public function __construct(string $salesChannelId)
    {
        parent::__construct(
            self::CONNECTION_AND,
            [
                new EqualsFilter('active', true),
                new EqualsFilter('salesChannels.id', $salesChannelId),
            ]
        );
    }
}