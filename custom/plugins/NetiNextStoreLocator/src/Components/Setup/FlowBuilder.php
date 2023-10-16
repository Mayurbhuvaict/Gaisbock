<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\Setup;

use NetInventors\NetiNextStoreLocator\Constants\FlowConstants;
use Shopware\Core\Content\Flow\FlowEntity;
use Shopware\Core\Content\MailTemplate\MailTemplateActions;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FlowBuilder
{
    protected EntityRepository $flowRepository;

    public function __construct(
        ContainerInterface       $container,
        protected InstallContext $context,
        protected MailTemplate   $mailTemplate
    ) {
        /** @var EntityRepository flowRepository */
        $this->flowRepository = $container->get('flow.repository');
    }

    public function createFlows(): void
    {
        // If flows are already installed, they do not need to be installed again.
        $flows = $this->getFilteredFlows();
        if ([] === $flows) {
            return;
        }

        $mailTemplates = [];
        foreach ($flows as $flow) {
            $mailTemplates = [ ...$mailTemplates, ...$flow ];
        }

        /** @var list<string> $mailTemplates */
        $mailTemplates = array_unique($mailTemplates);

        $data                  = [];
        $mailTemplatesEntities = $this->mailTemplate->getMailTemplates($mailTemplates, $this->context->getContext());
        foreach ($flows as $flowName => $mailTechnicalNames) {
            foreach ($mailTechnicalNames as $technicalName) {
                $mailTemplateEntity = $mailTemplatesEntities[$technicalName];

                $data[] = [
                    'id'        => Uuid::randomHex(),
                    'name'      => $flowName,
                    'eventName' => $flowName,
                    'active'    => true,
                    'sequences' => [
                        [
                            'id'           => Uuid::randomHex(),
                            'actionName'   => MailTemplateActions::MAIL_TEMPLATE_MAIL_SEND_ACTION,
                            'config'       => [
                                'recipient'          => [
                                    'data' => [],
                                    'type' => 'default',
                                ],
                                'mailTemplateId'     => $mailTemplateEntity->getId(),
                                'mailTemplateTypeId' => $mailTemplateEntity->getMailTemplateTypeId(),
                            ],
                            'displayGroup' => 1,
                        ],
                    ],
                ];
            }
        }

        $this->flowRepository->create($data, $this->context->getContext());
    }

    /**
     * @return array<string, list<string>>
     */
    protected function getFilteredFlows(): array
    {
        $flows = FlowConstants::EVENTS;

        $flowEntities = $this->flowRepository->search(
            (new Criteria())->addFilter(
                new EqualsAnyFilter('eventName', \array_keys($flows))
            ),
            $this->context->getContext()
        );

        /** @var FlowEntity $entity */
        foreach ($flowEntities->getElements() as $entity) {
            unset($flows[$entity->getEventName()]);
        }

        return $flows;
    }

    public function deleteFlows(): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('eventName', \array_keys(FlowConstants::EVENTS)));

        $context = $this->context->getContext();
        $flowIds = $this->flowRepository->searchIds($criteria, $context)->getIds();

        $data = \array_map(
            static fn ($id) => [ 'id' => $id ],
            $flowIds
        );

        if ([] === $data) {
            return;
        }

        $this->flowRepository->delete($data, $context);
    }
}
