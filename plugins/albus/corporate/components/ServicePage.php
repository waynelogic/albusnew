<?php namespace Albus\Corporate\Components;

use Albus\Corporate\Classes\Component\ElementPage;
use Albus\Corporate\Models\Service;
/**
 * ServicePage Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class ServicePage extends ElementPage
{
    const ITEM_CLASS = Service::class;

    public function componentDetails()
    {
        return [
            'name' => 'Услуги - страница элемента',
            'description' => 'No description provided yet...'
        ];
    }

    public function getElementObject()
    {
        $this->obElement = static::ITEM_CLASS::where('slug', $this->sElementSlug)->first();
    }
}
