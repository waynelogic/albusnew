<?php namespace Albus\Corporate\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;

use Albus\Corporate\Models\ServiceCategory;
use Albus\Corporate\Classes\Helpers\PagesWithComponent;

/**
 * ServiceCategories Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class ServiceCategoryList extends ComponentBase
{
    use PagesWithComponent;

    public function componentDetails()
    {
        return [
            'name' => 'Категории услуг - список категорий',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [
            'categoryPage' => [
                'title' => 'Страница элемента категории',
                'type' => 'dropdown',
            ],
        ];
    }

    public function getCategoryPageOptions() {
        return $this->pagesWithComponent('ServiceCategoryPage');
    }


    public $arItems;

    public function onRun() {
        $arCategories = ServiceCategory::all();
        $arCategories->each(function($category) {
            $category->url = $this->controller->pageUrl($this->property('categoryPage'), ['category' => $category->slug]);
        });
        $this->arItems = $arCategories;
    }

    public function get() {
        return $this->arItems;
    }
}
