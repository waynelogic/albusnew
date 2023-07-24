<?php namespace Albus\ShopOneSync\Classes\Resources;

/** PHP */
use XMLReader;
use SimpleXMLElement;

/** October CMS */
use Session;
use Storage;
use System\Classes\PluginManager;
use System\Models\File;

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

/** Albus\ShopWithOne */
use Albus\ShopOneSync\Classes\Resources\AbstractResource;
use Albus\ShopOneSync\Models\Settings;

class Catalog extends AbstractResource
{
    public $loadProps;

    public $sProducerGuid;

    public $sPropertiesType;

    public function parse()
    {
        $this->loadProps = PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic');
        $this->sProducerGuid = Settings::get('producerGuid');

        // $obXML = simplexml_load_file($this->filepath);

        // if (isset($obXML->Классификатор->Группы->Группа)) {
        //     foreach ($obXML->Классификатор->Группы->Группа as $obItem) {
        //         $this->import_category($obItem);
        //     }
        // }

        // if (isset($obXML->Классификатор->Свойства->Свойство)) {
        //     if ($this->loadProps == false) {
        //         foreach ($obXML->Классификатор->Свойства->Свойство as $obItem) {
        //             if ($obItem->Наименование == 'Бренд' or $obItem->Наименование == 'Производитель') {
        //                 $sProducerGuid = $this->val($obItem->Ид);
        //                 Settings::set('producerGuid', $sProducerGuid);
        //                 $this->sProducerGuid = $sProducerGuid;
        //                 if (isset($obItem->ВариантыЗначений->Справочник)) {
        //                     foreach ($obItem->ВариантыЗначений->Справочник as $obItem) {
        //                         $this->import_producer($obItem, 'property');
        //                     }
        //                 }
        //             }
        //         }
        //     }            
        //     // $obPropertySet = $this->checkAndLoadPropertySet();
            
        //     // foreach ($obXML->Классификатор->Свойства->Свойство as $obItem) {
        //     //     $obPropertyId = $this->import_property($obItem);
        //     //     if (isset($obPropertyId)) {
        //     //         $arProperties[] = $obPropertyId; 
        //     //     }
        //     // }
        //     // if (isset($arProperties)) {
        //     //     $prop_type = $this->sPropertiesType;
        //     //     $obPropertySet->$prop_type()->sync($arProperties);
        //     // }
        // }

        // if (isset($obXML->Классификатор->ЕдиницыИзмерения->ЕдиницаИзмерения)) {
        //     foreach ($obXML->Классификатор->ЕдиницыИзмерения->ЕдиницаИзмерения as $obItem) {
        //         $this->import_unit($obItem);
        //     }
        // }

        // if (isset($obXML->Каталог->Товары->Товар)) {
        //     foreach ($obXML->Каталог->Товары->Товар as $obItem) {
        //         $this->import_product($obItem);
        //     }
        // }

        // if (isset($obXML->ПакетПредложений->Предложения->Предложение)) {
        //     foreach ($obXML->ПакетПредложений->Предложения->Предложение as $obItem) {
        //         $this->import_offer($obItem);
        //     }
        // }
        return true;
    }

