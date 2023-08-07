<?php namespace Albus\Location\Components;

use Cms\Classes\ComponentBase;
use Albus\Location\Models\City;
/**
 * Locations Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class Locations extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Locations',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [];
    }

    public function getCities() {
        $obCityList = City::where('active', true)->get();
        return $obCityList;
    }
}
