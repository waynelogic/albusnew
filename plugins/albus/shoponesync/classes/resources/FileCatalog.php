<?php namespace Albus\ShopWithOne\Classes\Resources;


use Storage;
use System\Models\File;
use System\Classes\PluginManager;


/** Lovata\Shopaholic */
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Measure;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Price;
use Lovata\Shopaholic\Models\PriceType;

/** Lovata\PropertiesShopaholic */
use Lovata\PropertiesShopaholic\Models\Property;
use Lovata\PropertiesShopaholic\Models\PropertySet;
use Lovata\PropertiesShopaholic\Models\PropertyValue;
use Lovata\PropertiesShopaholic\Models\PropertyValueLink;


use Albus\ShopWithOne\Models\Settings;


class FileCatalog {
    const EXCHANGE_FOLDER = 'exchange';

    public $arFolders;

    public $filepath;

    public $sFolder;

    public $sItemType;

    public $sPropertiesType;

    public $loadProps;

    public function __construct()
    {
        $this->arFolders = Storage::directories(self::EXCHANGE_FOLDER);
        $this->loadProps = PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic');
    }

    public function import()
    {
        // dd($this->arFolders);
        if (!empty($this->arFolders)) {
            foreach ($this->arFolders as $sFolder) {
                if (str_contains($sFolder, 'groups')) {
                    $this->sItemType = 'groups';
                    $this->sFolder = $sFolder;
                    $this->importFiles($sFolder);
                }
                if (str_contains($sFolder, 'goods')) {
                    $this->sItemType = 'goods';
                    $this->sFolder = $sFolder;
                    $this->importFiles($sFolder);
                }
            }
        }
        return $this->answer(['success']);

    }

    public function groups()
    {
        // dd($this->arFolders);
        if (!empty($this->arFolders)) {
            foreach ($this->arFolders as $sFolder) {
                if (str_contains($sFolder, 'groups')) {
                    $this->sItemType = 'groups';
                    $this->sFolder = $sFolder;
                    $this->importFiles($sFolder);
                }
            }
        }
        return $this->answer(['success']);
    }

    public function goods()
    {
        // dd($this->arFolders);
        if (!empty($this->arFolders)) {
            foreach ($this->arFolders as $sFolder) {
                if (str_contains($sFolder, 'goods')) {
                    $this->sItemType = 'goods';
                    $this->sFolder = $sFolder;
                    $this->importFiles($sFolder);
                }
            }
        }
        return $this->answer(['success']);
    }

    public function propertiesGoods()
    {
        // dd($this->arFolders);
        if (!empty($this->arFolders)) {
            foreach ($this->arFolders as $sFolder) {
                if (str_contains($sFolder, 'propertiesGoods')) {
                    $this->sItemType = 'propertiesGoods';
                    $this->sFolder = $sFolder;
                    $this->importFiles($sFolder);
                }
            }
        }
        return $this->answer(['success']);
    }

    public function propertiesOffers()
    {
        if (!empty($this->arFolders)) {
            foreach ($this->arFolders as $sFolder) {
                if (str_contains($sFolder, 'propertiesOffers')) {
                    $this->sItemType = 'propertiesOffers';
                    $this->sFolder = $sFolder;
                    $this->importFiles($sFolder);
                }
            }
        }
        return $this->answer(['success']);
    }

    public function offers()
    {
        if (!empty($this->arFolders)) {
            foreach ($this->arFolders as $sFolder) {
                if (str_contains($sFolder, 'offers')) {
                    $this->sItemType = 'offers';
                    $this->sFolder = $sFolder;
                    $this->importFiles($sFolder);
                }
            }
        }
        return $this->answer(['success']);
    }


    public function importFiles($sFolder)
    {
        $arFiles = Storage::files($sFolder);
        foreach ($arFiles as $obFile) {
            $this->filepath = Storage::path($obFile);
            $this->parse();
        }
    }

