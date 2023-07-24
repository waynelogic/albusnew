<?php namespace Albus\ShopOneSync\Classes\Import;

use Str;
use Storage;
use SimpleXMLElement;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Category;

use Albus\ShopOneSync\Classes\Import\AbstractImport;

class BACK extends AbstractImport {

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
    
                $arProduct = $this->read_product($xml, $i);
                $arProductList[$arProduct['external_id']] = $arProduct;
                $i++;
                if ( $i === 500 ) {
                    $this->commit_products($arProductList);
                    $i = 0;
                    $arProductList = [];
                }
                $this->obReader->next('Товар');
            }
        }
        $this->commit_products($arProductList);
        return true;
    }
        /**
     * Импорт товара
     */
    public function read_product($obItem, $i)
    {
        $guid = (string) $obItem->Ид;
        $data = [
            'external_id' => $guid,
            'name' => (string) $obItem->Наименование,
            'slug' => Str::slug((string) $obItem->Наименование),
            'active' => $this->isDeleted($obItem),
            'description' => (string) $obItem->Описание,
            'code' => (string) $obItem->Артикул,
        ];

        if (isset($obItem->Изготовитель->Наименование)) {
            $brandGuid = (string) $obItem->Изготовитель->Ид;
            if (!isset($this->arBrands[$brandGuid])) {
                $intBrandId = Brand::create([
                    'external_id' => $brandGuid,
                    'name' => (string) $obItem->Изготовитель->Наименование,
                    'slug' => Str::slug((string) $obItem->Изготовитель->Наименование),
                    'active' => true
                ])->value('id');

                $this->arBrands[$brandGuid] = $intBrandId;
                $data['brand_id'] = $intBrandId;
            } else {
                $data['brand_id'] = $this->arBrands[$brandGuid]; 
            }
        }
        if (isset($obItem->Группы->Ид)) {
            $sCategoryGuid = (string) $obItem->Группы->Ид;
            $data['category_id'] = $this->arCategories[$sCategoryGuid]; 
        }
        
        if (isset($obItem->Картинка)) {
            $arImageGroup = [];
            foreach ($obItem->Картинка as $img) {
                $this->arImageGroups[$guid][] = (string) $img;
            }
        }

        if (isset($obItem->ЗначенияСвойств)) {
            $this->setProductProperty($guid, $obItem);
        }
        return $data;
    }

    public function commit_products($abProductList) {
        $arExistProducts = Product::whereIn('external_id', array_keys($abProductList))->get();

        $arExistProducts->each(function(Product $obProduct) use(&$abProductList) {
            $obProduct->update($abProductList[$obProduct->external_id]);
            unset($abProductList[$obProduct->external_id]);
        });

        if (!empty($abProductList)) {
            $obProductList = Product::query()->insertOrIgnore($abProductList);
        }

        $this->attachImages();
        $this->setAllProps();
    }

    /**
     * Прикрепить картинки к товару
     */
    public function attachImages()
    {
        if (!empty($this->arImageGroups)) {
            foreach ($this->arImageGroups as $guid => $arImageGroup) {
                $obProduct = Product::where('external_id', $guid)->first();
                $obProduct->preview_image()->delete();
                $obProduct->images()->delete();
                
                $is_preview_image = true;
                foreach ($arImageGroup as $img) {
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
        }
    }

    public function setProductProperty($obProductGuid, $obItem){

        foreach ($obItem->ЗначенияСвойств->ЗначенияСвойства as $obItem) {
            $id = (string) $obItem->Ид;
            $value = (string) $obItem->Значение;
            if (!empty($value) && $id != 'Бренд') {
                $this->arPropsWithItems[$id][$obProductGuid] = $value;
                // dd()

                $data = [
                    'value_external_id' => $value,
                    'property_external_id' => $id,
                    'element_external_id' => $obProductGuid,
                    'element_type' => Product::class,
                    'product_id' => $obProductGuid
                ];
            }
            // if (!empty($value)) {
            //     $obProperty = Property::where('external_id',$id)->first();
            //     if ($obProperty->type == 'select') {
            //         $obPropertyValue = PropertyValue::where('external_id', $value)->first();
            //     } elseif ($obProperty->type == 'input') {
            //         $find = [
            //             'value' => $value,
            //             'external_id' => null
            //         ];
            //         $values = [
            //             'value' => $value,
            //         ];
            //         $obPropertyValue = PropertyValue::updateOrCreate($find, $values);
            //     }
            //     $find = [
            //         'element_id' => $obOffer->id,
            //         'element_type' => get_class($obOffer),
            //     ];
            //     $values = [
            //         'value_id' => $obPropertyValue->id,
            //         'property_id' => $obProperty->id,
            //         'element_id' => $obOffer->id,
            //         'element_type' => get_class($obOffer),
            //         'product_id' => $obOffer->product->id
            //     ];
            //     PropertyValueLink::updateOrCreate($find, $values);
        }
    }

    public function setAllProps() {
        dd($this->arPropsWithItems);
    }
}
