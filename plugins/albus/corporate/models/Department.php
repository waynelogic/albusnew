<?php namespace Albus\Corporate\Models;

use Model;
use Albus\Corporate\Models\Employee;
use Albus\Corporate\Models\Job;

/**
 * Department Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Department extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\NestedTree;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_departments';

    /**
     * @var array rules for validation
     */
    public $rules = [];

        
    /**
     * @var array of jsonable fields
     */
    protected $jsonable = ['phone', 'email'];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * Relationships
     *
     * @var array
     */
    public $hasMany = [
        'employees' => Employee::class,
        'jobs' => Job::class
    ];
}
