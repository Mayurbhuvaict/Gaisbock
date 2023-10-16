<?php declare(strict_types=1);

namespace Swag\Security\Tests\Controller\Api;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Test\TestCaseBase\AdminFunctionalTestBehaviour;
use Swag\Security\Tests\Helper\UrlHelper;

/**
 * @internal
 */
class ConfigControllerTest extends TestCase
{
    use AdminFunctionalTestBehaviour;

    public function testSaveConfig(): void
    {
        $client = $this->createClient();

        $connection = $this->getContainer()->get(Connection::class);

        $connection->executeStatement('UPDATE user SET `password` = ?', [
            password_hash('test', \PASSWORD_BCRYPT),
        ]);

        $client->request('POST', UrlHelper::getApiUrl('/api/_action/swag-security/save-config', false), [
            'currentPassword' => 'test',
            'config' => [
                'test' => true,
            ],
        ]);

        static::assertSame(204, $client->getResponse()->getStatusCode());

        static::assertSame('1', $this->getConfig()['test']);

        $client->request('POST', UrlHelper::getApiUrl('/api/_action/swag-security/save-config', false), [
            'currentPassword' => 'test',
            'config' => [
                'test' => false,
            ],
        ]);

        static::assertSame(204, $client->getResponse()->getStatusCode());

        static::assertSame('0', $this->getConfig()['test']);
    }

    public function testSaveError(): void
    {
        $client = $this->createClient();

        $this->getContainer()->get(Connection::class)->executeStatement('UPDATE user SET `password` = ?', [
            password_hash('test', \PASSWORD_BCRYPT),
        ]);

        $client->request('POST', UrlHelper::getApiUrl('/api/_action/swag-security/save-config', false), [
            'currentPassword' => 'test2',
            'config' => [
                'test' => true,
            ],
        ]);

        static::assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testSaveIntegration(): void
    {
        $client = $this->createClient(null, false, false);
        $this->authorizeBrowserWithIntegration($client);

        $this->getContainer()->get(Connection::class)->executeStatement('UPDATE user SET `password` = ?', [
            password_hash('test', \PASSWORD_BCRYPT),
        ]);

        $client->request('POST', UrlHelper::getApiUrl('/api/_action/swag-security/save-config', false), [
            'currentPassword' => 'test',
            'config' => [
                'test' => true,
            ],
        ]);

        static::assertSame(403, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array<mixed, mixed>
     */
    private function getConfig(): array
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get(Connection::class);

        return $connection->fetchAllKeyValue('SELECT * FROM swag_security_config');
    }
}
