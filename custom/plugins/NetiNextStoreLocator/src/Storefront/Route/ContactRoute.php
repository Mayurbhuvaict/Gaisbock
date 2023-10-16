<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Route;

use NetInventors\NetiNextStoreLocator\Components\ContactForm\ContactForm;
use NetInventors\NetiNextStoreLocator\Components\FlowEvent\ContactCopyEvent as ContactCopyFlowEvent;
use NetInventors\NetiNextStoreLocator\Components\FlowEvent\ContactEvent as ContactFlowEvent;
use NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\ContactFormEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: [ '_routeScope' => [ 'store-api' ], '_contextTokenRequired' => true ])]
class ContactRoute
{
    public function __construct(
        private readonly ContactForm              $contactForm,
        private readonly EntityRepository         $storeRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @Since("4.1.0")
     */
    #[Route(path: '/store-api/store-locator/contact', name: 'store-api.store_locator.contact', methods: [ 'POST' ])]
    public function contact(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        $data   = $request->request->all();
        $fields = $this->contactForm->getFields($salesChannelContext);

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', (string) $data['storeId']));

        $result = $this->storeRepository->search($criteria, $salesChannelContext->getContext());
        $store  = $result->first();

        if (!$store instanceof StoreEntity) {
            return new JsonResponse(
                [
                    'success' => false,
                ]
            );
        }

        $values = [];
        $email  = null;
        $copy   = null;
        $files  = [];

        /** @var ContactFormEntity $field */
        foreach ($fields as $field) {
            /** @var bool|string|int $value */
            $value = $data[$field->getId()] ?? null;

            switch ($field->getType()) {
                case 'checkbox':
                    $value = $value ? 'Yes' : 'No'; // todo: Translate values
                    break;
                case 'email':
                    $email = $value;
                    break;
                case 'email_copy':
                    if ($value === 'true') {
                        $copy = true;
                    }
                    continue 2; // Skip value assignment
                case 'file_upload':
                    /** @var UploadedFile|null $file */
                    $file = $request->files->get($field->getId());

                    if ($file instanceof UploadedFile) {
                        $allowedExtensionsRaw = strtolower(trim($field->getValue()));
                        $allowedExtensions    = explode(',', $allowedExtensionsRaw);

                        // Invalid file extensions are skipped silently because there is also a client-side validation
                        if (
                            '' === $allowedExtensionsRaw
                            || in_array(
                                strtolower($file->getClientOriginalExtension()),
                                $allowedExtensions,
                                true
                            )
                        ) {
                            $files[] = [
                                'file' => $file,
                                'name' => $field->getLabel() . '.' . $file->getClientOriginalExtension(),
                            ];
                        }
                    }
                    continue 2;
            }

            $values[(string) $field->getTranslation('label')] = $value;
        }

        $events = [
            new ContactFlowEvent($salesChannelContext, $store, $values, [
                (string) $store->getNotificationMailAddress() => $store->getLabel(),
            ], $files),
        ];

        if ($copy && is_string($email)) {
            $events[] = new ContactCopyFlowEvent($salesChannelContext, $store, $values, [
                $email => $email,
            ], $files);
        }

        try {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        } catch (\Exception $ex) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => $ex->getMessage(),
                ]
            );
        }

        return new JsonResponse(
            [
                'success' => true,
            ]
        );
    }
}
