<?php namespace Albus\Corporate\Models;

use Model;
use System\Models\File;

use Albus\Corporate\Models\Employee;
use Albus\Corporate\Models\ServiceCategory;
use Albus\Corporate\Models\Service;
use Albus\Corporate\Models\Partner;

/**
 * Project Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_projects';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * File Attachments
     *
     * @var array
     */
    public $attachOne = [
        'cover' => File::class,
    ];
    public $attachMany = [
        'gallery' => File::class
    ];


    /**
     * Relationships
     *
     * @var array
     */
    public $belongsTo = [
        'service_category' => ServiceCategory::class
    ];    
    public $hasOne = [
        'partner' => Partner::class
    ];
    public $belongsToMany = [
        'services' => [
            Service::class,
            'table' => 'albus_corp_project_servicecat',
            'pivotSortable' => 'sort_order',
        ],
        'employees' => [
            Employee::class,
            'table' => 'albus_corp_project_employee',
            'pivotSortable' => 'sort_order',
        ],
    ];
}
