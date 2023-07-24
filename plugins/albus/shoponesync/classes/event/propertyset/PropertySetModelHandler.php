<?php namespace Albus\ShopOneSync\Classes\Event\PropertySet;

use Lovata\Toolbox\Classes\Event\ModelHandler;
use Lovata\PropertiesShopaholic\Models\PropertySet;
use Lovata\PropertiesShopaholic\Classes\Item\PropertySetItem;
use Lovata\PropertiesShopaholic\Classes\Store\PropertySetListStore;

/**
 * Class PropertySetModelHandler
 * @package Albus\ShopWithOne\Classes\Event\PropertySet
 */
class PropertySetModelHandler extends ModelHandler
{
    /** @var PropertySet */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $sModelClass = $this->getModelClass();
        $sModelClass::extend(function ($obElement) {
            $obElement->fillable[] = 'is_global';
        });
    }


    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return PropertySet::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return PropertySetItem::class;
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