    public function parse()
    {
        $obXML = simplexml_load_file($this->filepath);

        // Импортируем категории
        if (isset($obXML->Классификатор->Группы->Группа)) {
            foreach ($obXML->Классификатор->Группы->Группа as $obItem) {
                $this->import_category($obItem);
            }
        }
        if (isset($obXML->Классификатор->Свойства->Свойство) and $this->loadProps == true) {
            $obPropertySet = $this->checkAndLoadPropertySet();
            
            foreach ($obXML->Классификатор->Свойства->Свойство as $obItem) {
                $obPropertyId = $this->import_property($obItem);
                if (isset($obPropertyId)) {
                    $arProperties[] = $obPropertyId; 
                }
            }
            if (isset($arProperties)) {
                $prop_type = $this->sPropertiesType;
                $obPropertySet->$prop_type()->sync($arProperties);
            }
        }
        if (isset($obXML->Классификатор->ЕдиницыИзмерения->ЕдиницаИзмерения)) {
            foreach ($obXML->Классификатор->ЕдиницыИзмерения->ЕдиницаИзмерения as $obItem) {
                $this->import_unit($obItem);
            }
        }
        if (isset($obXML->Каталог->Товары->Товар)) {
            foreach ($obXML->Каталог->Товары->Товар as $obItem) {
                $this->import_product($obItem);
            }
        }
        if (isset($obXML->ПакетПредложений->Предложения->Предложение)) {
            foreach ($obXML->ПакетПредложений->Предложения->Предложение as $obItem) {
                $this->import_offer($obItem);
            }
        }

        return true;
    }

    /**
     * Загрузка Категорий
     *
     * @param $xml
     * @param $obParent
     * @return void
     */
    public function import_category($obItem, $obParent = null)
    {
        $guid = strval($obItem->Ид);
        $find = [ 'external_id' => $guid ];
        $values = [
            'external_id' => $guid,
            'name' => strval($obItem->Наименование),
            'active' => $this->isDeleted($obItem),
        ];
        $obCategory = Category::updateOrCreate($find, $values);
        if (isset($obParent)) {
            $obCategory->makeChildOf($obParent->id);
        }
        if(isset($obItem->Группы)) {
            foreach ($obItem->Группы->Группа as $obItem) {
                $this->import_category($obItem, $obCategory);
            }
        }
    }

    public function checkAndLoadPropertySet()
    {
        if ($this->sItemType == 'propertiesGoods') {
            $this->sPropertiesType = 'product_property';
        } elseif ($this->sItemType == 'propertiesOffers') {
            $this->sPropertiesType = 'offer_property';
        }
        $sName = 'Общие свойства';
        $sCode = 'main';
        $find = [ 'code' => $sCode ];
        $values = [
            'name' => $sName,
            'code' => $sCode,
            'is_global' => true,
        ];
        $obPropertySet = PropertySet::updateOrCreate($find, $values);
        return $obPropertySet;
    }

    /**
     * Импорт свойства
     *
     * @param  mixed $obItem
     * @return void
     */
    private function import_property($obItem)
    {
        $sName = strval($obItem->Наименование);

        if ($sName == 'Бренд' or $sName == 'Производитель') {
            $sProducerGuid = strval($obItem->Ид);
            Settings::set('producerGuid', $sProducerGuid);
            $this->sProducerGuid = $sProducerGuid;
            if (isset($obItem->ВариантыЗначений->Справочник)) {
                foreach ($obItem->ВариантыЗначений->Справочник as $obItem) {
                    $this->import_producer($obItem, 'property');
                }
            }
        } elseif ($this->loadProps == true) {

            $guid = strval($obItem->Ид);
            $type = $this->propertyType(strval($obItem->ТипЗначений));

            $find = [
                'external_id' => $guid,
            ];
            $values = [
                'external_id' => $guid,
                'name' => strval($obItem->Наименование),
                'active' => $this->isDeleted($obItem),
                'type' => $type
            ];
            $obProperty = Property::updateOrCreate($find, $values);
            if ($obItem->ВариантыЗначений) {
                foreach ($obItem->ВариантыЗначений->Справочник as $obItem) {
                    $guid = strval($obItem->ИдЗначения);
                    $find = [
                        'external_id' => $guid,
                    ];
                    $values = [
                        'external_id' => $guid,
                        'value' => strval($obItem->Значение),
                    ];
                    $obPropertyValue = PropertyValue::updateOrCreate($find, $values);
                    // dd($obPropertyValue);
                    $arValues[] = $obPropertyValue->id;
                }
                if (isset($arValues)) {
                    $obProperty->property_value()->sync($arValues);
                }
            }
            return $obProperty->id;
        }
    }

