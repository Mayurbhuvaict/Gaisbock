<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\Migration;

use Doctrine\DBAL\Connection;

class ColumnHelper
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function add(string $table, string $column, string $definition): void
    {
        if ($this->has($table, $column) === false) {
            $sql = '
                ALTER TABLE `%s`
                %s;
            ';

            $sql = sprintf($sql, $table, sprintf($definition, $column));

            $this->connection->executeStatement($sql);
        }
    }

    public function has(string $table, string $column): bool
    {
        $sql        = sprintf('SHOW COLUMNS FROM `%s` LIKE "%s"', $table, $column);
        /** @var false|string $columnName */
        $columnName = $this->connection->executeQuery($sql)->fetchOne();

        return $columnName === $column;
    }

    public function update(string $table, string $column, string $definition): void
    {
        if ($this->has($table, $column) === true) {
            $sql = '
                ALTER TABLE `%s`
                %s;
            ';

            $sql = sprintf($sql, $table, sprintf($definition, $column));

            $this->connection->executeStatement($sql);
        }
    }
}
