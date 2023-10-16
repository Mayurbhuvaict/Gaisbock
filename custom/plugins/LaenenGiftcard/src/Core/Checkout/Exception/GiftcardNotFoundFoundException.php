<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Exception;

use Shopware\Core\Framework\ShopwareHttpException;
use Symfony\Component\HttpFoundation\Response;

class GiftcardNotFoundFoundException extends ShopwareHttpException
{
    public function __construct(string $code)
    {
        parent::__construct('Giftcard with {{ code }} not found in given cart.', ['code' => $code]);
    }

    public function getErrorCode(): string
    {
        return 'CHECKOUT__CART_GIFTCARD_NOT_FOUND';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
