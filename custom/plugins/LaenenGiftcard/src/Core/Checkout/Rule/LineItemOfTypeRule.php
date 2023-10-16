<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Rule;

use Laenen\Giftcard\Core\Checkout\RedeemGiftcardCollector;
use Shopware\Core\Framework\Rule\RuleConfig;

class LineItemOfTypeRule extends \Shopware\Core\Checkout\Cart\Rule\LineItemOfTypeRule
{
    public function getConfig(): RuleConfig
    {
        $config = parent::getConfig()->getData();

        $newConfig = new RuleConfig();

        $newConfig->operatorSet($config['operatorSet']['operators'], false, $config['operatorSet']['isMatchAny']);

        foreach ($config['fields'] as $field) {
            if ($field['name'] === 'lineItemType') {
                $field['config']['options'][] = RedeemGiftcardCollector::GIFTCARDS;
            }

            $newConfig->field(
                $field['name'],
                $field['type'],
                $field['config']
            );
        }

        return $newConfig;
    }
}