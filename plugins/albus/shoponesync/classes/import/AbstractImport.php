<?php namespace Albus\ShopOneSync\Classes\Import;

use SimpleXMLElement;
use System\Models\File;
use System\Classes\PluginManager;

abstract class AbstractImport {

    const EXCHANGE_FOLDER = 'exchange';

    public $obReader;
    
    public $loadProps;

    public function __construct($obReader) {
        $this->obReader = $obReader;
        $this->loadProps = PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic');
        $this->init();
    }

    public function init() {
        return true;
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
    public function getExchangeFilePath($filename)
    {
        return self::EXCHANGE_FOLDER . DIRECTORY_SEPARATOR . $filename;
    }
}
