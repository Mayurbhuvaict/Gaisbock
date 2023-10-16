<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\ScheduledTask;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Shopware\Core\System\SystemConfig\SystemConfigService;

/**
 * @internal
 */
final class RefreshAccessTokenTaskHandler extends ScheduledTaskHandler
{
    protected EntityRepository $scheduledTaskRepository;

    private SystemConfigService $systemConfigService;

    private EntityRepository $salesChannelRepository;

    public function __construct(
        EntityRepository $scheduledTaskRepository,
        SystemConfigService $systemConfigService,
        EntityRepository $salesChannelRepository
    ) {
        parent::__construct($scheduledTaskRepository);
        $this->systemConfigService = $systemConfigService;
        $this->salesChannelRepository = $salesChannelRepository;
    }

    public static function getHandledMessages(): iterable
    {
        return [RefreshAccessTokenTask::class];
    }

    public function run(): void
    {
        // Global sales channel settings
        $this->refreshAccessToken();

        // Iterate individual sales channel settings
        $salesChannels = $this->salesChannelRepository->search(
            new Criteria(),
            Context::createDefaultContext()
        );
        $salesChannelIds = $salesChannels->getEntities()->getIds();

        foreach ($salesChannelIds as $salesChannelId) {
            $this->refreshAccessToken($salesChannelId);
        }
    }

    public function refreshAccessToken(?string $salesChannelId = null): void
    {
        $accessToken = $this->systemConfigService->get('SolidInstagramElements.config.accessToken', $salesChannelId);

        if ($accessToken) {
            if (\array_key_exists('lastRefreshed', $accessToken)) {
                $lastRefreshedDate = new \DateTime($accessToken['lastRefreshed']);
                $currentDate = new \DateTime();
                $activeForDays = $lastRefreshedDate->diff($currentDate)->format('%a');

                if (\array_key_exists('expiresIn', $accessToken)) {
                    $expiresInDays = (int) ($accessToken['expiresIn'] / 60 / 60 / 24);
                    $threshold = 20; // In days

                    if ($activeForDays > $expiresInDays - $threshold) {
                        if (\array_key_exists('accessToken', $accessToken)) {
                            $accessTokenValue = $accessToken['accessToken'];

                            // Refresh access token
                            $url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $accessTokenValue;
                            $curl = curl_init();
                            curl_setopt($curl, \CURLOPT_URL, $url);
                            curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, \CURLOPT_HEADER, true);
                            $response = curl_exec($curl);
                            $responseStatus = curl_getinfo($curl, \CURLINFO_RESPONSE_CODE);
                            $responseHeaderLength = curl_getinfo($curl, \CURLINFO_HEADER_SIZE);
                            curl_close($curl);

                            if ($responseStatus === 200) {
                                $responseBody = substr($response, $responseHeaderLength);

                                if ($responseBody) {
                                    $data = json_decode($responseBody, true);

                                    if ($data !== null && json_last_error() === \JSON_ERROR_NONE) {
                                        $newExpiresIn = $data['expires_in'];

                                        if ($newExpiresIn) {
                                            $newAccessToken = $accessToken;
                                            $newAccessToken['expiresIn'] = $newExpiresIn;
                                            $newAccessToken['lastRefreshed'] = $currentDate->format('Y-m-d');
                                            $this->systemConfigService->set(
                                                'SolidInstagramElements.config.accessToken',
                                                $newAccessToken,
                                                $salesChannelId
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
