<?php namespace Albus\ShopWithOne\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\ModelHandler;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\ProductListStore;


use Lovata\Shopaholic\Models\Offer;
/**
 * Class ProductModelHandler
 * @package Albus\ShopWithOne\Classes\Event\Product
 */
class ProductModelHandler extends ModelHandler
{
    /** @var Product */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $sModelClass = $this->getModelClass();
        $sModelClass::extend(function ($obElement) {

            $obElement->fillable[] = 'type';
            $obElement->addCachedField(['type']);

            $obElement->fillable[] = 'has_offers';
            $obElement->addCachedField(['has_offers']);

            $obElement->addDynamicMethod('getTypeOptions', function() use ($obElement) {
                return [
                    '1'  => ['Товар', '#85CB43'],
                    '2'  => ['Услуга', '#e67e21'],
                    null => ['Нет', '#bdc3c7'],
                ];
            });
        });
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Product::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return ProductItem::class;
    }
    /**
     * After create event handler
     */
    protected function afterCreate()
    {
        parent::afterCreate();
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
    }
}
