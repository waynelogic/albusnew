<?php

// use Albus\Corporate\Classes\Api\Controllers\Services;
// use Albus\Corporate\Classes\Api\Controllers\Slider;

use Albus\Corporate\Models\Service;
use Albus\Corporate\Models\ServiceCategory;

Route::group(['prefix' => 'api'], function () {
    Route::any('service-categories/all', function ()  {
        return ServiceCategory::all();
    });

    Route::any('services/{element}/{method}', function ($element, $method) {
        $data = new Services($element, $method);
        return $data->json_data;
    });
    Route::any('slider/{method}', function ($method) {
        $obSlider = new Slider();
        return $obSlider->$method();
    });
});