    private function propertyType($sType)
    {
        switch ($sType) {
            case 'Справочник':
                return "select";
            case 'Строка':
                return "input";
            case 'Число':
                return "number";
        }
    }


    /**
     * Загрузка товара на сайт
     *
     * @param  mixed $obItem
     * @return void
     */
    public function import_product($obItem)
    {
        $guid = strval($obItem->Ид);

        $find = [
            'external_id' => $guid,
        ];
        $values = [
            'external_id' => $guid,
            'name' => strval($obItem->Наименование),
            'active' => $this->isDeleted($obItem),
            'description' => strval($obItem->Описание),
            'code' => strval($obItem->Артикул),
        ];
        // Ид категории
        if (isset($obItem->Группы->Ид)) {
            $obCategory = Category::where('external_id', strval($obItem->Группы->Ид))->first();
            $values['category_id'] = $obCategory->id;
        }

        if (isset($obItem->Изготовитель->Ид)) {
            $values['brand_id'] = $this->import_producer($obItem->Изготовитель)->id;
        }

        // Реквизиты
        // if (isset($obItem->ЗначенияРеквизитов->ЗначениеРеквизита)) {
        //     foreach ($obItem->ЗначенияРеквизитов->ЗначениеРеквизита as $obOption) {
        //         if (strval($obOption->Наименование) == 'ТипНоменклатуры') {
        //             $values['type'] = $this->productType(strval($obOption->Значение));
        //         }
        //     }
        // }
        
        // dd($obProduct);
        $obProduct = Product::updateOrCreate($find, $values);
        
        // Добавляем изображение товара если они есть
        if (isset($obItem->Картинка)) {
            $obProduct->preview_image()->delete();
            $obProduct->images()->delete();
            $this->attachImages($obProduct, $obItem);
        }

        if (isset($obItem->ЗначенияСвойств)) {
            $this->setProductProperties($obProduct, $obItem);
        }
    }

    /**
     * Прикрепить картинки к товару
     */
    public function attachImages($obProduct, $obItem)
    {
        $is_preview_image = true;
        foreach ($obItem->Картинка as $img) {
            $filepath = ($this->sFolder . DIRECTORY_SEPARATOR . $img);

            if (!Storage::disk('local')->has($filepath))
                continue;
            $image = $this->createFileAttach(Storage::path($filepath));
            $is_preview_image == true
                ? $obProduct->preview_image()->add($image)
                : $obProduct->images()->add($image);
            $is_preview_image = false;
        }
    }

    public function import_producer($obItem, $mode = 'requisite')
    {
        if ($mode == 'requisite') {
            $guid = strval($obItem->Ид);
            $find = [ 'external_id' => $guid ];
            $values = [
                'external_id' => $guid,
                'name' => strval($obItem->Наименование),
                'active' => true
            ];
        } elseif ($mode == 'property') {
            $guid = strval($obItem->ИдЗначения);
            $find = [ 'external_id' => $guid ];
            $values = [
                'external_id' => $guid,
                'name' => strval($obItem->Значение),
                'active' => true
            ];
        }
        $obBrand = Brand::updateOrCreate($find, $values);
        return $obBrand;
    }


    /**
     * Импорт торгового предложения
     *
     * @param  mixed $obItem
     * @return void
     */
    public function import_offer($obItem)
    {
        $guid = strval($obItem->Ид);
        if (isset($obItem->Наименование)) {
            $obProduct = Product::where('external_id', head(explode("#", $guid)))->first();
            $find = [ 'external_id' => $guid, ];
            $values = [
                'external_id' => $guid,
                'name' => strval($obItem->Наименование),
                'active' => $this->isDeleted($obItem),
                'product_id' => $obProduct->id,
                'width' => $this->getVal($obItem->Ширина),
                'length' => $this->getVal($obItem->Длина),
                'height' => $this->getVal($obItem->Высота)
            ];
            $obOffer = Offer::updateOrCreate($find, $values);
        } 
        if (!isset($obOffer)) {
            $obOffer = Offer::where('external_id', $guid)->first();
        }
        if (isset($obItem->Цены->Цена)) {
            $this->setPrices($obOffer, $obItem);
        }
        if (isset($obItem->Количество)){
            $this->setCount($obOffer, $obItem, 'count');
        };
        if (isset($obItem->Остатки->Остаток)) {
            $this->setCount($obOffer, $obItem, 'wh');
        }
        if (isset($obItem->ЗначенияСвойств)) {
            $this->setOfferProperties($obOffer, $obItem);
        }
    }

