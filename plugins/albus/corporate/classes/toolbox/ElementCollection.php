<?php namespace Albus\Corporate\Classes\Toolbox;

use Iterator;
use ArrayIterator;
use October\Rain\Extension\Extendable;
/**
 * Class ElementCollection
 * @package Lovata\Toolbox\Classes\Collection
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link    https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection
 */
class ElementCollection implements Iterator
{
    const COUNT_PER_PAGE = 10;
    
    public $sItemClass;

    protected $iPosition = 0;

    /** @var array */
    protected $arElementIDList = [];

    /** @var int Skip element count, used in "take" method */
    protected $iSkip = 0;

    public static function make($arElementIDList = [], $sItemClass)
    {
        /** @var $this $obCollection */
        $obCollection = app()->make(static::class);
        
        $obCollection->sItemClass = $sItemClass;
        
        if (!empty($arElementIDList)) {
            if (!is_array($arElementIDList)) {
                $arElementIDList = [$arElementIDList];
            }
            $obCollection->arElementIDList = $arElementIDList;
        }

        return $obCollection->returnThis();
    }

    public function returnThis()
    {
        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->arElementIDList);
    }

    public function active()
    {
        $this->arElementIDList = $this->sItemClass::where('active', true)->pluck('id')->toArray();
        return $this->returnThis();
    }

    public function category($idCategory) {
        $arRequiredItems = $this->sItemClass::where('category_id', $idCategory)->pluck('id')->toArray();

        $this->arElementIDList = array_intersect_key($arRequiredItems, $this->arElementIDList);

        return $this->returnThis();
    }

    public function all()
    {
        if ($this->isEmpty()) {
            return [];
        }
        $sItemClass = $this->sItemClass;

        $arResult = $sItemClass::whereIn('id', $this->arElementIDList)->get();

        return $arResult;
    }
    /**
     * Get an iterator for the items.
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }

    public function count(): int
    {
        if ($this->isEmpty()) {
            return 0;
        }
        return count((array) $this->arElementIDList);
    }

    public function makeItem($iElementID) {
        return $this->sItemClass::whereId($iElementID)->first();
    }

    public function rewind()
    {
        reset($this->arElementIDList);
    }
  
    public function current()
    {
        $iElementID = current($this->arElementIDList);
        return $this->makeItem($iElementID);
    }
  
    public function key() 
    {
        return key($this->arElementIDList);
    }
  
    public function next() 
    {
        return next($this->arElementIDList);
    }
  
    public function valid()
    {
        $key = key($this->arElementIDList);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}
