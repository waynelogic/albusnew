<?php namespace Albus\ShopOneSync\Classes\Import;

use SimpleXMLElement;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Price;

use Albus\ShopOneSync\Classes\Import\AbstractImport;

class Prices extends AbstractImport {
    public function read() {
        $arPrices = [];
        while ($this->obReader->read()) {
            while ($this->obReader->name === 'Предложение') {
                $obItem = new SimpleXMLElement($this->obReader->readOuterXML());
                $intOfferId = Offer::where('external_id', (string) $obItem->Ид)->value('id');
                $price = intval($obItem->Цены->Цена->ЦенаЗаЕдиницу);
                $arPrices[$intOfferId] = $price;
                $this->obReader->next('Предложение');
            }
        }
        $this->commit_prices($arPrices);
        return true;
    }

    public function commit_prices($arItems) {
        $obPriceList = Price::where('item_type', Offer::class)->where('price_type_id', null)->whereIn('item_id', array_keys($arItems))->get();
        $obPriceList->each(function(Price $obPrice) use(&$arItems) {
            $obPrice->update(['price' => $arItems[$obPrice->item_id]]);
            unset($arItems[$obPrice->item_id]);
        });
    }
}