    public function import_category($obItem, $obParent = null)
    {
        $guid = $this->val($obItem->Ид);
        $values = [
            'external_id' => $guid,
            'name' => $this->val($obItem->Наименование),
            'active' => $this->isDeleted($obItem),
        ];
        $obCategory = Category::updateOrCreate(['external_id' => $guid], $values);
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

    public function import_producer($obItem, $mode = 'requisite')
    {
        $guid = $mode == 'requisite' ? $this->val($obItem->Ид) : $this->val($obItem->ИдЗначения);
        $values = [
            'external_id' => $guid,
            'name' => $mode == 'requisite' ? $this->val($obItem->Наименование) : $this->val($obItem->Значение),
            'active' => true
        ];
        
        $obBrand = Brand::updateOrCreate(['external_id' => $guid], $values);
        return $obBrand;
    }

    public function import_unit($obItem)
    {
        $guid = $this->val($obItem->Код);
        $values = [
            'code' => $guid,
            'name' => $this->val($obItem->НаименованиеПолное),
        ];
        $obMeasure = Measure::updateOrCreate(['code' => $guid], $values);
    }

    public function import_product($obItem)
    {
        $guid = $this->val($obItem->Ид);
        $values = [
            'external_id' => $guid,
            'name' => $this->val($obItem->Наименование),
            'active' => $this->isDeleted($obItem),
            'description' => $this->val($obItem->Описание),
            'code' => $this->val($obItem->Артикул),
        ];

        if (isset($obItem->Группы->Ид)) {
            $values['category_id'] = Category::where('external_id', $this->val($obItem->Группы->Ид))->value('id');
        }

        $obProduct = Product::updateOrCreate(['external_id' => $guid], $values);
        
        if (isset($obItem->Картинка)) {
            $obProduct->preview_image()->delete();
            $obProduct->images()->delete();
            $this->attachImages($obProduct, $obItem);
        }

        if (isset($obItem->ЗначенияСвойств)) {
            $this->setProductProperty($obProduct, $obItem);
        }
        return true;
    }

    public function setProductProperty($obProduct, $obItem) {
        foreach ($obItem->ЗначенияСвойств->ЗначенияСвойства as $obItem) {
            $value = $this->val($obItem->Значение);
            $id = $this->val($obItem->Ид);
            if (!empty($value)) {
                if ($id == $this->sProducerGuid) {
                    $intBrandId = Brand::where('external_id', $value)->value('id');
                    if(isset($intBrandId)) {
                        $obProduct->brand_id = $intBrandId;
                        $obProduct->save();
                    }
                } elseif ($this->loadProps == true) {
                    return;
                }
            }
        }
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



    public function import_offer($obItem)
    {
        $guid = $this->val($obItem->Ид);
        if (isset($obItem->Наименование)) {
            $intProductId = Product::where('external_id', head(explode("#", $guid)))->value('id');
            if (isset($intProductId)) {
                $find = [ 'external_id' => $guid, ];
                $values = [
                    'external_id' => $guid,
                    'name' => $this->val($obItem->Наименование),
                    'code' => $this->val($obItem->Артикул),
                    'active' => $this->isDeleted($obItem),
                    'product_id' => $intProductId,
                    'width' => $this->val($obItem->Ширина),
                    'length' => $this->val($obItem->Длина),
                    'height' => $this->val($obItem->Высота)
                ];
                $obOffer = Offer::updateOrCreate($find, $values);
            }
        }
        if (!isset($obOffer)) {
            $obOffer = Offer::where('external_id', $guid)->first();
        }
        if (isset($obItem->Цены->Цена)) {
            $this->setPrices($obOffer, $obItem);
        }
        if (isset($obItem->Количество)){
            $obOffer->quantity = intval($obItem->Количество);
        };

        return true;
    }

    public function setPrices($obOffer, $obItem)
    {
        $class = get_class($obOffer);
        $intOfferId = $obOffer->id;
        if (isset($obItem->Цены->Цена->ЦенаЗаЕдиницу)) {
            $find = [
                'item_id' => $intOfferId,
                'item_type' => $class,
                'price_type_id' => null
            ];
            $values = [
                'price' => $obItem->Цены->Цена->ЦенаЗаЕдиницу,
            ];
            $obPrice = Price::updateOrCreate($find, $values);
        } else {
            foreach ($obItem->Цены->Цена as $obItemPrice) {
                $obPriceType = PriceType::where('external_id', $obItemPrice->ИдТипаЦены)->first();
                $find = [
                    'item_id' => $intOfferId,
                    'item_type' => $class,
                    'price_type_id' => $obPriceType->id
                ];
                $values = [
                    'price' => $obItem->ЦенаЗаЕдиницу,
                ];
                $obPrice = Price::updateOrCreate($find, $values);
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
        return isset($obItem->ПометкаУдаления) ? $obItem->ПометкаУдаления != 'true' : true;
    }
    public function val($obItem)
    {
        return !empty($obItem) ? strval($obItem) : null;
    }
}
