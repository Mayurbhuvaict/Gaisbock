<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\Service;

use GuzzleHttp\Client;
use League\Flysystem\FilesystemOperator;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class CmsSlotInstagramPostService
{
    public const MEDIA_TYPE_IMAGE = 'IMAGE';
    public const MEDIA_TYPE_CAROUSEL_ALBUM = 'CAROUSEL_ALBUM';
    public const MEDIA_TYPE_VIDEO = 'VIDEO';

    private SystemConfigService $systemConfigService;

    private EntityRepository $salesChannelRepository;

    private EntityRepository $cmsSlotInstagramPostRepository;

    private FilesystemOperator $filesystem;

    private CmsSlotInstagramPostMediaUrlGenerator $cmsSlotInstagramPostMediaUrlGenerator;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepository $salesChannelRepository,
        EntityRepository $cmsSlotInstagramPostRepository,
        FilesystemOperator $filesystem,
        CmsSlotInstagramPostMediaUrlGenerator $cmsSlotInstagramPostMediaUrlGenerator
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->salesChannelRepository = $salesChannelRepository;
        $this->cmsSlotInstagramPostRepository = $cmsSlotInstagramPostRepository;
        $this->filesystem = $filesystem;
        $this->cmsSlotInstagramPostMediaUrlGenerator = $cmsSlotInstagramPostMediaUrlGenerator;
    }

    public function getMediaUrl(string $userId, string $postId): ?string
    {
        return $this->cmsSlotInstagramPostMediaUrlGenerator->getAbsoluteUrl($userId, $postId);
    }

    public function fetchAndStoreLatestPosts(): void
    {
        $salesChannelAccessTokens = $this->getSalesChannelAccessTokens();
        $salesChannelUseDatabaseAndFilesystemConfigs = $this->getSalesChannelUseDatabaseAndFilesystemConfigs();

        if (!$salesChannelAccessTokens) {
            // TODO: Log error
            return;
        }

        foreach ($salesChannelAccessTokens as $salesChannelId => $salesChannelAccessToken) {
            if (!\array_key_exists($salesChannelId, $salesChannelUseDatabaseAndFilesystemConfigs) || !$salesChannelUseDatabaseAndFilesystemConfigs[$salesChannelId]) {
                continue;
            }

            if (!\array_key_exists('accessToken', $salesChannelAccessToken) || !\array_key_exists('userId', $salesChannelAccessToken)) {
                // TODO: Log error
                continue;
            }

            $userId = $salesChannelAccessToken['userId'];
            $latestPosts = $this->fetchLatestPosts($salesChannelAccessToken['accessToken']);

            if (!$latestPosts) {
                // TODO: Log error
                continue;
            }

            $context = Context::createDefaultContext();

            $this->deletePostsByUserId($userId, $context);
            $this->createPostsFromData($userId, $latestPosts, $context);
            $this->storePostsMedia($userId, $latestPosts);
        }
    }

    private function getSalesChannelAccessTokens(): array
    {
        $salesChannelAccessTokens = [];

        // Global
        $globalAccessToken = $this->systemConfigService->get('SolidInstagramElements.config.accessToken');

        if ($globalAccessToken) {
            $salesChannelAccessTokens['global'] = $globalAccessToken;
        }

        // Sales channels
        $salesChannelIds = $this->getSalesChannelIds();

        if (!$salesChannelIds) {
            return $salesChannelAccessTokens;
        }

        foreach ($salesChannelIds as $salesChannelId) {
            $salesChannelAccessToken = $this->systemConfigService->get('SolidInstagramElements.config.accessToken', $salesChannelId);

            if ($salesChannelAccessToken) {
                $salesChannelAccessTokens[$salesChannelId] = $salesChannelAccessToken;
            }
        }

        return $salesChannelAccessTokens;
    }

    private function getSalesChannelUseDatabaseAndFilesystemConfigs(): array
    {
        $salesChannelUseDatabaseAndFilesystemConfigs = [];

        // Global
        $globalUseDatabaseAndFilesystem = $this->systemConfigService->get('SolidInstagramElements.config.useDatabaseAndFilesystem');
        $salesChannelUseDatabaseAndFilesystemConfigs['global'] = $globalUseDatabaseAndFilesystem;

        // Sales channels
        $salesChannelIds = $this->getSalesChannelIds();

        if (!$salesChannelIds) {
            return $salesChannelUseDatabaseAndFilesystemConfigs;
        }

        foreach ($salesChannelIds as $salesChannelId) {
            $salesChannelUseDatabaseAndFilesystem = $this->systemConfigService->get('SolidInstagramElements.config.useDatabaseAndFilesystem', $salesChannelId);
            $salesChannelUseDatabaseAndFilesystemConfigs[$salesChannelId] = $salesChannelUseDatabaseAndFilesystem;
        }

        return $salesChannelUseDatabaseAndFilesystemConfigs;
    }

    private function getSalesChannelIds(): ?array
    {
        $salesChannels = $this->salesChannelRepository->search(
            new Criteria(),
            Context::createDefaultContext()
        );

        if (!$salesChannels->getTotal()) {
            return null;
        }

        return $salesChannels->getEntities()->getIds();
    }

    private function fetchLatestPosts(string $accessToken): ?array
    {
        $httpClient = new Client();

        $response = $httpClient->request('GET', 'https://graph.instagram.com/me/media?fields=username,caption,media_type,media_url,permalink,thumbnail_url,timestamp,children{media_url}&access_token=' . $accessToken);

        if ($response->getStatusCode() !== 200) {
            // TODO: Log error
            return null;
        }

        return json_decode($response->getBody()->getContents())->data;
    }

    private function deletePostsByUserId(string $userId, Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('userId', $userId));
        $oldUserPostIds = $this->cmsSlotInstagramPostRepository->searchIds($criteria, $context);

        if ($oldUserPostIds->getTotal()) {
            $oldUserPostIds = $oldUserPostIds->getIds();
            $oldUserPostIds = array_map(function ($id) {
                return ['id' => $id];
            }, $oldUserPostIds);

            $this->cmsSlotInstagramPostRepository->delete($oldUserPostIds, $context);
        }
    }

    private function createPostsFromData(string $userId, array $data, Context $context): void
    {
        foreach ($data as $post) {
            $mediaUrl = $this->getPostMediaUrl($post);

            if (!$mediaUrl) {
                // TODO: Log error
                continue;
            }

            try {
                $this->cmsSlotInstagramPostRepository->create(
                    [
                        [
                            'id' => Uuid::randomHex(),
                            'userId' => $userId,
                            'username' => $post->username,
                            'postId' => $post->id,
                            'caption' => $post->caption,
                            'mediaType' => $post->media_type,
                            'mediaUrl' => $mediaUrl,
                            'permalink' => $post->permalink,
                            'timestamp' => $post->timestamp,
                        ],
                    ],
                    $context
                );
            } catch (\Exception $exception) {
                // TODO: Log error
            }
        }
    }

    private function storePostsMedia(string $userId, array $data): void
    {
        $httpClient = new Client();
        $newPostIds = [];

        // Store new media
        foreach ($data as $post) {
            $mediaUrl = $this->getPostMediaUrl($post);

            if (!$mediaUrl) {
                // TODO: Log error
                continue;
            }

            $response = $httpClient->request('GET', $mediaUrl);

            if ($response->getStatusCode() !== 200) {
                // TODO: Log error
                return;
            }

            $fileExtension = explode('/', $response->getHeader('Content-Type')[0]);
            $fileExtension = $fileExtension[array_key_last($fileExtension)];

            $this->filesystem->write($userId . '/' . $post->id . '.' . $fileExtension, $response->getBody()->getContents());

            array_push($newPostIds, $post->id);
        }

        // Delete ununsed media
        $existingFiles = $this->filesystem->listContents($userId);

        foreach ($existingFiles as $file) {
            $filePathSegments = explode('/', $file->path());
            $filename = $filePathSegments[array_key_last($filePathSegments)];
            $fileNameSegments = explode('.', $filename);
            $filenameWithoutExtension = $fileNameSegments[array_key_first($fileNameSegments)];

            if (!\in_array($filenameWithoutExtension, $newPostIds, true)) {
                $this->filesystem->delete($file->path());
            }
        }
    }

    private function getPostMediaUrl(object $post): ?string
    {
        /* Hotfix for missing media_url property on carousel albums */
        if ($post->media_type === self::MEDIA_TYPE_CAROUSEL_ALBUM && !property_exists($post, 'media_url')) {
            if (!property_exists($post, 'children')) {
                return null;
            }

            $children = $post->children;

            if (!property_exists($children, 'data')) {
                return null;
            }

            $data = $children->data;

            if (!$data) {
                return null;
            }

            $firstChild = $data[0];

            if (!property_exists($firstChild, 'media_url')) {
                return null;
            }

            return $firstChild->media_url;
        }

        if ($post->media_type === self::MEDIA_TYPE_VIDEO && $post->thumbnail_url) {
            return $post->thumbnail_url;
        }

        if (!property_exists($post, 'media_url')) {
            return null;
        }

        return $post->media_url;
    }
}
