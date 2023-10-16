<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\Service;

use Shopware\Core\DevOps\Environment\EnvironmentHelper;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ResetInterface;

class CmsSlotInstagramPostMediaUrlGenerator implements ResetInterface
{
    private FilesystemOperator $filesystem;

    private RequestStack $requestStack;

    private ?string $baseUrl;

    private ?string $fallbackBaseUrl = null;

    public function __construct(
        FilesystemOperator $filesystem,
        RequestStack $requestStack,
        ?string $baseUrl = null
    ) {
        $this->filesystem = $filesystem;
        $this->requestStack = $requestStack;
        $this->baseUrl = $this->normalizeBaseUrl($baseUrl);
    }

    public function getRelativeUrl(string $userId, string $postId): ?string
    {
        $availableFiles = $this->filesystem->listContents($userId);
        $matchingFilePath = '';

        foreach ($availableFiles as $file) {
            $filePathSegments = explode('/', $file->path());
            $filename = $filePathSegments[array_key_last($filePathSegments)];

            if (str_contains($filename, $postId)) {
                $matchingFilePath = $file->path();

                break;
            }
        }

        if (!$matchingFilePath) {
            return null;
        }

        return $this->toPathString([
            'plugins/solid_instagram_elements',
            $matchingFilePath,
        ]);
    }

    public function getAbsoluteUrl(string $userId, string $postId): ?string
    {
        $relativeUrl = $this->getRelativeUrl($userId, $postId);

        if ($relativeUrl) {
            return $this->getBaseUrl() . '/' . $this->getRelativeUrl($userId, $postId);
        }

        return null;
    }

    public function reset(): void
    {
        $this->fallbackBaseUrl = null;
    }

    private function createFallbackUrl(): string
    {
        $request = $this->requestStack->getMainRequest();
        if ($request && $request->getHttpHost() !== '' && $request->getHttpHost() !== ':') {
            $basePath = $request->getSchemeAndHttpHost() . $request->getBasePath();

            if (parse_url($basePath) === false) {
                return (string) EnvironmentHelper::getVariable('APP_URL');
            }

            return rtrim($basePath, '/');
        }

        return (string) EnvironmentHelper::getVariable('APP_URL');
    }

    private function normalizeBaseUrl(?string $baseUrl): ?string
    {
        if ($baseUrl === null) {
            return null;
        }

        return rtrim($baseUrl, '/');
    }

    private function getBaseUrl(): string
    {
        if (!$this->baseUrl) {
            return $this->fallbackBaseUrl ?? $this->fallbackBaseUrl = $this->createFallbackUrl();
        }

        return $this->baseUrl;
    }

    private function toPathString(array $parts): string
    {
        return implode('/', array_filter($parts));
    }
}
