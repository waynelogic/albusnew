<?php namespace Albus\Corporate\Classes\Registration;

trait RegisterComponents {

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'Albus\Corporate\Components\Slider' => 'Slider',

            'Albus\Corporate\Components\ServiceList' => 'ServiceList',
            'Albus\Corporate\Components\ServicePage' => 'ServicePage',

            'Albus\Corporate\Components\ServiceCategoryList' => 'ServiceCategoryList',
            'Albus\Corporate\Components\ServiceCategoryPage' => 'ServiceCategoryPage',
            
            'Albus\Corporate\Components\EmployeeList' => 'EmployeeList',

            'Albus\Corporate\Components\DepartmentList' => 'DepartmentList',

            'Albus\Corporate\Components\PartnerList' => 'PartnerList',
        ];
    }
}