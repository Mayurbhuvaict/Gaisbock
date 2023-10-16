<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Exception;

use Shopware\Core\Framework\ShopwareHttpException;
use Symfony\Component\HttpFoundation\Response;

class UnsupportedGiftcardType extends ShopwareHttpException
{
    public function __construct(string $type, string $action)
    {
        parent::__construct('Giftcard type {{ type }} does not support the action {{ action }}.', [
            'type' => $type,
            'action' => $action
        ]);
    }

    public function getErrorCode(): string
    {
        return 'CHECKOUT__CART_GIFTCARD_TYPE_ACTION_UNSUPPORTED';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