    public function setPrices($obOffer, $obItem)
    {
        if (isset($obItem->Цены->Цена->ЦенаЗаЕдиницу)) {
            $find = [
                'item_id' => $obOffer->id,
                'item_type' => get_class($obOffer),
                'price_type_id' => null
            ];
            $values = [
                'price' => $obItem->Цены->Цена->ЦенаЗаЕдиницу,
            ];
            $obOffer = Price::updateOrCreate($find, $values);
        } else {
            foreach ($obItem->Цены->Цена as $obItemPrice) {
                $obPriceType = PriceType::where('external_id', $obItemPrice->ИдТипаЦены)->first();
                $find = [
                    'item_id' => $obOffer->id,
                    'item_type' => get_class($obOffer),
                    'price_type_id' => $obPriceType->id
                ];
                $values = [
                    'price' => $obItem->ЦенаЗаЕдиницу,
                ];
                $obOffer = Price::updateOrCreate($find, $values);
            }
        }
    }

    public function setCount($obOffer, $obItem, $mode = 'wh')
    {
        $iTotal = 0;
        if ($mode == 'wh') {
            foreach ($obItem->Остатки->Остаток as $obWarehouse) {
                $count = intval($obWarehouse->Склад->Количество);
                if($count > 0) {
                    $iTotal += $count;
                }
            }
        } elseif ($mode == 'count') {
            $iTotal = intval($obItem->Количество);
        }
        $obOffer->quantity = $iTotal;
        $obOffer->save();
    }

    public function setOfferProperties($obOffer, $obItem)
    {
        foreach ($obItem->ЗначенияСвойств->ЗначенияСвойства as $obItem) {
            $id = strval($obItem->Ид);
            $value = strval($obItem->Значение);
            if (!empty($value)) {
                $obProperty = Property::where('external_id',$id)->first();
                if ($obProperty->type == 'select') {
                    $obPropertyValue = PropertyValue::where('external_id', $value)->first();
                } elseif ($obProperty->type == 'input') {
                    $find = [
                        'value' => $value,
                        'external_id' => null
                    ];
                    $values = [
                        'value' => $value,
                    ];
                    $obPropertyValue = PropertyValue::updateOrCreate($find, $values);
                }
                $find = [
                    'element_id' => $obOffer->id,
                    'element_type' => get_class($obOffer),
                ];
                if(!isset($obPropertyValue->id)) {
                    dd($obItem);
                }
                
                $values = [
                    'value_id' => $obPropertyValue->id,
                    'property_id' => $obProperty->id,
                    'element_id' => $obOffer->id,
                    'element_type' => get_class($obOffer),
                    'product_id' => $obOffer->product->id
                ];
                PropertyValueLink::updateOrCreate($find, $values);
            }
        }
    }





    /**
     * Создание файла
     *
     * @param  mixed $path
     * @return void
     */
    public function createFileAttach($path)
    {
        $file = new File;
        $file->fromFile($path);
        $file->save();
        return $file;
    }
    /**
     * Устанавливает активность товара
     *
     * @param  mixed $obItem
     * @return void
     */
    public function isDeleted($obItem)
    {
        if (isset($obItem->ПометкаУдаления)) {
            return $obItem->ПометкаУдаления == 'true' ? false : true;
        } else {
            return true;
        }
    }

    public function getVal($obItem)
    {
        $s = strval($obItem);
        if (empty($s)) {
            return null;
        } else {
            return $s;
        }
    }

    /**
     * Ответ для 1С
     *
     * возвращает ответ
     * @return Response
     */
    public function answer($array)
    {
        return implode("\n", $array);
    }

}