<?php namespace Albus\ShopWithOne\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Controllers\Products;

/**
 * Class ExtendProductFieldsHandler
 * @package Albus\ShopWithOne\Classes\Event\Product
 */
class ExtendProductFieldsHandler extends AbstractBackendFieldHandler
{

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        // Products::extend(function ($controller) {
        //     $path = 'plugins/albus/shopwithone/controllers/products/';
        //     // Extending the config_relation.yaml for the Rainlab.User plugin
        //     $controller->formConfig = $path . 'config_form.yaml';
        //     $controller->listConfig = $path . 'config_list.yaml';
        // });
        $obEvent->listen('backend.form.extendFields', function ($obWidget) {

            $sControllerClass = $this->getControllerClass();
            $sModelName = $this->getModelClass();

            /** @var \Backend\Widgets\Form $obWidget */
            if (!$obWidget->getController() instanceof $sControllerClass || $obWidget->isNested || empty($obWidget->context)) {
                return;
            }

            if (!$obWidget->model instanceof $sModelName) {
                return;
            }

            $this->extendFields($obWidget);
        }, $this->iPriority);
    }


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
        $obWidget->addTabFields([]);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Product::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Products::class;
    }
}
