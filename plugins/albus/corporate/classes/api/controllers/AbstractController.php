<?php namespace Albus\Corporate\Classes\Api\Controllers;

use Input;

abstract class AbstractController {

    public $json_data;

    public $element;

    public $method;

    public $params;

    public function __construct($element, $method)
    {
        $this->element = $element;
        $this->method = $method;
        $this->params = Input::all();
        $this->$element($method);
    }
}