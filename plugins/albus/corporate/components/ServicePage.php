<?php namespace Albus\Corporate\Components;

use Cms\Classes\ComponentBase;

use Albus\Corporate\Models\Service;
/**
 * ServicePage Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class ServicePage extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Услуги - страница элемента',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'   => 'Категория',
                'description'   => 'Выберите категорию',
                'type'    => 'string',
                'default' => '{{ :slug }}',
            ],
        ];
    }

    public $obItem;

    public function onRun()
    {
        $obService = Service::where('slug', $this->property('slug'))->first();
        $this->obItem = $obService;
        $this->page->title = $obService->name;
    }

    public function get() {
        return $this->obItem;
    }
}
