<?php namespace Albus\Corporate\Components;

use Albus\Corporate\Classes\Component\SortingElementList;
use Albus\Corporate\Classes\Toolbox\ElementCollection;
use Albus\Corporate\Models\Service;
use Albus\Corporate\Classes\Helpers\PagesWithComponent;
use Albus\Corporate\Models\ServiceCategory;

/**
 * ServiceList Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class ServiceList extends SortingElementList
{
    use PagesWithComponent;

    public function componentDetails()
    {
        return [
            'name' => 'Услуги - Список',
            'description' => 'Вывод списка услуг'
        ];
    }

    public function defineProperties()
    {
        return [
            'servicePage' => [
                'title' => 'Страница элемента категории',
                'type' => 'dropdown',
            ],
            'slug' => [
                'title' => 'Фильтр категорий',
                'type' => 'string',
                'default' => '{{ :category }}'
            ]
        ];
    }

    public function getServicePageOptions() {
        return $this->pagesWithComponent('ServicePage');
    }


    public $arItems;

    public function onRun() {
        $category = ServiceCategory::where('slug', $this->property('slug'))->first();
        $arServices = Service::query();
        if (!empty($category)) {
            $arServices->where('category_id', $category->id);
        }
        $arServices = $arServices->get();

        // $arServices->each(function($category) {
        //     $category->url = $this->controller->pageUrl($this->property('categoryPage'), ['category' => $category->slug]);
        // });
        $this->arItems = $arServices;
    }

    public function get() {
        return $this->arItems;
    }
}
