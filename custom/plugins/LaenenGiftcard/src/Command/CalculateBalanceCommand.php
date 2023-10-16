<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateBalanceCommand extends Command
{
    protected static $defaultName = 'kw:giftcard:calculate-balance';

    private Connection $connection;

    public function __construct(
        Connection $connection
    ) {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->connection->executeStatement(<<<SQL
UPDATE lae_giftcard
SET balance = initial_amount - (SELECT SUM(amount) FROM lae_giftcard_transaction WHERE giftcard_id = lae_giftcard.id)
SQL
        );

        return Command::SUCCESS;
    }
}
