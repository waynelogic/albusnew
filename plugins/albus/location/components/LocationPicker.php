<?php namespace Albus\Location\Components;

use Albus\Location\Models\City;
use Cms\Classes\ComponentBase;
use Input;
use Session;
use Redirect;
use Cms\Classes\Controller;
/**
 * LocationPicker Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class LocationPicker extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'LocationPicker Component',
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

    public $locationIsSet;

    public $currentLocation;

    public function init() {
        $sessionLocation = $this->sessionLocation();
        if (isset($sessionLocation)) {
            $this->currentLocation = $sessionLocation;
            $this->locationIsSet = true;
        } else {
            $this->currentLocation = City::where('id', 26)->first();    
            $this->locationIsSet = false;
        }
        // dd($this->currentLocation);
    }
    public function sessionLocation() {
        return City::where('id', Session::get('location'))->first();
    }

    public function onChangeLocation($newLocationId = null, $pageUrl = null) {
        if (empty($pageUrl)) {
            $pageUrl = Input::url();
        }
        if (!$newLocationId) {
            $newLocationId = Input::get('location');
        }
        Session::put('location', $newLocationId);
        return Redirect::to($pageUrl);
    }

    public function onConfirmLocation() {
        $this->onChangeLocation($this->currentLocation->id, Input::url());
    }
}
