<?php declare(strict_types=1);

namespace Swag\Security\Tests\Controller\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Adapter\Cache\CacheIdLoader;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Test\TestCaseBase\AdminFunctionalTestBehaviour;
use Swag\Security\Api\SecurityController;
use Swag\Security\Components\State;
use Swag\Security\Tests\Helper\UrlHelper;

/**
 * @internal
 */
class SecurityControllerTest extends TestCase
{
    use AdminFunctionalTestBehaviour;

    public function testLoadingConfig(): void
    {
        $client = $this->createClient();

        $client->request('GET', UrlHelper::getApiUrl('/api/_action/swag-security/available-fixes', false));

        static::assertSame(200, $client->getResponse()->getStatusCode());

        $json = json_decode((string) $client->getResponse()->getContent(), true);

        static::assertArrayHasKey('activeFixes', $json);
        static::assertArrayHasKey('availableFixes', $json);
    }

    public function testUpdateAvailable(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([['version' => '9.9.9', 'changelog' => [
                [
                    'version' => '9.9.9',
                ],
            ]]])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $controller = new SecurityController(
            $this->createMock(State::class),
            $this->getContainer()->get('plugin.repository'),
            sys_get_temp_dir(),
            $client,
            $this->createMock(CacheIdLoader::class)
        );

        $response = $controller->updateAvailable(Context::createDefaultContext());
        static::assertSame(200, $response->getStatusCode());

        $json = json_decode((string) $response->getContent(), true);

        static::assertArrayHasKey('updateAvailable', $json);
        static::assertTrue($json['updateAvailable']);
    }

    public function testUpdateCheckPluginNotAvailableInStore(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $controller = new SecurityController(
            $this->createMock(State::class),
            $this->getContainer()->get('plugin.repository'),
            sys_get_temp_dir(),
            $client,
            $this->createMock(CacheIdLoader::class)
        );

        $response = $controller->updateAvailable(Context::createDefaultContext());
        static::assertSame(200, $response->getStatusCode());

        $json = json_decode((string) $response->getContent(), true);

        static::assertArrayHasKey('updateAvailable', $json);
        static::assertFalse($json['updateAvailable']);
    }

    public function testClearCache(): void
    {
        $client = $this->createClient();

        $client->request('GET', UrlHelper::getApiUrl('/api/_action/swag-security/clear-container-cache', false));

        static::assertSame(204, $client->getResponse()->getStatusCode());
    }
}
