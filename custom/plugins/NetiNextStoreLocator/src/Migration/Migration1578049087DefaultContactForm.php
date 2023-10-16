<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1578049087DefaultContactForm extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_578_049_087;
    }

    public function update(Connection $connection): void
    {
        /** @var string|false $id */
        $id = $connection->executeQuery('SELECT id FROM neti_store_locator_contact_form')->fetchOne();

        if (!empty($id)) {
            return;
        }

        // Get all languages (de-DE, en-GB and probably the Defaults::SYSTEM_LANGUAGE)
        $languages = $this->getLanguages($connection);

        foreach ($this->getData() as $row) {
            $fieldId = $this->createField($connection, $row);

            /**
             * Create translations for de-DE, en-GB and the default language
             * If the given language is not found in our store we always refer to en-GB
             */
            foreach ($languages as $language) {
                $locale = $language['code'];

                $this->createFieldTranslation(
                    $connection,
                    $fieldId,
                    $language['id'],
                    $row['label'][$locale] ?? $row['label']['en-GB'],
                    $row['value'][$locale] ?? $row['value']['en-GB']
                );
            }
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    private function createFieldTranslation(
        Connection $connection,
        string $fieldId,
        string $languageId,
        string $label,
        ?string $value
    ): void {
        $sql = '
            INSERT INTO neti_store_locator_contact_form_translation
              (label, value, created_at, updated_at, neti_store_locator_contact_form_id, language_id)
            VALUES (:label, :value, NOW(), NULL, :fieldId, :languageId)
        ';

        $connection->executeStatement(
            $sql,
            [
                'label'      => $label,
                'value'      => $value,
                'fieldId'    => $fieldId,
                'languageId' => $languageId,
            ]
        );
    }

    private function createField(Connection $connection, array $data): string
    {
        $sql = '
            INSERT INTO neti_store_locator_contact_form
              (id, active, type, required, position, created_at, updated_at)
            VALUES (:id, :active, :type, :required, :position, NOW(), NULL)
        ';

        $id = Uuid::randomBytes();

        $connection->executeStatement(
            $sql,
            [
                'id'       => $id,
                'active'   => $data['active'],
                'type'     => $data['type'],
                'required' => $data['required'],
                'position' => $data['position'],
            ]
        );

        return $id;
    }

    /**
     * @return array{
     *     active: bool,
     *     type: string,
     *     required: bool,
     *     position: int,
     *     label: array{de-DE: string, en-GB: string},
     *     value: array{de-DE: ?string, en-GB: ?string}
     * }[]
     */
    private function getData(): array
    {
        return [
            [
                'active'   => true,
                'type'     => 'subject',
                'required' => true,
                'position' => 1,
                'label'    => [
                    'de-DE' => 'Betreff',
                    'en-GB' => 'Subject',
                ],
                'value'    => [
                    'de-DE' => null,
                    'en-GB' => null,
                ],
            ],
            [
                'active'   => true,
                'type'     => 'select',
                'required' => true,
                'position' => 2,
                'label'    => [
                    'de-DE' => 'Anrede',
                    'en-GB' => 'Salutation',
                ],
                'value'    => [
                    'de-DE' => 'Herr;Frau',
                    'en-GB' => 'Mr;Ms',
                ],
            ],
            [
                'active'   => true,
                'type'     => 'textfield',
                'required' => true,
                'position' => 3,
                'label'    => [
                    'de-DE' => 'Name',
                    'en-GB' => 'Name',
                ],
                'value'    => [
                    'de-DE' => null,
                    'en-GB' => null,
                ],
            ],
            [
                'active'   => true,
                'type'     => 'email',
                'required' => true,
                'position' => 4,
                'label'    => [
                    'de-DE' => 'E-Mail',
                    'en-GB' => 'eMail',
                ],
                'value'    => [
                    'de-DE' => null,
                    'en-GB' => null,
                ],
            ],
            [
                'active'   => true,
                'type'     => 'email_copy',
                'required' => true,
                'position' => 5,
                'label'    => [
                    'de-DE' => 'Kopie an mich?',
                    'en-GB' => 'Copy to me?',
                ],
                'value'    => [
                    'de-DE' => null,
                    'en-GB' => null,
                ],
            ],
            [
                'active'   => true,
                'type'     => 'textarea',
                'required' => true,
                'position' => 6,
                'label'    => [
                    'de-DE' => 'Ihre Nachricht',
                    'en-GB' => 'Your message',
                ],
                'value'    => [
                    'de-DE' => null,
                    'en-GB' => null,
                ],
            ],
        ];
    }

    /**
     *
     * @return array{id: string, code: string}[]
     * @throws Exception
     */
    private function getLanguages(Connection $connection): array
    {
        // en-DE, de-DE, and language-system
        $sql = '
            SELECT l.id, locale.code
            FROM language l
            LEFT JOIN locale ON locale.id = l.locale_id
            WHERE locale.code IN ("de-DE", "en-GB")
              OR HEX(l.id) = :languageId
        ';

        /** @var array{id: string, code: string}[] $languages */
        $languages = $connection->fetchAllAssociative(
            $sql,
            [
                'languageId' => Defaults::LANGUAGE_SYSTEM,
            ]
        );

        return $languages;
    }
}
