<?php declare(strict_types=1);

namespace MoorlFormsExample\Storefront\Subscriber;

use MoorlFormsExample\Core\Service\FbExampleService;
use Shopware\Core\Framework\Routing\Event\SalesChannelContextResolvedEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExampleSubscriber implements EventSubscriberInterface
{
    private FbExampleService $fbExampleService;

    public function __construct(
        FbExampleService $fbExampleService
    )
    {
        $this->fbExampleService = $fbExampleService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPageLoadedEvent',
        ];
    }

    public function onProductPageLoadedEvent(ProductPageLoadedEvent $event): void
    {
        $this->fbExampleService->enrichProduct(
            $event->getPage()->getProduct(),
            $event->getSalesChannelContext()
        );
    }
}
