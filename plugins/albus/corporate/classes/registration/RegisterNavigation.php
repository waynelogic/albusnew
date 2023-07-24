<?php namespace Albus\Corporate\Classes\Registration;

use Backend;

trait RegisterNavigation {
        /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'corporate' => [
                'label' => 'Корпорация',
                'url' => Backend::url('albus/corporate/services'),
                'icon' => 'icon-suitcase',
                'iconSvg'     => 'plugins/albus/corporate/assets/images/corp.svg',
                'permissions' => ['albus.corporate.main'],
                'order' => 500,
                'sideMenu' => [
                    'banners' => [
                        'label'       => 'Баннеры',
                        'icon'        => 'icon-file-image-o',
                        'url'         => Backend::url('albus/corporate/banners'),
                        'permissions' => ['albus.corporate.main']
                    ],
                    'services' => [
                        'label'       => 'Услуги',
                        'icon'        => 'icon-book',
                        'url'         => Backend::url('albus/corporate/services'),
                        'permissions' => ['albus.corporate.main']
                    ],
                    'employees' => [
                        'label'       => 'Сотрудники',
                        'icon'        => 'icon-users',
                        'url'         => Backend::url('albus/corporate/employees'),
                        'permissions' => ['albus.corporate.main']
                    ],
                    'departments' => [
                        'label'       => 'Отделы',
                        'icon'        => 'icon-cube',
                        'url'         => Backend::url('albus/corporate/departments'),
                        'permissions' => ['albus.corporate.main']
                    ],
                    'jobs' => [
                        'label'       => 'Вакансии',
                        'icon'        => 'icon-user-plus',
                        'url'         => Backend::url('albus/corporate/jobs'),
                        'permissions' => ['albus.corporate.main']
                    ],
                    'projects' => [
                        'label'       => 'Проекты',
                        'icon'        => 'icon-folder-open-o',
                        'url'         => Backend::url('albus/corporate/projects'),
                        'permissions' => ['albus.corporate.main']
                    ],
                    'partners' => [
                        'label'       => 'Контрагенты',
                        'icon'        => 'icon-address-book-o',
                        'url'         => Backend::url('albus/corporate/partners'),
                        'permissions' => ['albus.corporate.main']
                    ],
                ]
            ],
        ];
    }
}