<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1614425911EntryExtended extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1614425911;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            ALTER TABLE `hueb_seo_faq_questions_translation` MODIFY `question` mediumtext null;
            ALTER TABLE `hueb_seo_faq_questions_translation` MODIFY `answer` mediumtext null;
            ALTER TABLE `hueb_seo_faq_questions_translation` MODIFY `meta_description` mediumtext null;
            ALTER TABLE `hueb_seo_faq_questions_translation` MODIFY `keywords` mediumtext null;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
