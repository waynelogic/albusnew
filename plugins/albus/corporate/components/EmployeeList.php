<?php namespace Albus\Corporate\Components;

use Cms\Classes\ComponentBase;
use Albus\Corporate\Models\Employee;

/**
 * EmployeeList Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class EmployeeList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Список сотрудников',
            'description' => 'No description provided yet...'
        ];
    }

    public $arEmployees;

    public function onRun()
    {
        $this->arEmployees = Employee::all();
    }

    public function get()
    {
        return $this->arEmployees;
    }
}
