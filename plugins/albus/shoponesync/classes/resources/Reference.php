<?php namespace Albus\ShopWithOne\Classes\Resources;

/** PHP */
use XMLReader;
use SimpleXMLElement;

use Albus\ShopWithOne\Classes\Resources\AbstractResource;

class Reference extends AbstractResource {
    public function parse()
    {
        // $z = new XMLReader;
        // $z->open($this->filepath);
        // while ($z->read()) {
        //     $tag = $z->name;
        //     // if ($z->name == 'Контрагент') {
        //     //     while ($z->name === 'Контрагент') {
        //     //         $xml = new SimpleXMLElement($z->readOuterXML());
        //     //         $this->import_client($xml);
        //     //         $z->next('Контрагент');
        //     //     }
        //     // }

        //     if ($tag == 'Справочник') {
        //         while ($z->read()) { //так что мы говорим ему продолжать читать
        //             if ($z->name == 'Пользователи') {
        //                 dd($z->name);
        //             };
        //             // if ($z->nodeType == XMLReader::ELEMENT && $obXML->name === 'Группа') { // и когда он находит тег amount...
        //             //     $this->obItem = $obXML->expand(new DOMDocument()); 
        //             //     dd($this->obItem->childNodes);
        //             //     // $this->importProduct();
        //             //     // break;
        //             // }

        //         }
        //         // break;
        //     }

        // }

        // $z->close();

        return true;
    }
}