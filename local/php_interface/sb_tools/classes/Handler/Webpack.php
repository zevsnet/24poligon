<?php

namespace SB\Handler;

use SB\Exception;
use SB\Model\Webpack\Resource;
use SB\Traits\CheckFields;
use SB\Traits\Singleton;

/**
 * Class Webpack
 * @package SB\Site\Handler
 */
class Webpack
{
    use CheckFields;
    use Singleton;

    /** @var bool $isProduction Флаг типа сборки */
    public $isProduction = true;

    /** @var string $devServerHost Адрес node.js сервера с developer ресурсами */
    public $devServerHost = 'http://vueapp.loc/app.js';

    /** @var string $resourcePath Путь до папки с production ресурсами */
    public $resourcePath = '/dist/static/';

    /** @var bool $applications Список подключаемых приложений */
    protected $applications = [];

    /** @var string $configName Название свойства window с настройками Vue (camelCase) */
    protected $configName = 'vueConfig';

    /** @var null|Resource $resource Объект с ресурсами */
    protected $resource;

    /**
     * Подключает скрипт dev сервера
     */
    protected function includeDevJs()
    {
        ?>
        <script type=text/javascript src="<?= $this->devServerHost ?>"></script>
        <?php
    }

    /**
     * @return \SB\Model\Vue\Resource
     * @throws \SB\Exception
     */
    protected function getResourceList()
    {
        try {
            $vueResource = new Resource();
            if (empty($this->resourcePath)) {
                throw new Exception('Не указан resourcePath');
            }
            $appPath = new \FilesystemIterator($_SERVER['DOCUMENT_ROOT'] . $this->resourcePath);
            if (!$appPath->isDir()) {
                throw new Exception('Путь до ресурсов не корректен');
            }
            /** @var \SplFileInfo $appDir */
            foreach ($appPath as $appDir) {
                if (!$appDir->isDir()) {
                    continue;
                }
                $cssSort = ['app'];
                $jsSort = ['manifest', 'vendor', 'app'];
                switch ($appDir->getFilename()) {
                    case 'css':
                        {
                            $cssPath = new \FilesystemIterator($appDir->getPathname());
                            /** @var \SplFileInfo $cssFile */
                            foreach ($cssPath as $cssFile) {
                                if ($cssFile->isFile() && $cssFile->getExtension() === 'css') {
                                    $explodedName = explode('.', $cssFile->getFilename());
                                    $fileIndex = array_search(current($explodedName), $cssSort, true);
                                    $vueResource->css[$fileIndex] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $cssFile->getPathname());
                                }
                                ksort($vueResource->css);
                            }
                            break;
                        }
                    case 'js':
                        {
                            $jsPath = new \FilesystemIterator($appDir->getPathname());
                            /** @var \SplFileInfo $jsFile */
                            foreach ($jsPath as $jsFile) {
                                if ($jsFile->isFile() && $jsFile->getExtension() === 'js') {
                                    $explodedName = explode('.', $jsFile->getFilename());
                                    $fileIndex = array_search(current($explodedName), $jsSort, true);
                                    $vueResource->js[$fileIndex] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $jsFile->getPathname());
                                }
                                ksort($vueResource->js);
                            }
                            break;
                        }
                }
            }
            return $vueResource;
        } catch (Exception $exception) {
            throw new Exception('Ошибка получения ресурсов. ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Подключение стилей
     */
    public function includeCss()
    {
        if (!$this->isProduction) {
            return;
        }
        $this->resource = $this->getResourceList();
        if (null !== $this->resource && is_array($this->resource->css)) {
            foreach ($this->resource->css as $filePath) {
                ?>
                <link href="<?= $filePath ?>" rel="stylesheet">
                <?php
            }
        }
    }

    /**
     * Подключение скриптов
     */
    public function includeJs()
    {
        if (!$this->isProduction) {
            $this->includeDevJs();
            return;
        }
        $this->resource = $this->getResourceList();
        if (null !== $this->resource && is_array($this->resource->js)) {
            foreach ($this->resource->js as $filePath) {
                ?>
                <script type=text/javascript src="<?= $filePath ?>"></script>
                <?php
            }
        }
    }
}