<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Api\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['api']])]

class OverviewController extends AbstractController
{
    public function __construct(
        private Connection $connection
    ) {
    }

    #[Route(path: '/api/_action/lae-giftcard/overview', name: 'api.action.lae-giftcard.overview', methods: ['GET'])]
    public function overview(): Response
    {
        return $this->json([
            'activeCards' => (int)$this->connection->fetchOne('SELECT COUNT(*) FROM lae_giftcard WHERE balance > 0'),
            'boughtLastMonth' => (int)$this->connection->fetchOne('SELECT SUM(initial_amount) FROM lae_giftcard WHERE DATE(created_at) >= DATE_ADD(CURDATE(), INTERVAL -30 DAY)'),
            'redeemedLastMonth' => (float)$this->connection->fetchOne('SELECT SUM(amount) FROM lae_giftcard_transaction WHERE amount > 0 AND DATE(created_at) >=DATE_ADD(CURDATE(), INTERVAL -30 DAY)'),
            'totalUnredeemed' => (float)$this->connection->fetchOne('SELECT SUM(balance) FROM lae_giftcard WHERE balance > 0'),
        ]);
    }
}
