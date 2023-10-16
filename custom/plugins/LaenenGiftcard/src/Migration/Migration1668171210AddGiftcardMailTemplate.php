<?php declare(strict_types=1);

namespace Laenen\Giftcard\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1668171210AddGiftcardMailTemplate extends MigrationStep
{
    public const MAIL_TEMPLATE_ID = 'eef3e9e522054e9abe2f63961470b1d4';

    public function getCreationTimestamp(): int
    {
        return 1668171210;
    }

    public function update(Connection $connection): void
    {
        $languageId = Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM);

        $mailTemplateTypeId = $this->createMailTemplateType($connection, $languageId);

        $this->createMailTemplate($connection, $languageId, $mailTemplateTypeId);
    }

    public function updateDestructive(Connection $connection): void
    {

    }

    private function createMailTemplateType(Connection $connection, string $languageId): string
    {
        $mailTemplateTypeId = Uuid::randomHex();

        $connection->insert('mail_template_type', [
            'id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'technical_name' => 'lae_giftcard_created',
            'available_entities' => json_encode(['order' => 'order', 'giftcard' => 'lae_giftcard']),
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->insert('mail_template_type_translation', [
            'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'language_id' => $languageId,
            'name' => 'Giftcard created',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        return $mailTemplateTypeId;
    }

    private function createMailTemplate(Connection $connection, string $languageId, string $mailTemplateTypeId): void
    {
        $connection->insert('mail_template', [
            'id' => Uuid::fromHexToBytes(self::MAIL_TEMPLATE_ID),
            'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'system_default' => 0,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->insert('mail_template_translation', [
            'mail_template_id' => Uuid::fromHexToBytes(self::MAIL_TEMPLATE_ID),
            'language_id' => $languageId,
            'sender_name' => '{{ salesChannel.name }}',
            'subject' => 'Your giftcard',
            'description' => 'Template for email to client who bought giftcard',
            'content_html' => $this->getContentHtml(),
            'content_plain' => $this->getContentPlain(),
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

    }

    private function getContentHtml(): string
    {
        return <<<MAIL
<div style="font-family:arial; font-size:12px;">
    <p>
        Dear client,<br />
        <br />
        Thank you for your giftcard purchase.<br />
        You will find the giftcard in PDF format attached to this email.<br />
        <br />
        Kind regards
    </p>
</div>
MAIL;

    }

    private function getContentPlain(): string
    {
        return <<<MAIL
Dear client,

Thank you for your giftcard purchase.
You will find the giftcard in PDF format attached to this email.

Kind regards
MAIL;

    }
}
