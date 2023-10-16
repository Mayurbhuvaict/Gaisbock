<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Migration;

use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
class Migration1685959119UpdateConfig extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1685959119;
    }

    public function update(Connection $connection): void
    {
        // Add user id to access token configs
        $accessTokenConfigs = $connection->executeQuery('
            SELECT * FROM `system_config`
            WHERE `configuration_key`
            LIKE \'SolidInstagramElements.config.accessToken\'
            AND JSON_EXTRACT(configuration_value, \'$._value.userId\') IS NULL;
        ')->fetchAllAssociative();

        foreach ($accessTokenConfigs as $accessTokenConfig) {
            $accessToken = json_decode($accessTokenConfig['configuration_value']);

            if (!property_exists($accessToken, '_value')) {
                return;
            }

            $accessToken = $accessToken->_value;

            if (property_exists($accessToken, 'accessToken')) {
                $userId = $this->fetchAccessTokenUserId($accessToken->accessToken);

                if ($userId) {
                    $connection->executeStatement(
                        '
                        UPDATE `system_config`
                        SET `configuration_value` = JSON_SET(configuration_value, \'$._value.userId\', \'' . $userId . '\')
                        WHERE id = :id',
                        [
                            'id' => $accessTokenConfig['id'],
                        ]
                    );
                }
            }
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    private function fetchAccessTokenUserId(string $accessToken): ?string
    {
        $httpClient = new Client();

        $response = $httpClient->request('GET', 'https://graph.instagram.com/me?fields=id&access_token=' . $accessToken);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $data = json_decode($response->getBody()->getContents());

        if (!property_exists($data, 'id')) {
            return null;
        }

        return $data->id;
    }
}
