<?php namespace Albus\ShopOneSync\Models;

use October\Rain\Database\Model;

use Lovata\OrdersShopaholic\Models\Status;
use Lovata\OrdersShopaholic\Models\OrderProperty;

/**
 * Settings Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsFields = 'fields.yaml';

    public $settingsCode = 'albus_shop_one_sync';

    /**
     * Get options array
     *
     * @param string $fieldName
     * @param mixed  $value
     * @param        $formData
     *
     * @return array
     */
    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if (in_array($fieldName, ['final_order_status_id', 'delivery_order_status_id'])) {
            return Status::orderBy('sort_order', 'asc')->get()->lists('name', 'id');
        } elseif (strpos($fieldName, '_property') !== false) {
            return OrderProperty::orderBy('sort_order', 'asc')->get()->lists('name', 'code');
        }

        return [];
    }
}
