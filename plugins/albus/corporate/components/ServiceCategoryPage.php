<?php namespace Albus\Corporate\Components;

use Albus\Corporate\Models\ServiceCategory;
use Albus\Corporate\Classes\Component\ElementPage;

/**
 * ServiceCategoryPage Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class ServiceCategoryPage extends ElementPage
{
    public function componentDetails()
    {
        return [
            'name' => 'Категории услуг - Страница категории',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'Фильтр категорий',
                'type' => 'string',
                'default' => '{{ :category }}'
            ]
        ];
    }

    public $obItem;

    public function onRun() {
        $obCategory = ServiceCategory::where('slug', $this->property('slug'))->first();
        $this->page->title = $obCategory->name;
        $this->obItem = $obCategory;
    }

    public function get() {
        return $this->obItem;
    }

}
