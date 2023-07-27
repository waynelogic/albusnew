<?php namespace Albus\Location\Models;

use Model;
use Albus\Location\Models\Region;

/**
 * Country Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Country extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'albus_location_countries';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    public $hasMany = [
        'regions' => Region::class
    ];
}
