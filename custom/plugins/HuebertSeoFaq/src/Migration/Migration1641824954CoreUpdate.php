<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1641824954CoreUpdate extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1641824954;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            ALTER TABLE `hueb_seo_faq_group`
            ADD COLUMN `position` INT(11) NOT NULL DEFAULT 1 AFTER `id`');

        $connection->executeUpdate('
            ALTER TABLE `hueb_seo_faq_questions`
            ADD COLUMN `question_position` INT(11) NOT NULL DEFAULT 1 AFTER `id`');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}