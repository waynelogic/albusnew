<?php namespace Albus\Location\Models;

use Model;
use Albus\Location\Models\Country;
use Albus\Location\Models\Region;

/**
 * City Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class City extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'albus_location_cities';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    protected $fillable = ['name', 'code', 'iso', 'geonames_id'];

    public $belongsTo = [
        'country' => Country::class,
        'region' => Region::class
    ];
}
