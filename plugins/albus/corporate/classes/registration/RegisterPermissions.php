<?php namespace Albus\Corporate\Classes\Registration;

trait RegisterPermissions {
    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return [
            'albus.corporate.main' => [
                'tab' => 'Corporate',
                'label' => 'Основные права'
            ],
        ];
    }
}