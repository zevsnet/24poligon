<?php

namespace SB;

use Bitrix\Main\Config\Configuration;
use SB\Exception\ComposerNotFountException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Main
{
    /** @var ComposerNotFountException $composerException */
    private $composerException;

    /**
     * Main constructor.
     */
    public function __construct()
    {
        $this->composerException = new ComposerNotFountException('Установите модули (composer install)');
    }

    /**
     * @throws \InvalidArgumentException
     * @throws ComposerNotFountException
     */
    public function init()
    {
        $this->initWhoops();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws ComposerNotFountException
     */
    public function initWhoops()
    {
        $debugConfig = Configuration::getInstance()->get('exception_handling');

        if ($debugConfig['debug']) {

            if (!class_exists(Run::class)) {
                throw $this->composerException;
            }

            $whoops = new Run();
            $whoops->pushHandler(new PrettyPageHandler());
            $whoops->register();
        }
    }
}