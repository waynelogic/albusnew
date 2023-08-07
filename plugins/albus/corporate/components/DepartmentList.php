<?php namespace Albus\Corporate\Components;

use Cms\Classes\ComponentBase;
use Albus\Corporate\Models\Department;


/**
 * DepartmentList Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class DepartmentList extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'Отделы  - Список',
            'description' => 'Выводит список всех отделов с сортировкой'
        ];
    }

    public $arItems;

    public function init() {
        $arDepartments = Department::get();
        $this->arItems = $arDepartments;
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

    public function get() {
        return $this->arItems;
    }
}
