<?php namespace Albus\Corporate\Classes\Component;

use System\Classes\PluginManager;
use Cms\Classes\ComponentBase;
use Albus\Corporate\Classes\Item\ElementItem;
/**
 * Class ElementPage
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementPage extends ComponentBase
{
    /** @var \Model */
    protected $obElement = null;

    public $sElementSlug;
    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Фильтр объектов',
                'type'        => 'string',
                'default'     => '',
            ],
        ];
    }

    /**
     * Проверка того что установлено свойство :slug?
     */
    public function onRun()
    {
        //Get element slug
        $sElementSlug = $this->property('slug');
        if (empty($sElementSlug)) {
            return null;
        }
        return null;
    }

    /**
     * Init plugin method
     */
    public function init()
    {
        //Get element slug
        $sElementSlug = $this->property('slug');
        if (empty($sElementSlug)) {
            return;
        }
        $this->sElementSlug = $sElementSlug;

        //Get element by slug
        $this->getElementObject();
        if (empty($this->obElement)) {
            return $this->controller->run('404');
        }
    }

    public function title($sName = null) {
        if (isset($this->obElement->cover)) {
            $this->page->cover = $this->obElement->cover->getPath();
        }
        if (!empty($sName) && isset($this->obElement->$sName)) {
            $this->page->title = $this->obElement->$sName;
        }
        return $this;
    }

    public function get()
    {
        return $this->obElement;
    }

    public function getElementObject(){
        return;
    }
}
