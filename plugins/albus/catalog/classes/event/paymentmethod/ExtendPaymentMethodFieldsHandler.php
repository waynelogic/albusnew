<?php namespace Albus\Catalog\Classes\Event\PaymentMethod;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;
use Lovata\OrdersShopaholic\Models\PaymentMethod;
use Lovata\OrdersShopaholic\Controllers\PaymentMethods;

/**
 * Class ExtendPaymentMethodFieldsHandler
 * @package Albus\Catalog\Classes\Event\PaymentMethod
 */
class ExtendPaymentMethodFieldsHandler extends AbstractBackendFieldHandler
{
    /**
     * Extend fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        $this->removeField($obWidget);
        $this->addField($obWidget);
    }

    /**
     * Remove fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function removeField($obWidget)
    {
        $obWidget->removeField('');
    }

    /**
     * Add fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function addField($obWidget)
    {
        $obWidget->addTabFields([
            'icon' => [
                'label' => 'Иконка',
                'type' => 'fileupload',
                'fileTypes' => 'svg'
            ],
        ]);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return PaymentMethod::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return PaymentMethods::class;
    }
}
