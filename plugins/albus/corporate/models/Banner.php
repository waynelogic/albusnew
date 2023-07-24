<?php namespace Albus\Corporate\Models;

use Model;
use System\Models\File;
use Albus\Corporate\Models\BannerCategory;

/**
 * Banner Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Banner extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_banners';

    /**
     * @var array rules for validation
     */
    public $rules = [];


    protected $jsonable = ['buttons'];
    /**
     * File Attachments
     *
     * @var array
     */
    public $attachOne = [
        'background' => File::class,
        'image' => File::class,
    ];

    public $belongsTo = [
        'category' => [ BannerCategory::class, 'key' => 'category_id']
    ];

    public function getThemeOptions() {
        return [
            'light' => 'Светлая',
            'dark' => 'Темная',
        ];
    }
}
