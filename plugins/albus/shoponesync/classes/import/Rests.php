<?php namespace Albus\ShopOneSync\Classes\Import;

use SimpleXMLElement;
use Lovata\Shopaholic\Models\Offer;

use Albus\ShopOneSync\Classes\Import\AbstractImport;

class Rests extends AbstractImport {

    public function read() {
        while ($this->obReader->read()) {
            while ($this->obReader->name === 'Предложение') {
                $obItem = new SimpleXMLElement($this->obReader->readOuterXML());
                $this->import_count($obItem);
            }
        }
        return true;
    }

    public function import_count($obItem) {
        $guid = (string) $obItem->Ид;
        $iTotal = 0;
        foreach ($obItem->Остатки->Остаток as $obWarehouse) {
            $count = intval($obWarehouse->Склад->Количество);
            $iTotal += $count;
        }
        Offer::where('external_id', $guid)->update(['quantity' => $iTotal]);
        return true;
    }
}