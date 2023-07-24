<?php namespace Albus\ShopOneSync\Classes\Import;

use SimpleXMLElement;
/** Lovata\PropertiesShopaholic */
use Lovata\PropertiesShopaholic\Models\Property;
use Lovata\PropertiesShopaholic\Models\PropertyValue;
use Lovata\PropertiesShopaholic\Models\PropertySet;

use Albus\ShopOneSync\Classes\Import\AbstractImport;

class Properties extends AbstractImport {

    public $obPropertySet;

    public $sPropertiesType;

    public function init() {
        $this->checkAndLoadPropertySet();
    }

    public function setType($type) {
        $this->sPropertiesType = $type;
    }

    public function read() {
        $type = $this->sPropertiesType;
        while ($this->obReader->name === 'Свойство') {
            $xml = new SimpleXMLElement($this->obReader->readOuterXML());

            $intPropertyId = $this->import_property($xml);

            if (isset($intPropertyId)) {
                $arProperties[] = $intPropertyId; 
            }

            $this->obReader->next('Свойство');
        }
        if (isset($arProperties)) {
            $type = $this->sPropertiesType;
            $this->obPropertySet->$type()->sync($arProperties);
        }
    }

    protected function import_property($obItem) {
        $sName = (string) $obItem->Наименование;
        $guid = (string) $obItem->Ид;
        if ($sName != 'Бренд' && $sName != 'Производитель' && $this->loadProps == true) {

            $data = [
                'external_id' => $guid,
                'name' => $sName,
                'active' => $this->isDeleted($obItem),
                'type' => $this->getPropertyType((string) $obItem->ТипЗначений)
            ];
            $obProperty = Property::updateOrCreate(['external_id' => $guid], $data);
            if ($obItem->ВариантыЗначений) {
                $this->read_values($obItem->ВариантыЗначений, $obProperty);
            }
            return $obProperty->id;
        }
        return null;
    }
    public function read_values($xml, $obProperty) {
        $arPropertyValues = [];
        $i = 0;

        foreach ($xml->Справочник as $obItem) {
            $guid = (string) $obItem->ИдЗначения;
            $data = [
                'external_id' => $guid,
                'value' => (string) $obItem->Значение,
            ];
            $arPropertyValues[$guid] = $data;

            $i++;
            if ( $i === 500 ) {
                $this->commit_values($arPropertyValues, $obProperty);
                $i = 0;
                $arPropertyValues = [];
            }
        }
        
        $this->commit_values($arPropertyValues, $obProperty);
    }


    private function commit_values($arValues, $obProperty) {
        $arPropertyValues = PropertyValue::whereIn('external_id', array_keys($arValues))->get();
        
        if ($arPropertyValues->count() > 0) {
            $arPropertyValues->each(function(PropertyValue $obValue) use(&$arValues, $obProperty) {
                $obValue->update($arValues[$obValue->external_id]);
                unset($arValues[$obValue->external_id]);
            });
        }

        if (!empty($arValues)) {
            foreach ($arValues as $data) {
                $obValue = PropertyValue::create($data);
                $obValue->property()->sync($obProperty->id);
            }
        }
    }

    private function checkAndLoadPropertySet() {
        $obPropertySet = PropertySet::where('code', 'main')->first();
        if (!empty($obPropertySet)) {
            $this->obPropertySet = $obPropertySet;
        } else {
            $data = [
                'name' => 'Общие свойства',
                'code' => 'main',
                'is_global' => true,
            ];
            $this->obPropertySet = PropertySet::create($data);
        }
    }
        

    private function getPropertyType($sType) {
        return match ($sType) {
            'Справочник' => 'select',
            'Строка' => 'input',
            'Число' => 'number',
            default => '',
        };
    }
}