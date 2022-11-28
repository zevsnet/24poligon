<?php

namespace SB\Handler;

use SB\Model\Vue\Application;
use SB\Traits\CheckFields;
use SB\Traits\Singleton;

/**
 * Class Vue
 * @package SB\Site\Handler
 */
class Vue
{
    use CheckFields;
    use Singleton;

    /** @var Application[] $applications Список подключаемых приложений */
    protected $applications = [];

    /** @var string $configName Название свойства window с настройками Vue (camelCase) */
    protected $configName = 'vueConfig';

    /**
     * @param Application $application
     */
    public function addApplication(Application $application)
    {
        /** @var Application $applicationExist */
        if (is_array($this->applications)) {
            foreach ($this->applications as $applicationExist) {
                if ($applicationExist->getName() === $application->getName()) {
                    return;
                    // throw new Exception('Добавление существующего приложения');
                }
            }
        }
        $this->applications[] = $application;
    }

    /**
     * Подключение данных
     */
    public function includeData()
    {
        if (empty($this->configName)) {
            return;
        }
        if (!is_array($this->applications)) {
            return;
        }
        $applications = [];
        foreach ($this->applications as $application) {
            $applications[] = [
                'name' => $application->getName(),
                'element' => $application->getElement()
            ];
        }
        ?>
        <script>
          window['<?=$this->configName?>'] = {
            applications: <?=json_encode($applications, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);?> <?php # Список приложений ?>
          } // Глобальные настройки приложения
        </script>
        <?php
        foreach ($this->applications as $application) {
            $encodeDataSetting = (JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
            if (empty($application->data)) {
                $encodeDataSetting = (JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);
            }
            ?>
            <script>
              window['<?=$application->getName()?>'] = {
                config: {
                  component: {
                    name: '<?=$application->component?>', <?php # Название компонента для подключения ?>
                    data: <?=json_encode($application->data, $encodeDataSetting);?> <?php # Данные для передачи в компонент ?>
                  }
                }
              } <?php # Локальные настройки приложения ?>
            </script><?php
        }
    }

    /**
     * Подключение элемента
     * @param string $applicationName
     */
    public function includeElement(string $applicationName)
    {
        foreach ($this->applications as $application) {
            if ($applicationName === $application->getName()) {
                ?>
                <div id="<?= $application->getElement() ?>"></div>
                <?php
            }
        }
    }
}