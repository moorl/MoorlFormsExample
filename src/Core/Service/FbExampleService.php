<?php declare(strict_types=1);

namespace MoorlFormsExample\Core\Service;

use MoorlForms\Core\Content\Form\FormCollection;
use MoorlForms\Core\Service\FbService;
use MoorlFormsExample\Core\Content\Form\Type\FormTypeExample;
use MoorlFormsExample\MoorlFormsExample;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class FbExampleService
{
    private FbService $fbService;
    private DefinitionInstanceRegistry $definitionInstanceRegistry;
    private SystemConfigService $systemConfigService;

    private ?FormCollection $forms = null;
    private ?SalesChannelContext $salesChannelContext = null;

    /**
     * @param FbService $fbService
     * @param DefinitionInstanceRegistry $definitionInstanceRegistry
     * @param SystemConfigService $systemConfigService
     */
    public function __construct(
        FbService $fbService,
        DefinitionInstanceRegistry $definitionInstanceRegistry,
        SystemConfigService $systemConfigService
    )
    {
        $this->fbService = $fbService;
        $this->definitionInstanceRegistry = $definitionInstanceRegistry;
        $this->systemConfigService = $systemConfigService;
    }

    public function enrichProduct(
        SalesChannelProductEntity $product,
        SalesChannelContext $salesChannelContext
    ): void
    {
        $this->salesChannelContext = $salesChannelContext;
        $this->initForms();
        if ($this->forms->count() === 0) {
            return;
        }

        foreach ($this->forms as $form) {
            $this->fbService->enrichFromEntity($form, $this->salesChannelContext, $product);
        }

        $product->addExtension(MoorlFormsExample::NAME, $this->forms);
    }

    private function initForms(): void
    {
        if ($this->forms) {
            return;
        }
        $this->forms = $this->fbService->initFormsByType(FormTypeExample::NAME, $this->salesChannelContext);
    }
}
