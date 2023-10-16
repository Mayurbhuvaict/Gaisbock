<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Storefront\Controller;

use Huebert\SeoFaq\Core\Content\SeoFaqGroup\SeoFaqGroupCollection;
use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\SeoFaqQuestionsCollection;
use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\SeoFaqQuestionsEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Page\GenericPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class SeoFaqController extends StorefrontController
{
    private EntityRepository $questionsRepository;

    private EntityRepository $groupRepository;

    private GenericPageLoader $genericPageLoader;

    public function __construct(
        GenericPageLoader $genericPageLoader,
        EntityRepository $questionsRepository,
        EntityRepository $groupRepository
    )
    {
        $this->genericPageLoader = $genericPageLoader;
        $this->questionsRepository = $questionsRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @Route("/faq", name="frontend.seoFaq", options={"seo"="false"}, methods={"GET"})
     */
    public function seoFaqRaw(Request $request, SalesChannelContext $context): Response
    {
        $page = $this->genericPageLoader->load($request, $context);

        [$questionResult, $groupResult] = $this->assignVariables($context->getContext());

        $page->assign([
            'group' => $groupResult,
            'question' => $questionResult,
        ]);

        return $this->renderStorefront("@HuebertSeoFaq/storefront/seoFaq/seoFaq.html.twig", [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/faq/{question}", name="frontend.seoFaq.question", options={"seo"="false"}, methods={"GET"})
     */
    public function seoFaq(Request $request, SalesChannelContext $context, string $question): Response
    {
        $page = $this->genericPageLoader->load($request, $context);

        [$questionResult, $groupResult] = $this->assignVariables($context->getContext());

        $currentQuestion = $questionResult
            ->filter(function (SeoFaqQuestionsEntity $seoQuestion) use ($question) {
                if($seoQuestion->getQuestion() !== '' && is_string($seoQuestion->getQuestion() )){
                    return $this->encodeURLString($seoQuestion->getQuestion()) === $question;
                }
                return '';

            })
            ->first();

        $page->assign([
            'group' => $groupResult,
            'question' => $questionResult,
            'questionSpecific' => $currentQuestion,
        ]);

        return $this->renderStorefront("@HuebertSeoFaq/storefront/seoFaq/seoFaq.html.twig", [
            'page' => $page,
        ]);
    }

    private function assignVariables(Context $context): array
    {
        // Update 28.12.21 Added sorting by group and question position
        $questionCriteria = new Criteria();
        $questionCriteria->addSorting(new FieldSorting('questionPosition', FieldSorting::ASCENDING));

        /** @var SeoFaqQuestionsCollection $questionResult */
        $questionResult = $this->questionsRepository->search($questionCriteria, $context)->getEntities();

        foreach ($questionResult as $entity) {
            if($entity->getTranslation('question') !== '' && is_string($entity->getTranslation('question'))){
                $entity->setExtensions(['questionUrl' => $this->encodeURLString($entity->getTranslation('question'))]);
            }
        }

        $groupCriteria = new Criteria();
        $groupCriteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));

        /** @var SeoFaqGroupCollection $groupResult */
        $groupResult = $this->groupRepository->search($groupCriteria, $context)->getEntities();

        return [$questionResult, $groupResult];
    }

    private function encodeURLString(string $string): string
    {
        $encodedUrl = str_replace("_",
            "-",
            strtolower(preg_replace(
                ['/ä/', '/ö/', '/ü/', '/Ä/', '/Ö/', '/Ü/', '/ẞ/', '/ß/',],
                ['ae', 'oe', 'ue', 'ae', 'oe', 'ue', 'ss', 'ss',],
                $string
            )));

        $encodedUrl = preg_replace('/[^\da-z ]/i', '', $encodedUrl);

        return str_replace(' ', '-', $encodedUrl);
    }
}
