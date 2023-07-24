<?php namespace Albus\Corporate\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Services Backend Controller
 *
 * @link https://docs.octobercms.com/3.x/extend/system/controllers.html
 */
class Services extends Controller
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
    // public $listConfig = 'config_services_list.yaml';
    public $listConfig = [
        'services' => 'config_list_services.yaml',
        'categories' => 'config_list_categories.yaml',
    ];

    /**
     * @var array required permissions
     */
    public $requiredPermissions = ['albus.corporate.services'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        $this->vars['mode'] = false;

        if (post('categories_mode')) {
            $this->vars['mode'] = 'categories';
            $this->formConfig = 'config_list_categories.yaml';
        }

        parent::__construct();

        BackendMenu::setContext('Albus.Corporate', 'corporate', 'services');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
        $this->bodyClass = 'compact-container';
    }
}
