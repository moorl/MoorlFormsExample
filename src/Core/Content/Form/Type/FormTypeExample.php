<?php declare(strict_types=1);

namespace MoorlFormsExample\Core\Content\Form\Type;

use MoorlForms\Core\Content\Form\Type\FormTypeExtension;
use Shopware\Core\Content\Product\ProductDefinition;

class FormTypeExample extends FormTypeExtension
{
    /**
     * The name of the form type need to be unique
     */
    public const NAME = 'example';

    /**
     * For this example, we relate the form entity to the product
     */
    public const RELATED_ENTITY = ProductDefinition::ENTITY_NAME;

    public function getName(): string
    {
        return self::NAME;
    }

    public function getRelatedEntity(): ?string
    {
        return self::RELATED_ENTITY;
    }

    /**
     * @return string[]
     *
     * The options define, what the shop owner can do additional with the form
     * Here we want to build a form for product requests. So we can serve the options:
     * 1. mail - The shop owner will be informed via mail (Included in the base plugin)
     * 2. submit - Serve Options for a feedback message after the form was submitted (Included in the base plugin)
     * 3. newsletter - Serve NL registration (Classic Add-On needed)
     * 4. Stylesheet - Serve Options for the style of the form (Included in the base plugin)
     */
    public function getOptions(): array
    {
        return [
            'mail',
            'submit',
            'newsletter',
            'stylesheet'
        ];
    }
}
