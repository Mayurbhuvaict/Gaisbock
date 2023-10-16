<?php declare(strict_types=1);

namespace Swag\Security\Tests\Subscriber;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Test\TestCaseBase\AdminFunctionalTestBehaviour;
use Swag\Security\Tests\Helper\UrlHelper;

/**
 * @internal
 */
class AdminSecurityFixesProviderTest extends TestCase
{
    use AdminFunctionalTestBehaviour;

    public function testConfig(): void
    {
        $client = $this->createClient();

        $client->request('GET', UrlHelper::getApiUrl('/api/_info/config', false));

        static::assertSame(200, $client->getResponse()->getStatusCode());

        $json = json_decode((string) $client->getResponse()->getContent(), true);

        static::assertArrayHasKey('swagSecurity', $json);
    }
}
