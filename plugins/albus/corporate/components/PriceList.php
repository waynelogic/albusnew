<?php namespace Albus\Corporate\Components;

use Albus\Corporate\Models\ServiceCategory;
use Cms\Classes\ComponentBase;

/**
 * PriceList Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class PriceList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Прайс-лист',
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

    public $arItems;

    public function onRun() {
        $arCategories = ServiceCategory::whereHas('services', function ($query) {
            $query->whereNotNull('price');
        })->get();

        $this->arItems = $arCategories;
    }

    public function get() {
        return $this->arItems;
    }
}
