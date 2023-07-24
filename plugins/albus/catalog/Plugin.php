<?php namespace Albus\Catalog;

use Backend;
use System\Classes\PluginBase;
use Albus\Catalog\Classes\Registration\Boot;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Albus - Каталог',
            'description' => 'Плагин для вывода каталога',
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


    use Boot;

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'Albus\Catalog\Components\Catalog' => 'Catalog',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'albus.catalog.some_permission' => [
                'tab' => 'Catalog',
                'label' => 'Some permission'
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
            'catalog' => [
                'label' => 'Catalog',
                'url' => Backend::url('albus/catalog/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['albus.catalog.*'],
                'order' => 500,
            ],
        ];
    }
}
