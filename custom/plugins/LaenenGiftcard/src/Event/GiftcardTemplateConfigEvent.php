<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Event;

use Laenen\Giftcard\Content\Giftcard\GiftcardEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class GiftcardTemplateConfigEvent
{
    private array $config;

    public function __construct(
        private GiftcardEntity $giftcardEntity,
        private string $template,
        private ProductEntity $product,
        private SalesChannelContext $context
    ){
        $this->config = [];
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getGiftcardEntity(): GiftcardEntity
    {
        return $this->giftcardEntity;
    }

    public function setGiftcardEntity(GiftcardEntity $giftcardEntity): void
    {
        $this->giftcardEntity = $giftcardEntity;
    }

    public function getProduct(): ProductEntity
    {
        return $this->product;
    }

    public function setProduct(ProductEntity $product): void
    {
        $this->product = $product;
    }

    public function getContext(): SalesChannelContext
    {
        return $this->context;
    }

    public function setContext(SalesChannelContext $context): void
    {
        $this->context = $context;
    }
}
