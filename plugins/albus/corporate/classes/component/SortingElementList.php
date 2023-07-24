<?php namespace Albus\Corporate\Classes\Component;

use Input;
use Cms\Classes\ComponentBase;

/**
 * Class SortingElementList
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class SortingElementList extends ComponentBase
{
    /** @var string Active sorting value */
    protected $sSorting;

    /**
     * Init start data
     */
    public function init()
    {
        $this->setActiveSorting();
        parent::init();
    }

    /**
     * Get active sorting
     * @return string
     */
    public function getSorting()
    {
        return $this->sSorting;
    }

    /**
     * Set active sorting
     */
    protected function setActiveSorting()
    {
        $this->sSorting = Input::get('sort');
        if (empty($this->sSorting)) {
            $this->sSorting = $this->property('sorting');
        }
    }

    /**
     * Method for ajax request with empty response
     * @deprecated
     * @return bool
     */
    public function onAjaxRequest()
    {
        return true;
    }
}
