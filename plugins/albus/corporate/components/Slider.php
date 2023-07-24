<?php namespace Albus\Corporate\Components;

use Cms\Classes\ComponentBase;
use Albus\Corporate\Models\Banner;
use Albus\Corporate\Models\BannerCategory;

/**
 * Slider Component
 */
class Slider extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Слайдер',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [
            'category' => [
                'title'   => 'Категория',
                'description'   => 'Выберите категорию',
                'type'    => 'dropdown',
            ],
            'items' => [
                'title'   => 'Количество',
                'description'   => 'Максимальное количество',
                'type'    => 'string',
            ],
        ];
    }

    public function getCategoryOptions()
    {
        return BannerCategory::lists('name', 'id');
    }

    public $arSlides;

    public function onRun() {
        $intCategory = $this->property('category');
        $intItems = $this->property('items');

        $arSlides = Banner::whereActive(true);
        if (isset($intCategory)) {
            $arSlides->where('category_id',$intCategory);
        }
        
        if (isset($intItems)) {
            $this->arSlides = $arSlides->take($intItems)->get();
        } else {
            $this->arSlides = $arSlides->get();
        }
    }

    public function get() {
        return $this->arSlides;
    }
}
