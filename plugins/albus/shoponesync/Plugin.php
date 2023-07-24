<?php namespace Albus\ShopOneSync;

use Event;
use Backend;
use System\Classes\PluginBase;
use System\Classes\PluginManager;

use Albus\ShopOneSync\Classes\Event\PropertySet\PropertySetModelHandler;
use Albus\ShopOneSync\Classes\Event\PropertyValue\PropertyValueModelHandler;
/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{

    /** @var array Plugin dependencies */
    public $require = [
        'Lovata.Shopaholic',
        'Lovata.OrdersShopaholic',
        'Lovata.PropertiesShopaholic',
        'Lovata.FilterShopaholic'
    ];

    public function pluginDetails()
    {
        return [
            'name' => 'ShopOneSync',
            'description' => 'Плагин для синхронизации 1С и Lovata Shopaholic',
            'author' => 'Albus',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        if (PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            Event::subscribe(PropertySetModelHandler::class);
            Event::subscribe(PropertyValueModelHandler::class);
        }
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return [
            'albus.shoponesync.main_permission' => [
                'tab' => 'ShopOneSync',
                'label' => 'Основные права'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'shoponesync' => [
                'label' => 'ShopOneSync',
                'url' => Backend::url('albus/shoponesync/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['albus.shoponesync.*'],
                'order' => 500,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'exchange' => [
                'label' => 'Настройки обмена с 1С',
                'description' => 'Настройки обмена с 1С',
                'category' => 'Albus',
                'icon' => 'icon-pencil',
                'class' => 'Albus\ShopOneSync\Models\Settings',
                'order' => 100,
            ]
        ];
    }    
}
