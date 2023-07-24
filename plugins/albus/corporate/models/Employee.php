<?php namespace Albus\Corporate\Models;

use Model;
use System\Models\File;
use Albus\Corporate\Models\Department;

/**
 * Employee Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Employee extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_employees';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = [ 'slug' => 'full_name' ];

    public $appends = ['full_name'];

    public function getFullNameAttribute() {
        $arFullname = [
            $this->last_name,
            $this->name, 
            $this->middle_name,
        ];
        return rtrim(implode(' ', $arFullname));
    }

    /**
     * File Attachments
     *
     * @var array
     */
    public $attachOne = [
        'cover' => File::class,
        'avatar' => File::class
    ];
    
    /**
     * Relationships
     *
     * @var array
     */
    public $belongsTo = [
        'department' => Department::class
    ];
    public $belongsToMany = [
        'categories' => [
            Category::class,
            'table' => 'rainlab_blog_posts_categories',
            'order' => 'name'
        ]
    ];
}
