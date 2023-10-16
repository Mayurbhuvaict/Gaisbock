<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Commands;

use NetInventors\NetiNextStoreLocator\Storefront\Framework\Seo\SeoUrlRoute\StorePageSeoUrlRoute;
use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeoCommand extends Command
{
    public function __construct(
        string                            $name,
        private readonly EntityRepository $storeRepository,
        private readonly SeoUrlUpdater    $seoUrlUpdater
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Generates seo urls for the store locator stores.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Loading stores');

        $criteria = new Criteria();
        $context  = new Context(new SystemSource());

        $result = $this->storeRepository->searchIds($criteria, $context);
        /** @var list<string> $ids */
        $ids = $result->getIds();

        $output->writeln('Writing seo urls');

        $this->seoUrlUpdater->update(StorePageSeoUrlRoute::ROUTE_NAME, $ids);

        return 0;
    }
}
