<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\ScheduledTask;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class CustomersAlsoViewedIndexTaskHandler extends ScheduledTaskHandler
{
    private Connection $connection;

    private SystemConfigService $systemConfigService;

    public function __construct(
        EntityRepository $scheduledTaskRepository,
        Connection                $connection,
        SystemConfigService           $systemConfigService
    )
    {
        parent::__construct($scheduledTaskRepository);
        $this->scheduledTaskRepository = $scheduledTaskRepository;
        $this->connection = $connection;
        $this->systemConfigService = $systemConfigService;
    }

    public static function getHandledMessages(): iterable
    {
        return [CustomersAlsoViewedIndexTask::class];
    }

    public function run(): void
    {

        $customersAlsoViewedReadDays = $this->systemConfigService->get('AcrisSuggestedProductsCS.config.customersAlsoViewedReadDays');

        if (empty($customersAlsoViewedReadDays)) {
            $customersAlsoViewedReadDays = 90;
        }

        $sql = '
            DELETE FROM acris_customers_also_viewed
            WHERE DATEDIFF(current_date, created_at) > ?;
        ';
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $customersAlsoViewedReadDays, \PDO::PARAM_INT);
            $stmt->executeQuery();
        } catch(\Throwable $exception) {

        }
    }
}
