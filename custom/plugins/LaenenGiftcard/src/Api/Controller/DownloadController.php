<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Api\Controller;

use Laenen\Giftcard\Content\Giftcard\GiftcardEntity;
use Laenen\Giftcard\Document\GiftcardDocumentGenerator;
use Laenen\Giftcard\Service\GiftcardGateway;
use Laenen\Giftcard\Struct\GiftcardStruct;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\EntityNotFoundException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\Context\AbstractSalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
class DownloadController extends AbstractController
{
    public function __construct(
        private GiftcardGateway $giftcardGateway,
        private GiftcardDocumentGenerator $giftcardDocumentGenerator,
        private AbstractSalesChannelContextFactory $salesChannelContextFactory,
        private EntityRepository $giftcardRepository
    ) {
    }

    #[Route(path: '/api/_action/lae-giftcard/download/{giftcardId}', name: 'api.action.lae-giftcard.download', methods: ['GET'])]
    public function download(string $giftcardId, Context $context): Response
    {
        try {
            $giftcard = $this->giftcardGateway->getFlatById($giftcardId, $context);

            if (!$giftcard) {
                return new Response('Not found', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $salesChannelContext = $this->getSalesChannelContext($giftcard, $context);

            $couponPdf = $this->giftcardDocumentGenerator->generate(null, $giftcard, $salesChannelContext);
        } catch (\RuntimeException $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createResponse(
            'giftcard.pdf',
            $couponPdf
        );
    }

    private function createResponse(string $filename, string $content): Response
    {
        $response = new Response($content);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            $filename,
            preg_replace('/[\x00-\x1F\x7F-\xFF]/', '_', $filename)
        );

        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    private function getSalesChannelContext(GiftcardStruct $giftcardStruct, Context $context): SalesChannelContext
    {
        $giftcard = $this->giftcardRepository
            ->search(new Criteria([$giftcardStruct->getExternalId()]), $context)
            ->first();
        if (!$giftcard instanceof GiftcardEntity) {
            throw new EntityNotFoundException('lae_giftcard', $giftcardStruct->getExternalId());
        }

        return $this->salesChannelContextFactory->create(
            $giftcardStruct->getExternalId(),
            $giftcard->getSalesChannelId()
        );
    }
}
