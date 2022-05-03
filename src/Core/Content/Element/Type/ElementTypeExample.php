<?php declare(strict_types=1);

namespace MoorlFormsExample\Core\Content\Element\Type;

use MoorlForms\Core\Content\Element\ElementDataStruct;
use MoorlForms\Core\Content\Element\ElementEntity;
use MoorlForms\Core\Content\Element\Type\Fields\ElementGroupFields;
use MoorlForms\Core\Content\Element\Type\Fields\ElementTypeText;
use MoorlForms\MoorlForms;

class ElementTypeExample extends ElementGroupFields
{
    public const NAME = 'example';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @return array
     *
     * Add your on options.
     * File: src/Resources/app/administration/src/extension/element-settings/options/example/index.js
     */
    public function getOptions(): array
    {
        return array_merge(parent::getOptions(), ['example']);
    }

    /**
     * @param ElementEntity $element
     *
     * Every element can be initialized, for example to enrich configs or test compatibilities.
     */
    public function init(ElementEntity $element): void
    {
        parent::init($element);

        /**
         * Change type of the element if SOAPClient is not available.
         */
        if (!class_exists("SOAPClient")) {
            $element->setType(ElementTypeText::NAME);

            /**
             * Change properties based on the options.
             */
            $element->setTranslated(array_merge(
                $element->getTranslated(),
                [
                    'placeholder' => 'SOAPClient not installed',
                    'name' => 'SOAPClient not installed',
                ]
            ));
        } else {
            /**
             * Change properties based on the options.
             */
            $element->setTranslated(array_merge(
                $element->getTranslated(),
                [
                    'placeholder' => $element->getConfigData()->get('exampleValue'),
                    'tooltip' => $element->getTranslated()['custom1']
                ]
            ));
        }
    }

    /**
     * @param ElementDataStruct $element
     * @param array|null $tree
     * @throws \Exception
     *
     * After the form has been submitted, the element can be individually validated.
     * This example is included in "Classic Add-On" and checks for a valid TAX-ID.
     */
    public function validate(ElementDataStruct $element, ?array $tree = null): void
    {
        parent::validate($element);

        if (!class_exists("SOAPClient")) {
            throw new \Exception("PHP extension SOAPClient not installed");
        }

        $vat = preg_replace("/[^a-zA-Z0-9]]/", "", $element->getValue());
        $vatRegex = "/^[a-z]{2}[a-z0-9]{0,12}$/i";

        if (preg_match($vatRegex, $vat) !== 1) {
            throw new \Exception("invalidFormat", MoorlForms::MSG_CODE);
        }

        $client = new \SoapClient("https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");

        $params = [
            'countryCode' => substr($vat, 0, 2),
            'vatNumber' => substr($vat, 2),
        ];

        $result = $client->checkVatApprox($params);

        if ($result->valid) {
            return;
        }

        throw new \Exception("invalidTaxIdNumber", MoorlForms::MSG_CODE);
    }
}
