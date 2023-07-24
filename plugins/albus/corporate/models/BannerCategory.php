<?php namespace Albus\Corporate\Models;

use Model;

/**
 * BannerCategory Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class BannerCategory extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'albus_corporate_banner_categories';

    /**
     * @var array rules for validation
     */
    public $rules = [];
}
