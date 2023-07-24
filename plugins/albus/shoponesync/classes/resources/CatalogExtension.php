<?php namespace Albus\ShopOneSync\Classes\Resources;

/** PHP */
use XMLReader;

/** October CMS */
use System\Classes\PluginManager;

/** Albus/ShopOneSync */
use Albus\ShopOneSync\Models\Settings;
use Albus\ShopOneSync\Classes\Import\Properties;
use Albus\ShopOneSync\Classes\Import\Products;
use Albus\ShopOneSync\Classes\Import\Categories;
use Albus\ShopOneSync\Classes\Import\Offers;
use Albus\ShopOneSync\Classes\Import\Prices;
use Albus\ShopOneSync\Classes\Import\Rests;

class CatalogExtension extends AbstractResource
{
    public $loadProps;

    public $sProducerGuid;

    public $sPropertiesType;

    public function parse()
    {
        $this->loadProps = PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic');
        $this->sProducerGuid = Settings::get('producerGuid');
        [$name, $other] = explode('_', basename($this->filepath), 2);
        $importType = 'parce_'.$name;

        if (is_callable([$this, $importType])) {
            $obReader = new XMLReader;
            $obReader->open($this->filepath);
            while ($obReader->read()) {
                $this->$importType($obReader);
            }
            $obReader->close();
        }
        
        return true;
    }

    public function parce_groups($obReader) {
        $obCategoryList = new Categories($obReader);
        $obCategoryList->read();
        return true;
    }
    public function parce_goods($obReader) {
        $obProdictList = new Products($obReader);
        $obProdictList->read();
        return true;
    }
    public function parce_offers($obReader) {
        $obOfferList = new Offers($obReader);
        $obOfferList->read();
        return true;
    }
    public function parce_prices($obReader) {
        $obOfferList = new Prices($obReader);
        $obOfferList->read();
        return true;
    }
    // public function parce_rests($obReader) {
    //     $obRestList = new Rests($obReader);
    //     $obRestList->read();
    //     return true;
    // }

    public function parce_propertiesGoods($obReader) {
        $obPropertieList = new Properties($obReader);
        if (str_contains($this->filepath, 'import.xml') or str_contains($this->filepath, 'propertiesGoods')) {
            $obPropertieList->setType('product_property');
        } elseif (str_contains($this->filepath, 'offers.xml') or str_contains($this->filepath, 'propertiesOffers')) {
            $obPropertieList->setType('offer_property');
        }
        $obPropertieList->read();
        return true;
    }
}
