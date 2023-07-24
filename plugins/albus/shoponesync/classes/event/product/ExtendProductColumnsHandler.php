<?php namespace Albus\ShopWithOne\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractBackendColumnHandler;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Controllers\Products;

/**
 * Class ExtendProductColumnsHandler
 * @package Albus\ShopWithOne\Classes\Event\Product
 */
class ExtendProductColumnsHandler extends AbstractBackendColumnHandler
{
    /**
     * Extend columns model
     * @param \Backend\Widgets\Lists $obWidget
     */
    protected function extendColumns($obWidget)
    {
        $this->removeColumn($obWidget);
        $this->addColumn($obWidget);
    }

    /**
     * Remove columns model
     * @param \Backend\Widgets\Lists $obWidget
     */
    protected function removeColumn($obWidget)
    {
        $obWidget->removeColumn('');
    }

    /**
     * Add columns model
     * @param \Backend\Widgets\Lists $obWidget
     */
    protected function addColumn($obWidget)
    {
        $obWidget->addColumns([
            'preview_image' => [
                'label' => 'Обложка',
                'type' => 'image',
                'sortable' => false,
            ],
            'type' => [
                'label' => 'Тип',
                'type' => 'selectable',
            ]
        ]);
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
