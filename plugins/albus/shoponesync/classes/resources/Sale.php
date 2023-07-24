<?php namespace Albus\ShopWithOne\Classes\Resources;

/** PHP */
use XMLReader;
use SimpleXMLElement;

/** October CMS */
use Storage;

/** Albus\ShopWithOne */
use Albus\ShopWithOne\Classes\Export\ExportOrdersByXML;
use Albus\ShopWithOne\Classes\Export\InfoXML;
use Albus\ShopWithOne\Classes\Resources\AbstractResource;

/**
 * Class Sale
 * @package Albus\ShopWithOne\Classes\Resources
 */
class Sale extends AbstractResource
{
    const FILE_NAME = 'sales_exchange.xml';
    
    const INFO_FILE_NAME = 'info.xml';

    const ORDERS_FILE_NAME = 'sales.xml';
    
    /**
     * Import 1C -> CMS
     * Импорт XML в CMS
     */
    public function parse()
    {
        $z = new XMLReader;
        $z->open($this->filepath);
        while ($z->read()) {

            if ($z->name == 'Контрагент') {
                while ($z->name === 'Контрагент') {
                    $xml = new SimpleXMLElement($z->readOuterXML());
                    $this->import_client($xml);
                    $z->next('Контрагент');
                }
            }
        }

        $z->close();

        return true;
    }

    public function import_client($obItem)
    {
        dd($obItem);
    }

    /**
     * EXPORT CMS -> 1C
     * Экспорт XML в 1С - Статусы заказа, методы оплаты, методы доставки
     */
    public function info()
    {
        $obInfoXML = new InfoXML;
        $obInfoXML->createFile();
        try {
            Storage::put(self::EXCHANGE_FOLDER . DIRECTORY_SEPARATOR . self::INFO_FILE_NAME, $obInfoXML->sContent);
        } catch (\Exception $ex) {
            trace_log('Saving sales.xml failed: ' . $ex->getMessage());
        }

        return $this->makeResponse($obInfoXML->sContent);
    }

    /**
     * EXPORT CMS -> 1C
     * Экспорт XML в 1С - Заказы
     */
    public function query()
    {
        $obOrdersXML = new ExportOrdersByXML();
        $obOrdersXML->createFile();

        try {
            Storage::put(self::EXCHANGE_FOLDER . DIRECTORY_SEPARATOR . self::ORDERS_FILE_NAME, $obOrdersXML->sContent);
        } catch (\Exception $ex) {
            trace_log('Saving sales.xml failed: ' . $ex->getMessage());
        }

        return $this->makeResponse($obOrdersXML->sContent);
    }
}
