<?php namespace Albus\Corporate\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Partners Backend Controller
 *
 * @link https://docs.octobercms.com/3.x/extend/system/controllers.html
 */
class Partners extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array required permissions
     */
    public $requiredPermissions = ['albus.corporate.partners'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Albus.Corporate', 'corporate', 'partners');

        $this->addCss('/plugins/albus/corporate/assets/css/flexible-chebox-list.css');
    }
}
