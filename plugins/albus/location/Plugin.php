<?php namespace Albus\Location;

use Backend;
use System\Classes\PluginBase;
use Albus\Location\Classes\Import\ApiImport;
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
            'name' => 'Location',
            'description' => 'No description provided yet...',
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
        $import = new ApiImport();
        // $import->getRegions('RU');
        // $import->updateCitiesByCountry('RU');
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'Albus\Location\Components\LocationPicker' => 'LocationPicker',
            'Albus\Location\Components\Locations' => 'Locations',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return [
            'albus.location.main_permission' => [
                'tab' => 'Location',
                'label' => 'Основые права'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'location' => [
                'label' => 'Location',
                'url' => Backend::url('albus/location/cities'),
                'icon' => 'icon-leaf',
                'permissions' => ['albus.location.*'],
                'order' => 500,
                'sideMenu' => [
                    'cities' => [
                        'label' => 'Города',
                        'icon' => 'icon-copy',
                        'url' => Backend::url('albus/location/cities'),
                        'permissions' => ['albus.location.main_permission'],
                    ],
                    'regions' => [
                        'label' => 'Регионы',
                        'icon' => 'icon-copy',
                        'url' => Backend::url('albus/location/regions'),
                        'permissions' => ['albus.location.main_permission'],
                    ],
                    'countries' => [
                        'label' => 'Страны',
                        'icon' => 'icon-copy',
                        'url' => Backend::url('albus/location/countries'),
                        'permissions' => ['albus.location.main_permission'],
                    ],
                ]
            ],
        ];
    }
}
