<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Uuid\Uuid;

class ICTECHNewsletterWithPromotion extends Plugin
{
    // Newsletter mail
    final public const TEMPLATE_TYPE_NAME = 'Newsletter Popup promotion';
    final public const TEMPLATE_TYPE_TECHNICAL_NAME = 'newsletter_popup_promotion';

    private function fetchMailTemplateType(
        string $type,
        Connection $connection
    ): ?string {
        $result = $connection->fetchAllAssociative(
            'SELECT * FROM mail_template_type WHERE technical_name = ?',
            array($type)
        );
        if ($result && array_key_exists(0, $result)) {
            return Uuid::fromBytesToHex($result[0]['id']);
        }

        return null;
    }

    private function checkMailTemplate(string $id, $context): bool
    {
        $mailTemplateRepository = $this->container->get(
            'mail_template.repository'
        );
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('mailTemplateTypeId', $id)
        );
        return $mailTemplateRepository->search(
            $criteria,
            $context->getContext()
        )->first() !== null;
    }

    public function install(InstallContext $installContext): void
    {
        $connection = $this->container->get(Connection::class);

        // Create newsletter mail
        $newsletterMailTemplateTypeId = $this->fetchMailTemplateType(
            self::TEMPLATE_TYPE_TECHNICAL_NAME,
            $connection
        );

        if (!$newsletterMailTemplateTypeId) {
            $newsletterMailTemplateTypeId = Uuid::randomHex();
            $this->createNewsletterMailTemplateType(
                $connection,
                $newsletterMailTemplateTypeId
            );
        }

        if (!$this->checkMailTemplate(
            $newsletterMailTemplateTypeId,
            $installContext
        )
        ) {
            try {
                $this->createNewsletterMailTemplate(
                    $connection,
                    $newsletterMailTemplateTypeId
                );
            } catch (UniqueConstraintViolationException) {

            }
        }
    }

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);

        $connection->executeUpdate('DROP TABLE IF EXISTS `newsletter_popup_translation`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `newsletter_popup`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `reserved_individual_promotion_code`');

        //get the Templates and Associations added by this Plugin from the DB
        /** @var EntityRepository $mailTemplateTypeRepository */
        $mailTemplateTypeRepository = $this->container->get('mail_template_type.repository');
        /** @var EntityRepository $mailTemplateRepository */
        $mailTemplateRepository = $this->container->get('mail_template.repository');

        /** @var MailTemplateTypeEntity $myCustomMailTemplateType */
        $myCustomMailTemplateType = $mailTemplateTypeRepository->search(
            (new Criteria())
                ->addFilter(new EqualsFilter('technicalName', self::TEMPLATE_TYPE_TECHNICAL_NAME)),
            $context
                ->getContext()
        )->first();

        $mailTemplateIds = $mailTemplateRepository->searchIds(
            (new Criteria())
                ->addFilter(
                    new MultiFilter(
                        MultiFilter::CONNECTION_OR,
                        [
                            new EqualsFilter(
                                'mailTemplateTypeId',
                                $myCustomMailTemplateType->getId()
                            )
                        ]
                    )
                ),
            $context
                ->getContext()
        )->getIds();

        //Get the Ids from the fetched Entities
        $ids = array_map(static fn ($id) => ['id' => $id], $mailTemplateIds);

        //Delete the Templates which were added by this Plugin
        $mailTemplateRepository->delete($ids, $context->getContext());

        //Delete the TemplateType which were added by this Plugin
        $mailTemplateTypeRepository->delete([
            ['id' => $myCustomMailTemplateType->getId()]
        ], $context->getContext());
    }

    /* ---- Newsletter mail ---- */

    // create mail template type
    private function createNewsletterMailTemplateType(
        $connection,
        $mailTemplateTypeId
    ): void {
        $enLangId = $this->getLanguageIdByLocale($connection, 'en-GB');
        $deLangId = $this->getLanguageIdByLocale($connection, 'de-DE');

        $connection->insert('mail_template_type', [
            'id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'technical_name' => self::TEMPLATE_TYPE_TECHNICAL_NAME,
            'available_entities' => json_encode([
                'newsletterRecipient' => 'newsletter_recipient',
                'promotion' => 'promotion',
                'salesChannel' => 'sales_channel'
            ]),
            'created_at' => (new \DateTime())->format(
                Defaults::STORAGE_DATE_TIME_FORMAT
            ),
        ]);

        if ($enLangId) {
            $connection->insert('mail_template_type_translation', [
                'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
                'language_id' => $enLangId,
                'name' => self::TEMPLATE_TYPE_NAME,
                'created_at' => (new \DateTime())->format(
                    Defaults::STORAGE_DATE_TIME_FORMAT
                ),
            ]);
        }

        if ($deLangId) {
            $connection->insert('mail_template_type_translation', [
                'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
                'language_id' => $deLangId,
                'name' => self::TEMPLATE_TYPE_NAME,
                'created_at' => (new \DateTime())->format(
                    Defaults::STORAGE_DATE_TIME_FORMAT
                ),
            ]);
        }

        if (
            $deLangId !== Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM) &&
            $enLangId !== Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM)
        ) {
            $connection->insert('mail_template_type_translation', [
                'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
                'language_id' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
                'name' => self::TEMPLATE_TYPE_NAME,
                'created_at' => (new \DateTime())->format(
                    Defaults::STORAGE_DATE_TIME_FORMAT
                ),
            ]);
        }
    }

    // Create mail template
    private function createNewsletterMailTemplate(
        $connection,
        $mailTemplateTypeId
    ): void {
        $mailTemplateId = Uuid::randomHex();

        $enLangId = $this->getLanguageIdByLocale($connection, 'en-GB');
        $deLangId = $this->getLanguageIdByLocale($connection, 'de-DE');

        $connection->insert('mail_template', [
            'id' => Uuid::fromHexToBytes($mailTemplateId),
            'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'system_default' => true,
            'created_at' => (new \DateTime())->format(
                Defaults::STORAGE_DATE_TIME_FORMAT
            ),
        ]);

        if ($enLangId) {
            $connection->insert('mail_template_translation', [
                'mail_template_id' => Uuid::fromHexToBytes($mailTemplateId),
                'language_id' => $enLangId,
                'sender_name' => '{{ salesChannel.name }}',
                'subject' => 'Your coupon code. Thank you for registering for the newsletter',
                'description' => '',
                'content_html' => $this->getNewsletterContentHtmlEn(),
                'content_plain' => $this->getNewsletterContentPlainEn(),
                'created_at' => (new \DateTime())->format(
                    Defaults::STORAGE_DATE_TIME_FORMAT
                ),
            ]);
        }

        if ($deLangId) {
            $deLangIds = $this->getLanguageIdByLocale($connection, 'de-DE');
            $connection->insert('mail_template_translation', [
                'mail_template_id' => Uuid::fromHexToBytes($mailTemplateId),
                'language_id' => $deLangIds,
                'sender_name' => '{{ salesChannel.name }}',
                'subject' => 'Dein coupon code. Danke für die Registrierung zum Newsletter',
                'description' => '',
                'content_html' => $this->getNewsletterContentHtmlDe(),
                'content_plain' => $this->getNewsletterContentPlainDe(),
                'created_at' => (new \DateTime())->format(
                    Defaults::STORAGE_DATE_TIME_FORMAT
                ),
            ]);
        }

        if (
            $deLangId !== Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM) &&
            $enLangId !== Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM)
        ) {
            $connection->insert('mail_template_translation', [
                'mail_template_id' => Uuid::fromHexToBytes($mailTemplateId),
                'language_id' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
                'sender_name' => '{{ salesChannel.name }}',
                'subject' => 'Your coupon code. Thank you for registering for the newsletter',
                'description' => '',
                'content_html' => $this->getNewsletterContentHtmlEn(),
                'content_plain' => $this->getNewsletterContentPlainEn(),
                'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        }
    }

    private function getLanguageIdByLocale(Connection $connection, string $locale): ?string
    {
        $sql = <<<SQL
        SELECT `language`.`id`
        FROM `language`
        INNER JOIN `locale` ON `locale`.`id` = `language`.`locale_id`
        WHERE `locale`.`code` = :code
        SQL;
        return $connection->executeQuery(
            $sql,
            ['code' => $locale]
        )->fetchOne();
//        return $languageId;
    }

    private function getNewsletterContentHtmlEn(): string
    {
        return <<<MAIL
Hello,<br><br>Your coupon code is {{ promotion.code }}.<br><br>Thank you for registering for the newsletter.
MAIL;
    }

    private function getNewsletterContentHtmlDe(): string
    {
        return <<<MAIL
Hallo,<br><br>Dein Coupon Code lautet {{ promotion.code }}.<br><br>Vielen Dank für deine Registrierung zum Newsletter.
MAIL;
    }

    // Content plain

    private function getNewsletterContentPlainEn(): string
    {
        return <<<MAIL
Hello,\n\n Your coupon code is {{ promotion.code }}.\n\n Thank you for registering for the newsletter.
MAIL;
    }

    private function getNewsletterContentPlainDe(): string
    {
        return <<<MAIL
Hallo,\n\n Dein Coupon Code lautet {{ promotion.code }}.\n\n Vielen Dank für deine Registrierung zum Newsletter.
MAIL;
    }
}
