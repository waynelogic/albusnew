<?php namespace Albus\ShopOneSync\Classes\Import;

use Str;
use Storage;
use SimpleXMLElement;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;

use Albus\ShopOneSync\Classes\Import\AbstractImport;

class Offers extends AbstractImport {
    public function read() {
        $arOffers = [];
        $i = 0;

        while ($this->obReader->read()) {
            while ($this->obReader->name === 'Предложение') {
                
                $xml = new SimpleXMLElement($this->obReader->readOuterXML());
                $data = $this->read_offer($xml);
                $arOffers[$data['external_id']] = $data;

                $i++;
                if ( $i === 500 ) {
                    $this->commit_offers($arOffers);
                    $i = 0;
                    $arOffers = [];
                }

                $this->obReader->next('Предложение');
            }
        }
        $this->commit_offers($arOffers);
        return true;
    }
    public function read_offer($obItem)
    {
        $guid = (string) $obItem->Ид;
        $intProductId = Product::where('external_id', head(explode("#", $guid)))->value('id');
        if (isset($intProductId)) {
            $data = [
                'external_id' => $guid,
                'name' => (string) $obItem->Наименование,
                'code' => (string) $obItem->Артикул,
                'active' => $this->isDeleted($obItem),
                'product_id' => $intProductId,
                'width' => (string) $obItem->Ширина,
                'length' => (string) $obItem->Длина,
                'height' => (string) $obItem->Высота
            ];
            return $data;
        }
        return null;
    }
    public function commit_offers($arItems) {
        $obOfferList = Offer::whereIn('external_id', array_keys($arItems))->get();
        $obOfferList->each(function(Offer $obOffer) use(&$arItems) {
            $obOffer->update($arItems[$obOffer->external_id]);
            unset($arItems[$obOffer->external_id]);
        });

        if (!empty($arItems)) {
            Offer::insert($arItems);
        }
    }
}