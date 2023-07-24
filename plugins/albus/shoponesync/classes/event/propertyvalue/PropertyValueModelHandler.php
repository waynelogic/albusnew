<?php namespace Albus\ShopOneSync\Classes\Event\PropertyValue;

use Lovata\Toolbox\Classes\Event\ModelHandler;
use Lovata\PropertiesShopaholic\Models\PropertyValue;
use Albus\ShopWithOne\Classes\Item\PropertyValueItem;
use Albus\ShopWithOne\Classes\Store\PropertyValueListStore;

/**
 * Class PropertyValueModelHandler
 * @package Albus\ShopWithOne\Classes\Event\PropertyValue
 */
class PropertyValueModelHandler extends ModelHandler
{
    /** @var PropertyValue */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $sModelClass = $this->getModelClass();
        $sModelClass::extend(function ($obElement) {
            $obElement->fillable[] = 'external_id';
            $obElement->addCachedField(['external_id']);
        });
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return PropertyValue::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return PropertyValueItem::class;
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
