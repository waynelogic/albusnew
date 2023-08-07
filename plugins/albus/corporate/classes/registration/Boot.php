<?php namespace Albus\Corporate\Classes\Registration;

use Event;
use Yaml;
use Cms\Classes\Theme;
use Cms\Classes\Controller;
use Albus\Corporate\Models\ServiceCategory;
use Albus\Corporate\Models\Service;
use Albus\Corporate\Controllers\Services;
use RainLab\Pages\Classes\MenuItemReference;

trait Boot {
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        Event::listen('backend.form.extendFields', function($widget) {
            if (!$widget->model instanceof \RainLab\Pages\Classes\MenuItem) return;
    
            $widget->addTabFields([
                'viewBag[class2]' => [
                    'label' => 'Картинка',
                    'tab' => 'rainlab.pages::lang.menuitem.attributes_tab',
                    'type' => 'mediafinder',
                ],
            ]);
        });
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        // MenuItemReference::extend(function ($model) {
        //     dd($model);
        //     // $model->bindEvent('user.register', function () use ($model) {
        //     //     // Code to register $model->email to mailing list
        //     // });
        // });

        // Services::extend(function ($obController) {
        //     // Найди ID модели в backend контроллере October CMS
        //     dd($obController->model);
        //     $id = $obController->page->id;
        // });

        Event::listen('backend.form.extendFields', function($form) {
            if (
                !$form->getController() instanceof Services ||
                !$form->getModel() instanceof Service ||
                !isset($form->getModel()->id) ||
                $form->isNested
            ) {
                return;
            }
            if ($form->getModel()->category->properties_file_path) {
                $file_path = Theme::getActiveTheme()->getPath() . DIRECTORY_SEPARATOR . 'meta/services/' . $form->getModel()->category->properties_file_path;
                $form->addTabFields([
                    'content' => [
                        'tab' => 'Контент',
                        // 'label' => 'My Field',
                        'type' => 'repeater',
                        // 'comment' => 'This is a custom field I have added.',
                        'displayMode' => 'builder',
                        'prompt' => 'Добавить блок',
                        'groups' => $file_path
                    ],
                ]);
            }
        });
        Event::listen('pages.menuitem.listTypes', function() {
            return [
                'all-service-categories'=>'Все категории услуг',
                'service-category'=>'Категория услуг',
            ];
        });
        Event::listen('pages.menuitem.getTypeInfo', function($type) {
            if ($type == 'service-category' || $type == 'all-service-categories')
                return ServiceCategory::getMenuTypeInfo($type);
        });
    
        Event::listen('pages.menuitem.resolveItem', function($type, $item, $url, $theme) {
            if ($type == 'service-category' || $type == 'all-service-categories')
                return ServiceCategory::resolveMenuItem($item, $url, $theme);
        });
    }
}





