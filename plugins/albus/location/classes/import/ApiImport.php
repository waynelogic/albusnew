<?php namespace Albus\Location\Classes\Import;

use Albus\Location\Models\Country;
use Albus\Location\Models\Region;
use Albus\Location\Models\City;

class ApiImport {
    const API_URL = 'http://api.geonames.org/searchJSON';
    const API_USERNAME = 'waynelogic';

    public function getRegions($countryCode) {
        $params = [
            'country' => $countryCode,
            'featureCode' => 'ADM1',
            'lang' => 'ru',
            'username' => self::API_USERNAME
        ];
        $url = self::API_URL . '?' . http_build_query($params);
        $response = file_get_contents($url);
        $regions = json_decode($response)->geonames;
        return $regions;
    }

    public function getDataFromApi($params) {
        $url = self::API_URL . '?' . http_build_query($params); 
        $response = file_get_contents($url);
        $data = json_decode($response)->geonames;

        return $data;
    }

    public function updateRegionsByCountry($countryCode) {
        if (!$countryCode) {
            return;
        }
        $regions = $this->getRegions($countryCode);
        $intCountryId = Country::where('code', $countryCode)->value('id');
        foreach ($regions as $region) {
            if (empty($region->adminCodes1->ISO3166_2)) {
                return;
            }
            $regionModel = Region::firstOrNew(['name' => $region->name]);
            $regionModel->name = $region->name;
            $regionModel->iso = $region->adminCodes1->ISO3166_2;
            $regionModel->country_id = $intCountryId;
            $regionModel->save();
        }
    }

    public function updateCitiesByCountry($countryCode) {
        if (!$countryCode) {
            return;
        }
        $intCountryId = Country::where('code', $countryCode)->value('id');
        
        $arRegions = Region::where('country_id', $intCountryId)->pluck('id', 'name')->toArray();
        $params = [
            'country' => $countryCode,
            'featureClass' => 'P',
            'maxRows' => 1000,
            'lang' => 'ru',
            'username' => self::API_USERNAME
        ];

        $arCities = $this->getDataFromApi($params);
        // dd($arCities);
        foreach ($arCities as $city) {
            $obCity = City::firstOrNew(['name' => $city->name]);
            $obCity->name = $city->name;
            $obCity->iso = !empty($city->adminCodes1->ISO3166_2) ? $city->adminCodes1->ISO3166_2 : '' ;
            $obCity->country_id = $intCountryId;
            $obCity->region_id = isset($arRegions[$city->adminName1]) ? $arRegions[$city->adminName1] : null;
            $obCity->save();
        }

    }
}