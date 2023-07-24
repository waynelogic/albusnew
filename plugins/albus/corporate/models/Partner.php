<?php namespace Albus\Corporate\Models;

use Model;
use System\Models\File;

/**
 * Partner Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Partner extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_partners';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    protected $jsonable = ['relations'];
    /**
     * File Attachments
     *
     * @var array
     */
    public $attachOne = [
        'logo' => File::class,
    ];
    public $attachMany = [
        'gallery' => File::class
    ];

    public function getRelationsOptions() {
        return [
            'client' => 'Клиент',
            'provider' => 'Поставщик',
            'other' => 'Другое'
        ];
    }

    public function getTypeOptions() {
        return [
            'entity' => 'ООО',
            'entrepreneur' => 'ИП',
            'individual' => 'Физ. лицо',
        ];
    }
}
