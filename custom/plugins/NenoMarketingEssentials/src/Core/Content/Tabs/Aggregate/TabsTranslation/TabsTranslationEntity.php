<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\Tabs\Aggregate\TabsTranslation;

use  Neno\MarketingEssentials\Core\Content\Tabs\TabsEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class TabsTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $tabsId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $text;

    // tabsId
    public function getTabsId(): string
    {
        return $this->tabsId;
    }

    public function setTabsId(string $tabsId): void
    {
        $this->tabsId = $tabsId;
    }

    // name

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    // text

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
