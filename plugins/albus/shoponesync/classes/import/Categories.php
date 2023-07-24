<?php namespace Albus\ShopOneSync\Classes\Import;

use Str;
use Storage;
use SimpleXMLElement;
use Lovata\Shopaholic\Models\Category;


use Albus\ShopOneSync\Classes\Import\AbstractImport;

class Categories extends AbstractImport {

    public function read() {
        while ($this->obReader->read()) {
            while ($this->obReader->name === 'Группа') {
                $xml = new SimpleXMLElement($this->obReader->readOuterXML());
                $this->import_category($xml, null);
                $this->obReader->next('Группа');
            }
        }
        return true;
    }

    /**
     * Импорт данных категории
     */
    public function import_category($obItem, $obParent = null, $create = true)
    {
        if ($create == true) {
            $guid = $this->val($obItem->Ид);
            $values = [
                'external_id' => $guid,
                'name' => $this->val($obItem->Наименование),
                'active' => $this->isDeleted($obItem),
            ];
            $obCategory = Category::updateOrCreate(['external_id' => $guid], $values);
        } else {
            $obCategory = null;
        }
        if (isset($obParent)) {
            $obCategory->makeChildOf($obParent->id);
        }
        if (isset($obItem->Группы)) {
            foreach ($obItem->Группы->Группа as $obGroup) {
                $this->import_category($obGroup, $obCategory);
            }
        }
        return true;
    }
}