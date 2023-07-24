<?php namespace Albus\Corporate\Components;

use Albus\Corporate\Classes\Component\SortingElementList;
use Albus\Corporate\Classes\Toolbox\ElementCollection;
use Albus\Corporate\Models\Department;


/**
 * DepartmentList Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class DepartmentList extends SortingElementList
{
    /** @var array */
    protected $arPropertyList = [];

    const ITEM_CLASS = Department::class;

    public function componentDetails()
    {
        return [
            'name' => 'Отделы  - Список',
            'description' => 'Выводит список всех отделов с сортировкой'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $this->arPropertyList = [
            'sorting' => [
                'title'   => 'lovata.shopaholic::lang.component.product_list_sorting',
                'type'    => 'dropdown',
                'options' => [
                    'default'          => 'По умолчанию',
                    'name asc'         => 'Имя А..Я',
                    'name desc'        => 'Имя Я..А',
                    'created_at asc'   => 'Дата создания',
                    'created_at desc'  => 'Дата создания',
                    'updated_at asc'   => 'Дата создания',
                    'updated_at desc'  => 'Дата создания',
                ],
            ],
        ];

        return $this->arPropertyList;
    }

    public function make($arElementIDList = null)
    {
        return ElementCollection::make($arElementIDList, static::ITEM_CLASS);
    }

    /**
     * Method for ajax request with empty response
     * @deprecated
     * @return bool
     */
    public function onAjaxRequest()
    {
        return true;
    }
}
