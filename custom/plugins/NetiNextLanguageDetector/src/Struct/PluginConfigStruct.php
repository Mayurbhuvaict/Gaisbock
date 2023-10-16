<?php

declare(strict_types=1);

namespace NetInventors\NetiNextLanguageDetector\Struct;

class PluginConfigStruct
{
    private bool   $active           = true;

    private bool   $allSalesChannels = false;

    private string $noCookieIps      = '';

    private string $logAddresses     = '';

    private string $defaultLanguage  = '';

    /**
     * @psalm-mutation-free
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @psalm-mutation-free
     */
    public function isAllSalesChannels(): bool
    {
        return $this->allSalesChannels;
    }

    /**
     * @psalm-mutation-free
     */
    public function getNoCookieIps(): string
    {
        return $this->noCookieIps;
    }

    /**
     * @psalm-mutation-free
     */
    public function getLogAddresses(): string
    {
        return $this->logAddresses;
    }

    /**
     * @psalm-mutation-free
     */
    public function getDefaultLanguage(): string
    {
        return $this->defaultLanguage;
    }
}
