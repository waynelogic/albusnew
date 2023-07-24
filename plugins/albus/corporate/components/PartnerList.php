<?php namespace Albus\Corporate\Components;

use Cms\Classes\ComponentBase;
use Albus\Corporate\Models\Partner;
/**
 * PartnerList Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class PartnerList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Партнеры - список',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [];
    }

    public $arPartnerList;

    public function onRun () {
        $this->arPartnerList = Partner::all();
    }
    public function get() {
        return $this->arPartnerList;
    }
}
