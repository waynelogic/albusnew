<?php namespace Albus\Corporate\Models;

use Model;
use System\Models\File;
use Cms\Classes\Controller;

use Albus\Corporate\Models\ServiceCategory;
/**
 * Service Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_services';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];
    
    protected $jsonable = ['content', 'properties', 'steps', 'blocks'];

    /**
     * File Attachments
     *
     * @var array
     */
    public $attachOne = [
        'cover' => File::class,
        'background' => File::class,
        'image' => File::class
    ];
    public $attachMany = [
        'gallery' => File::class
    ];
    public $belongsTo = [
        'category' => [ ServiceCategory::class, 'key' => 'category_id']
    ];    

    public function getPriceTypeOptions() {
        return [
            'one' => ['Разовая', '#85CB43'],
            'month' => ['мес.', '#e67e21'],
            'hour' => ['ч.', '#e67e21'],
            'depends' => ['Зависит от объема работ', '#bdc3c7'],
            'from' => ['От', '#bdc3c7'],
        ];
    }

    public function getPageUrl($pageId) {
        $controller = Controller::getController();
        return $controller->pageUrl($pageId, [
            'category' => $this->category->slug,
            'slug' => $this->slug
        ]);
    }
}
