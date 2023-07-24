<?php namespace Albus\Catalog\Classes\Event\PaymentMethod;

use Lovata\Toolbox\Classes\Event\ModelHandler;
use Lovata\OrdersShopaholic\Models\PaymentMethod;
use Albus\Catalog\Classes\Item\PaymentMethodItem;
use Albus\Catalog\Classes\Store\PaymentMethodListStore;

/**
 * Class PaymentMethodModelHandler
 * @package Albus\Catalog\Classes\Event\PaymentMethod
 */
class PaymentMethodModelHandler extends ModelHandler
{
    /** @var PaymentMethod */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $sModelClass = $this->getModelClass();
        $sModelClass::extend(function ($obElement) {
            $obElement->fillable[] = 'icon';
            $obElement->addCachedField(['icon']);
            $obElement->attachOne['icon'] = ['System\Models\File'];
        });
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return PaymentMethod::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return PaymentMethodItem::class;
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
