<?php declare(strict_types=1);

namespace Huebert\SeoFaq\DataResolver;

use Huebert\SeoFaq\Core\Content\SeoFaqGroup\SeoFaqGroupDefinition;
use Huebert\SeoFaq\Core\Content\SeoFaqGroup\SeoFaqGroupEntity;
use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\SeoFaqQuestionsDefinition;
use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\SeoFaqQuestionsEntity;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class HuebFaqCmsElementResolver extends AbstractCmsElementResolver
{
    private $seoFaqQuestionsRepository;

    public function __construct(
        EntityRepository $seoFaqQuestionsRepository
    ){
        $this->seoFaqQuestionsRepository = $seoFaqQuestionsRepository;
    }

    public function getType(): string
    {
        return 'hueb-faq-element';
    }
    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $collection = new CriteriaCollection();
        $groupId = $slot->getConfig()['group']['value'];

        $groupCriteria = new Criteria([$groupId]);
        $faqCriteria = (new Criteria())
        ->addFilter(new EqualsFilter('group', $groupId));

        $collection->add('faqGroup', SeoFaqGroupDefinition::class, $groupCriteria);
        $collection->add('faqQuestions', SeoFaqQuestionsDefinition::class, $faqCriteria);

        return $collection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $slot->assign([
            'faqs' => $result->get('faqQuestions')->getElements(),
            'group' => $result->get('faqGroup')->first()
        ]);
    }
}
