<?php namespace Albus\ShopOneSync\Controllers;

use Albus\ShopOneSync\Classes\Resources\Catalog;
use Albus\ShopOneSync\Classes\Resources\CatalogExtension;
use Exception;
use Illuminate\Http\Request;

class ExchangeController {
    public $obResponse;

    public function __invoke(Request $obRequest, Catalog $obCatalog, CatalogExtension $obExCat)
    {
        $type = e($obRequest->get('type'));
        $mode = e($obRequest->get('mode'));
        $this->log('request: '.print_r($obRequest->all(), true));
        if (empty($mode) or empty($mode)) {
            throw new Exception('Нут');
        }
        
        $object = null;
        switch ($type) {
            case 'catalog':
                $object = $obExCat;
                break;
            default:
                throw new Exception('Неверный тип: ' . $type);
        }
    
        if (!method_exists($object, $mode)) {
            throw new Exception('Метод "' . $mode . '" не найден!');
        }
    
        try {
            $this->obResponse = $object->$mode();
        } catch (Exception $e) {
            $this->log(
                "exchange_1c: failure \n".$e->getMessage()."\n".$e->getFile()."\n".$e->getLine()."\n",
                'error'
            );
            $response = "failure\n";
            $response .= $e->getMessage()."\n";
            $response .= $e->getFile()."\n";
            $response .= $e->getLine()."\n";
    
            return response($response, 500, ['Content-Type', 'text/plain']);
        }
    
        return $this->obResponse;
    }

    private function log(string $message, string $type = 'info'): void
    {
        \Log::info($message);
    }
}