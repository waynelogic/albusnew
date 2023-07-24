<?php namespace Albus\ShopOneSync\Classes\Resources;

use Input;
use Carbon\Carbon;
use Session, Response, Storage;
use October\Rain\Filesystem\Zip;
use Illuminate\Http\Request;
use Albus\ShopOneSync\Models\Settings;

/**
 * Class Resource
 * @package Lovata\CommerceMLShopaholic\Classes\Resource
 */
abstract class AbstractResource
{
    /** @var string */
    const EXCHANGE_FOLDER = 'exchange';

    const NO_FILE = 'Файл не обнаружен';

    /** @var Request */
    protected $obRequest;

    protected $obItem;

    public $mainPrice;

    public $cleanFolder;

    public $filepath;

    public $start_time;

    public $max_exec_time;

    public $zip;

    public function __construct(Request $obRequest)
    {
        $this->obRequest = $obRequest;
        $this->mainPrice = Settings::get('mainPrice') == '1' ? true : false;
        $this->cleanFolder = Settings::get('cleanFolder') == '1' ? true : false;
        $this->zip = Settings::get('zip') == '1' ? 'yes' : 'no';
        $this->start_time = microtime(true);
        $this->max_exec_time = min(30, @ini_get("max_execution_time"));
    }

    /**
     * 1C METHOD
     * Запрос авторизации
     */
    public function checkauth()
    {
        return $this->successFullResponse();
    }

    /**
     * Запрос параметров версии синхронищации
     */
    public function init()
    {
        $answer[] = 'zip=' . $this->zip;
        $answer[] = 'file_limit=' . $this->file_upload_max_size();
        $answer[] = '493e67946a9a9c51156913d679baace9';
        $answer[] = 'version=3.1';

        return $this->answer($answer);
    }

    /**
     * Стандартная функция 1С. Загрузка файла на сервер.
     * File uploading function.
     */
    public function file()
    {
        if (!Storage::exists(self::EXCHANGE_FOLDER)) Storage::makeDirectory(self::EXCHANGE_FOLDER);
        $sFilename = Input::get('filename');
        $sPath = $this->getExchangeFilePath($sFilename);
        Storage::put($sPath, $this->obRequest->getContent());
        if (!Storage::disk('local')->exists($sPath)) {
            return $this->failureFullResponse('Нет файла');
        }
        if (str_contains($sFilename, '.zip')){
            Zip::extract(Storage::path($sPath), Storage::path($this->getExchangeFilePath('')));
        };
        return $this->successResponse();
    }

        
    /**
     * Стандартная функция 1С. Инициализирует опреацию импорта файла, загруженного предыдущей фнкцией File.
     * Standart function of 1C. Inicialize operation to import file, loaded by previous finction File.
     *
     * @return void
     */
    public function import()
    {
        // \Log::info('Заголовки');
        // \Log::info($this->obRequest->header());
        $sFilename = Input::get('filename');
        $sPath = $this->getExchangeFilePath($sFilename);
        if (Storage::disk('local')->exists($sPath) === false) {
            \Log::info(self::NO_FILE . ' - '. $sFilename);
            return $this->failureFullResponse(self::NO_FILE);
        } else {
            $this->filepath = Storage::path($sPath);
            $this->parse();
            return $this->answer(['success']);
        }
    }

    public function getExchangeFilePath($filename)
    {
        return self::EXCHANGE_FOLDER . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Default parse method. Doesn't do anything.
     * You should redefine this method in your custom resourse.
     *
     * @return boolean
     */
    public function parse()
    {
        return true;
    }

    /**
     * INTERNAL METHOD
     *
     * returns response with custom content
     * @return Response
     */
    protected function makeResponse($sContent)
    {
        return Response::make($sContent)->header('Content-Type', 'text/plain');
    }

    /**
     * INTERNAL METHOD
     *
     * returns response with short success content
     * @return Response
     */
    protected function successResponse()
    {
        return Response::make("success\n")->header('Content-Type', 'text/plain');
    }

    protected function failureFullResponse($sError = null)
    {
        $sResponse = "failure\n";
        if (!empty($sError)) {
            $sResponse .= $sError . "\n";
        }
        $sResponse .= 'timestamp='.time();

        return Response::make($sResponse)->header('Content-Type', 'text/plain');
    }


    /**
     * INTERNAL METHOD
     *
     * returns response with rich success content
     * @return Response
     */
    protected function successFullResponse()
    {
        $sResponse = "success\n";
        $sResponse .= config('session.cookie')."\n";
        $sResponse .= Session::getId()."\n";
        $sResponse .= 'timestamp='.time();

        return Response::make($sResponse)->header('Content-Type', 'text/plain');
    }

    public function query()
    {
        $sResponse = "success\n";
        $sResponse .= config('session.cookie')."\n";
        $sResponse .= Session::getId()."\n";
        $sResponse .= 'timestamp='.time();

        return Response::make($sResponse)->header('Content-Type', 'text/plain');
    }

    /**
     * Деактивировать не вошедщие в выгрузку товары
     */
    public function deactivate()
    {
        $this->clearStorage();
        $this->lastSync();
        return $this->successResponse();
    }

    /**
     * Синхронизация завершена
     */
    public function complete()
    {
        $this->clearStorage();
        $this->lastSync();
        return $this->successResponse();
    }

    public function clearStorage()
    {
        if ($this->cleanFolder == true) {
            Storage::deleteDirectory(self::EXCHANGE_FOLDER);
        }
    }

    public function lastSync()
    {
        Settings::set('dateLastUpdate', Carbon::now()->format('Y-m-d H:i:s'));
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


    /**
     * Сервисные функции
     *
     * @return float|int
     */
    private function file_upload_max_size() {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $max_size = $this->parse_size(ini_get('post_max_size'));

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }
    private function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }

}
