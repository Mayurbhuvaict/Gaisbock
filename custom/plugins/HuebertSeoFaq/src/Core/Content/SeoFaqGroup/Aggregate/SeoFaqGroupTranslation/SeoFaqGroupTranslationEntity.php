<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqGroup\Aggregate\SeoFaqGroupTranslation;

use Huebert\SeoFaq\Core\Content\SeoFaqGroup\SeoFaqGroupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class SeoFaqGroupTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $seoFaqGroupId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var SeoFaqGroupEntity|null
     */
    protected $seoFaqGroup;

    /**
     * @return string
     */
    public function getSeoFaqGroupId(): string
    {
        return $this->seoFaqGroupId;
    }

    /**
     * @param string $seoFaqGroupId
     */
    public function setSeoFaqGroupId(string $seoFaqGroupId): void
    {
        $this->seoFaqGroupId = $seoFaqGroupId;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return SeoFaqGroupEntity|null
     */
    public function getSeoFaqGroup(): ?SeoFaqGroupEntity
    {
        return $this->seoFaqGroup;
    }

    /**
     * @param SeoFaqGroupEntity|null $seoFaqGroup
     */
    public function setSeoFaqGroup(?SeoFaqGroupEntity $seoFaqGroup): void
    {
        $this->seoFaqGroup = $seoFaqGroup;
    }
}

