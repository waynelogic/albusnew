<?php namespace Albus\Corporate\Models;

use Model;
use Albus\Corporate\Models\Department;

/**
 * Job Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Job extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_jobs';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * Relationships
     *
     * @var array
     */
    public $belongsTo = [
        'department' => Department::class
    ];

    /**
     * getStatusNameOptions
     */
    public function getTypeOptions()
    {
        return [
            'constant' => ['Постоянная', '#85CB43'],
            'combination' => ['Возможно совщемщение', '#bdc3c7'],
            'freelancer' => ['Фриланс', '#e67e21'],
        ];
    }
}
