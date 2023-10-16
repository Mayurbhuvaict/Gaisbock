<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Exception;

use Shopware\Core\Framework\ShopwareHttpException;
use Symfony\Component\HttpFoundation\Response;

class NoGiftcardsFoundException extends ShopwareHttpException
{
    public function __construct()
    {
        parent::__construct('No giftcards found in given cart.');
    }

    public function getErrorCode(): string
    {
        return 'CHECKOUT__CART_GIFTCARDS_NOT_FOUND';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
