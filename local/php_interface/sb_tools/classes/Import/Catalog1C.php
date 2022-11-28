<?php

namespace SB\Import;

use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use SB\Tools\Log;
use SB\Exception;

/**
 * @todo надо ли?
 * Class Catalog1C
 * @package SB\Import
 */
class Catalog1C
{
    protected $useLog = true;
    protected $logFilePath = '/upload/logs/Import/1C/Import of ';
    protected $logFileExtension = '.txt';
    protected $logDateFormat = 'Y-m-d H:i:s';
    protected $host;
    protected $login;
    protected $password;
    protected $optionModuleName = 'main';
    protected $optionName = 'last_import_catalog';
    protected $errorOptionName = 'error_import_catalog';

    /**
     * @throws ArgumentOutOfRangeException
     */
    public function disableCheck()
    {
        Option::set("catalog", "DEFAULT_SKIP_SOURCE_CHECK", "Y");
        Option::set("sale", "secure_1c_exchange", "N");
    }

    /**
     * @param string $fileName
     * @param string $type
     * @param bool $dump
     * @return bool
     * @throws ArgumentOutOfRangeException
     */
    public function Import($fileName = '', $type = 'catalog', $dump = false)
    {
        try {
            # Файл лога
            if ($this->useLog) {
                $dateStart = date($this->logDateFormat);
                $logFilePath = $_SERVER["DOCUMENT_ROOT"] . $this->logFilePath . $dateStart . $this->logFileExtension;
                $log = new Log($logFilePath, 'ab+', true);
                if (null === $log) {
                    throw new Exception('Файл лога не создан');
                }
                $log->add('Начало импорта');
            }
            try {
                if (!\in_array($type, ['sale', 'catalog', 'reference'], true)) {
                    throw new Exception('Не верный тип выгрузки');
                }
                # Папка с файлами для импорта
                switch ($type) {
                    case 'sale':
                        {
                            $folder = '1c_catalog';
                            break;
                        }
                    case 'reference':
                        {
                            $folder = '1c_highloadblock';
                            break;
                        }
                    default:
                        {
                            $folder = '1c_catalog';
                        }
                }
                $workPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/{$folder}/";
                if (!file_exists($workPath)) {
                    throw new Exception('Рабочий каталог не существует');
                }
                # Папка с импортированными файлами
                $uploadPath = $workPath . 'uploaded/';
                if (!file_exists($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true) && is_dir($uploadPath)) {
                        throw new Exception('Каталог для импортированных файлов не существует');
                    }
                }
                # Авторизация
                $obCurl = curl_init();
                $url = $this->host . '/bitrix/admin/1c_exchange.php?mode=checkauth&type=' . $type;
                curl_setopt($obCurl, CURLOPT_HEADER, false);
                curl_setopt($obCurl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($obCurl, CURLOPT_URL, $url);
                curl_setopt($obCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($obCurl, CURLOPT_USERPWD, $this->login . ":" . $this->password);
                $response = curl_exec($obCurl);
                curl_close($obCurl);
                $arResponseBody = explode("\n", $response);
                if (count($arResponseBody) > 1 && trim($arResponseBody[0]) === 'success' && trim($arResponseBody[1]) === 'PHPSESSID') {
                    $sessidParam = $arResponseBody[3];
                    $strCookie = 'PHPSESSID=' . $arResponseBody[2] . '; path=/';
                    if ($this->useLog && null !== $log && $log instanceof Log) {
                        $log->add('Авторизация прошла успешно');
                    }
                } else {
                    throw new Exception('Не удалось авторизоваться');
                }
                $arFiles = scandir($workPath, null);
                if (empty($arFiles)) {
                    throw new Exception('Нет файлов в рабочей директории');
                }
                foreach ($arFiles as $key => $file) {
                    $filePath = $workPath . $file;
                    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                    if ($ext !== 'xml') {
                        continue;
                    }
                    $arFiles[$file] = filemtime($filePath);
                }
                # Если указан файл, берем его
                if (!empty($fileName) && array_key_exists($fileName, $arFiles)) {
                    $workFilePath = $fileName;
                } else {
                    #  Если есть файлы, то берем самый ранний по дате
                    asort($arFilesImport);
                    $workFilePath = key($arFilesImport);
                }
                if (empty($workFilePath)) {
                    throw new Exception('Файл не найден');
                }
                /** @var \XMLReader $xml */
                $xml = \XMLReader::open($workPath . $workFilePath);
                $xml->setParserProperty(\XMLReader::VALIDATE, true);
                if ($xml->isValid()) {
                    $arLogFields['IMPORT_PROCESS'][] = 'Файл валидный';
                } else {
                    throw new Exception('Файл не валидный');
                }
                if ($this->useLog && null !== $log && $log instanceof Log) {
                    $log->add('Импорт файла ' . $workFilePath);
                }
                $url = $this->host . '/bitrix/admin/1c_exchange.php?type=' . $type . '&mode=import&filename=' . $workFilePath . '&' . $sessidParam;
                $error = true;
                $copyAndUnlinkError = true;
                for ($i = 0; $i < 1000; $i++) {
                    $obCurl = curl_init();
                    curl_setopt($obCurl, CURLOPT_HEADER, false);
                    curl_setopt($obCurl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($obCurl, CURLOPT_URL, $url);
                    curl_setopt($obCurl, CURLOPT_COOKIE, $strCookie);
                    $response = trim(iconv('cp1251', 'utf-8', curl_exec($obCurl)));
                    curl_close($obCurl);
                    if (empty($response)) {
                        break;
                    }
                    if ($dump) {
                        \_::d($response);
                    }
                    $arLogFields['IMPORT_PROCESS'][] = $response;
                    if (strpos($response, 'progress') === 0) {
                        continue;
                    }

                    if (strpos($response, 'success') === 0) {
                        $error = false;
//                        if (copy($workPath . $workFilePath, $uploadPath . str_replace('.xml', '', $workFilePath) . '_by_' . date('Y-m-d_H:i:s') . '.xml')) {
//                            unlink($workPath . $workFilePath);
//                            $copyAndUnlinkError = false;
//                        }
                        break;
                    }
                    break;
                }
                if ($error) {
                    throw new Exception('Во время импорта файла ' . $workFilePath . ' возникла ошибка');
                }

                if ($copyAndUnlinkError) {
                    if ($this->useLog && null !== $log && $log instanceof Log) {
                        $log->add('Файл ' . $workFilePath . 'импорнирован, но не удален');
                    }
                } else {
                    if ($this->useLog && null !== $log && $log instanceof Log) {
                        $log->add('Файл ' . $workFilePath . ' успешно импортирован');
                    }
                }
                $dateEnd = date($this->logDateFormat);
                Option::set($this->optionModuleName, $this->optionName, $dateEnd);
            } catch (\Throwable $throwable) {
                if ($dump) {
                    \_::d($throwable->getMessage());
                }
                if ($this->useLog && null !== $log && $log instanceof Log) {
                    $log->add('Ошибка: ' . $throwable->getMessage());
                }
            }
            Option::set($this->optionModuleName, $this->errorOptionName, '');
            if ($this->useLog && null !== $log && $log instanceof Log && $dump) {
                $log->printFile();
            }
            return true;
        } catch (\Throwable $throwable) {
            Option::set($this->optionModuleName, $this->errorOptionName, date($this->logDateFormat) . ': ' . $throwable->getMessage());
            if ($dump) {
                \_::d($throwable->getMessage());
            }
            return false;
        }
    }
}