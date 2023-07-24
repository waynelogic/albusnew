<?php namespace Albus\ShopOneSync\Classes\Import;

use Str;
use Storage;
use SimpleXMLElement;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Category;

use Lovata\PropertiesShopaholic\Models\Property;

use Albus\ShopOneSync\Classes\Import\AbstractImport;
use Lovata\PropertiesShopaholic\Models\PropertyValue;

class Products extends AbstractImport {

    public $arCategories = [];
    public $arBrands = [];
    
    public $arImageGroups = [];

    public $arProductsByCategory = [];

    public $arPropsWithItems = [];

    public function init() {
        $this->arCategories = Category::all()->pluck('id', 'external_id')->toArray();
        $this->arBrands = Brand::all()->pluck('id', 'external_id')->toArray();
    }

    public function read() {
        $arProductList = [];
        $i = 0;        
        while ($this->obReader->read()) {
            while ($this->obReader->name === 'Товар') {
                $xml = new SimpleXMLElement($this->obReader->readOuterXML());
                $arProduct = $this->import_product($xml);
                $this->obReader->next('Товар');
            }
        }
        return true;
    }

    public function import_product($obItem) {
        $guid = (string) $obItem->Ид;
        $data = [
            'external_id' => $guid,
            'name' => (string) $obItem->Наименование,
            'active' => $this->isDeleted($obItem),
            'description' => (string) $obItem->Описание,
            'code' => (string) $obItem->Артикул,
        ];
        if (isset($obItem->Изготовитель->Наименование)) {
            $data['brand_id'] = $this->getBrandId($obItem->Изготовитель);
        }
        if (isset($obItem->Группы->Ид)) {
            $sCategoryGuid = (string) $obItem->Группы->Ид;
            $data['category_id'] = $this->arCategories[$sCategoryGuid]; 
        }

        $obProduct = Product::updateOrCreate( ['external_id' => $guid], $data);

        if (isset($obItem->Картинка)) {
            $obProduct->preview_image()->delete();
            $obProduct->images()->delete();
            $this->attachImages($obProduct, $obItem);
        }

        if (isset($obItem->ЗначенияСвойств)) {
            $this->setProductProperty($obProduct, $obItem);
        }
    }

    public function getBrandId($obItem) {
        $brandGuid = (string) $obItem->Ид;
        if (isset($this->arBrands[$brandGuid])) {
            return $this->arBrands[$brandGuid];
        } else {
            $intBrandId = Brand::create([
                'external_id' => $brandGuid,
                'name' => (string) $obItem->Наименование,
                'active' => true
            ])->value('id');

            $this->arBrands[$brandGuid] = $intBrandId;
            return $intBrandId;
        };
    }


    /**
     * Прикрепить картинки к товару
     */
    public function attachImages($obProduct, $obItem)
    {
        $is_preview_image = true;
        foreach ($obItem->Картинка as $img) {
            $filepath = $this->getExchangeFilePath($img);
            if (!Storage::disk('local')->exists($filepath))
                continue;
            $image = $this->createFileAttach(Storage::path($filepath));
            $is_preview_image == true
                ? $obProduct->preview_image()->add($image)
                : $obProduct->images()->add($image);
            $is_preview_image = false;
        }
    }

    public function setProductProperty($obProduct, $obItem){
        // // dd($obProduct->getPropertyValueList);
        // $arProperties = [];

        // foreach ($obItem->ЗначенияСвойств->ЗначенияСвойства as $sProp) {
        //     $id = (string) $sProp->Ид;
        //     $value = (string) $sProp->Значение;
        //     if (!empty($value) && $id != 'Бренд') {
        //         // dd($obItem);

        //         $obPropery = Property::getByExternalID($id)->first();
        //         if ($obPropery->type == 'select') {
        //             $value = PropertyValue::where('external_id', $value)->value('id');
        //         }
        //         array_set($arProperties, $obPropery->id, $value);
        //     }
        // }
        
        // if (empty($arProperties)) {
        //     return null;
        // }

        // $obProduct->setPropertyAttribute($arProperties);
        // // dd($arProperties);
        // // $obProduct->property = $arProperties; 
        // $obProduct->save();
    }
}
