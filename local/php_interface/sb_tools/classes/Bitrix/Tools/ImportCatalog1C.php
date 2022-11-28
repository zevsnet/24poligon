<?php

namespace SB\Bitrix\Tools;

use SB\Bitrix\Tools;
use SB\Tools\Output;

/**
 * Класс реализует протокол обмена номенклатурой 1с и сайта
 * Осуществляет загрузку наменклатуры.
 * @todo отрефакторить
 * Class ImportCatalog1C
 * @package SB\Bitrix\Tools
 */
class ImportCatalog1C
{
    protected $protocol = 'http';
    protected $host;

    protected $user;
    protected $password;

    protected $handlerUrl = '/bitrix/admin/1c_exchange.php';

    /**
     * @var Output
     */
    protected $output;

    protected $authCookie = '';

    protected $arInitResponse = [];

    public $timeout = 180;

    public $responseCharset  = 'WINDOWS-1251';


    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;

        parent::__construct();
    }

    /**
     * @param string $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @param string $handlerUrl
     */
    public function setHandlerUrl($handlerUrl)
    {
        $this->handlerUrl = '/' . trim($handlerUrl, '/');
    }

    /**
     * @param Output $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @param string $responseCharset
     */
    public function setResponseCharset($responseCharset)
    {
        $this->responseCharset = $responseCharset;
    }

    /**
     * Выполняет передачу и загрузку файла в БД
     *
     * @param $filePath
     * @throws \RuntimeException
     */
    public function importFile($filePath)
    {
        // запрос на авторизацию
        $this->checkAuthRequest($this->user, $this->password);

        // запрос параметров type=init
        $this->initRequest();

        // загрузка файла type=file
        $this->uploadFile($filePath);

        $filename = pathinfo($filePath, PATHINFO_BASENAME);

        while(1)
        {
            $arResponse = $this->importRequest($filename);

            if ($arResponse['status'] == 'success')
                break;
        }
    }



    /**
     * Загружает все файлы, котрые есть в каталоге и подкаталоге как в папке webdata
     *
     * @param $dirPath
     */
    function importDirectory($dirPath)
    {
        $dirPath = rtrim($dirPath, DIRECTORY_SEPARATOR);

        $arDirs = array($dirPath);

        $arFiles = array();
        while(1)
        {
            if (empty($arDirs))
                break;

            $dirPath = array_shift($arDirs);

            $arGlob = glob($dirPath . DIRECTORY_SEPARATOR . '*');

            $arTmp = array();

            foreach($arGlob as $path)
            {
                if (is_dir($path))
                {
                    $arDirs[] = $path;
                }
                else
                {
                    $arTmp[] = $path;
                }
            }

            usort($arTmp, array($this, "sort"));
            $arFiles = array_merge($arFiles, $arTmp);
        }

        foreach($arFiles as $filePath)
        {
            $this->importFile($filePath);
        }
    }

    /**
     * Отправляет запрос на авторизацию mode=checkauth type=catalog
     *
     * @param $user
     * @param $password
     *
     * @throws \RuntimeException
     */
    function checkAuthRequest($user, $password)
    {
        $this->user = $user;
        $this->password = $password;

        //Авторизация
        $url = $this->getUrl() . '?mode=checkauth&type=catalog';

        $response = $this->request($url, [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $user . ':' . $password
        ]);

        if(!$response)
            throw new \RuntimeException('Auth error');

        $arResponseBody = explode("\n", $response);

        if((!trim($arResponseBody[0]) == 'success' && trim($arResponseBody[1]) == 'PHPSESSID'))
            throw new \RuntimeException('Bad response for checkout request');


        $this->authCookie = "PHPSESSID={$arResponseBody[2]}";
    }

    /**
     * Запрос параметров обмена  сайтас
     *
     * TODO: Сейчас не работае архивирование. надо доделать
     *
     * @return string
     */
    function initRequest()
    {
        $url = $this->getUrl() . '?mode=init&type=catalog';

        $response = $this->request($url);

        $arResponse = [];
        parse_str(str_replace("\n", '&',$response), $arResponse);

        if (!(array_key_exists('zip', $arResponse) && array_key_exists('zip', $arResponse)))
            throw new \RuntimeException('Bad response for init request');

        $arResponse['file_limit'] = (int)$arResponse['file_limit'];

        if (!$arResponse['file_limit'])
            throw new \RuntimeException('Bad response for init request');

        $this->arInitResponse = $arResponse;

        return $response;
    }

    /**
     * Пыполняет запросы по отправки файла на сайт
     *
     * @param string $srcFilePath
     */
    function uploadFile($srcFilePath)
    {
        if (!file_exists($srcFilePath))
            throw new \LogicException('Uploading file does not exist');

        if (!$this->arInitResponse)
            throw new \LogicException('You must send request init before request file');

        $arPathInfo = pathinfo($srcFilePath);

        $filename = $arPathInfo['basename'];

        if ($this->arInitResponse['zip'] == 'yes')
        {
            $zipFilePath = "{$arPathInfo['dirname']}/{$arPathInfo['filename']}.zip";

            $Zip = new \ZipArchive;
            $Zip->open($zipFilePath, \ZIPARCHIVE::CREATE);
            $Zip->addFile($srcFilePath, $arPathInfo['basename']);
            $Zip->close();

            $srcFilePath = $zipFilePath;

            $filename = pathinfo($srcFilePath, PATHINFO_BASENAME);
        }

        $size = filesize($srcFilePath);

        $fileLimit = $this->arInitResponse['file_limit'];
        $countPart = ceil($size / $fileLimit);

        $fh = fopen($srcFilePath, 'r');

        for($i=0;$i<$countPart;$i++)
        {
            $data = fread($fh, $fileLimit);

            $this->fileRequest($filename, $data);
        }

        fclose($fh);

        if (file_exists($zipFilePath))
            unlink($zipFilePath);
    }

    /**
     * Запрос отправки файла на сайт
     *
     * @param string $filename
     * @param mixed $data
     *
     * @return string
     */
    function fileRequest($filename, $data)
    {
        if (!$filename)
            throw new \LogicException('Filename can not be empty');

        $url = $this->getUrl() . "?type=catalog&mode=file&filename=$filename";

        $response = $this->request($url, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        ]);

        if (strpos($response, 'success') !== 0)
            throw new \RuntimeException('Bad response for file request');

        return $response;
    }

    /**
     * Запрос параметров обмена  сайтас
     *
     * @param string $filename
     *
     * @return string
     */
    function importRequest($filename)
    {
        if (!$filename)
            throw new \InvalidArgumentException('Filename can not be empty');

        $url = $this->getUrl() . "?mode=import&type=catalog&filename=$filename";

        $response = $this->request($url);

        $arResponse = explode("\n", $response);

        $status = array_shift($arResponse);

        if ($status != 'success' && $status != 'progress')
            throw new \RuntimeException('Bad response for file request');

        $arResponse['status'] = $status;
        $arResponse['message'] = array_shift($arResponse);
        $arResponse['response'] = $response;

        return $arResponse;
    }

    /**
     * сортировка файлов
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    protected function sort($a, $b)
    {
        $arRang = array(
            "import",
            "offers",
            "prices",
            "rests"
        );

        $arA = explode("/", $a);
        $lastA = array_pop($arA);
        $a = preg_replace("/___.*/", "", $lastA);
        $a = array_search($a, $arRang);
        $a = $a === false ? 10 : $a;

        $arB = explode("/", $b);
        $lastB = array_pop($arB);
        $b = preg_replace("/___.*/", "", $lastB);
        $b = array_search($b, $arRang);
        $b = $b === false ? 10 : $b;


        if($a === $b)
        {
            return 0;
        }

        return $a > $b ? 1 : -1;
    }

    /**
     * формирует url
     *
     * @return string
     */
    protected function getUrl()
    {
        return $this->protocol . "://" . $this->host . $this->handlerUrl;
    }

    /**
     * Посылвает запрос на сайт
     *
     * @param $url
     * @param array $arOptions - парамктры курл
     *
     * @return string
     */
    protected function request($url, $arOptions = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);

        if ($this->authCookie)
            curl_setopt($curl, CURLOPT_COOKIE, $this->authCookie);

        curl_setopt($curl, CURLOPT_URL, $url);

        foreach($arOptions as $opt=>$value)
        {
            $opt = (int)$opt;

            curl_setopt($curl, $opt, $value);
        }

        $this->log('Request: ' . $url);

        $res = curl_exec($curl);
        $info = curl_getinfo($curl);

        $response = substr($res, $info['header_size']);
        $header = substr($res, 0, $info['header_size']);

        $arCharset = array();

        if(preg_match("/charset=.+/", $header, $arCharset) !== false)
        {
            $this->responseCharset = strtoupper(str_replace("charset=", "", trim(current($arCharset))));
        }

        if ($response === false)
            throw new \RuntimeException(curl_error($curl));

        curl_close($curl);

        // конвертируем если коировки различаются
        if (ini_get('default_charset') != $this->responseCharset)
        {
            $response = iconv($this->responseCharset, ini_get('default_charset'), $response);
        }

        $this->log('Response: >>>>>>>>>>>>>>>>>>>>>>');
        $this->log($response);
        $this->log('================================');

        return $response;
    }

    protected function log($text)
    {
        if (!$this->output)
            return;

        $this->output->writeln($text);
    }

}