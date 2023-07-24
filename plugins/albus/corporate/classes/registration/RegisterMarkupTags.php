<?php namespace Albus\Corporate\Classes\Registration;

trait RegisterMarkupTags {
    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'corp_phone' => [$this, 'phone'],
            ],
            // 'functions' => [
            //     // A static method call, i.e Form::open()
            //     'form_open' => [\October\Rain\Html\Form::class, 'open'],
    
            //     // Using an inline closure
            //     'helloWorld' => function() { return 'Hello World!'; }
            // ]
        ];
    }
    
    public function phone(string $string) : string
    {
        return str_replace(array(' ', '(' , ')', '-'), '', $string);
    }
}