<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 14.03.2018
 * Time: 12:19
 * @author Denis Kolosov <kdnn@mail.ru>
 */
namespace SB\Handler;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\EventResult;
use Bitrix\Main\Type\DateTime;
use SB\Tools\Output;

/**
 * класс для работы с Агентами
 *
 * !!!Внимание!!! не работает с анонимными функциями
 *
 * Class Agent
 * @package SB\Handler
 * @example Handler/Agent.php 2
 */
class Agent
{
    /** @var callable функция для вызова */
    protected $function;
    /** @var array параметры функции */
    protected $functionArgs = [];
    /** @var string имя модуля */
    protected $moduleName = '';
    /** @var int сортировка */
    protected $sort = 100;
    /** @var bool активность */
    protected $active = true;
    /** @var int интервал */
    protected $interval = 86400;
    /** @var DateTime дата первого запуска */
    protected $nextExecTime;
    /** @var bool флаг переодичности */
    protected $period = false;

    /** @var bool флаг проверки агента на уникальность */
    protected $unique = false;
    /** @var bool флаг повторения функции */
    protected $repeat = true;

    /** @var string родительский класс для логирования */
    protected $logClassParent = Output::class;
    /** @var string класс логирования */
    protected $logClass;
    /** @var array параметры для конструктора класса логирования */
    protected $logArgs = [];

    /**
     * Agent constructor.
     * @param callable $function
     * @param array $functionArgs
     * @throws \Bitrix\Main\ObjectException
     */
    public function __construct(callable $function, array $functionArgs = [])
    {
        $this->function = $function;
        $this->functionArgs = $functionArgs;
        $this->nextExecTime = new DateTime();
    }

    /**
     * установка имени модуля для агента
     * @param string $moduleName
     * @return Agent
     */
    public function setModuleName(string $moduleName): Agent
    {
        $this->moduleName = $moduleName;
        return $this;
    }
    /**
     * установка сортировки агента
     * @param int $sort
     * @return Agent
     */
    public function setSort(int $sort): Agent
    {
        $this->sort = $sort;
        return $this;
    }
    /**
     * установка активности агента
     * @param bool $active
     * @return Agent
     */
    public function setActive(bool $active): Agent
    {
        $this->active = $active;
        return $this;
    }
    /**
     * установка интервала агента
     * @param int $interval
     * @return Agent
     */
    public function setInterval(int $interval): Agent
    {
        $this->interval = $interval;
        return $this;
    }
    /**
     * установка следующего запуска агента
     * @param DateTime $nextExecTime
     * @return Agent
     */
    public function setNextExecTime(DateTime $nextExecTime): Agent
    {
        $this->nextExecTime = $nextExecTime;
        return $this;
    }
    /**
     * переодичен ли агент
     * @param bool $period
     * @return Agent
     */
    public function setPeriod(bool $period): Agent
    {
        $this->period = $period;
        return $this;
    }


    /**
     * установка уникальности агента
     * @param bool $unique
     * @return Agent
     */
    public function setUnique(bool $unique): Agent
    {
        $this->unique = $unique;
        return $this;
    }
    /**
     * установка повторяемости
     * @param bool $repeat
     * @return Agent
     */
    public function setRepeat(bool $repeat): Agent
    {
        $this->repeat = $repeat;
        return $this;
    }
    /**
     * установка класса логирования
     * @param string $logClass
     * @return Agent
     * @throws ArgumentTypeException
     */
    public function setLogClass(string $logClass): Agent
    {
        if(!is_subclass_of($logClass, $this->logClassParent)) {
            throw new ArgumentTypeException('logClass');
        }
        $this->logClass = $logClass;
        return $this;
    }
    /**
     * установка параметров для конструктора для класса логирования
     * @param array $logArgs
     * @return Agent
     */
    public function setLogArgs(array $logArgs): Agent
    {
        $this->logArgs = $logArgs;
        return $this;
    }


    /**
     * выполнение функции, предназначено для выполнения агентов в обёртке,
     * для упразднения действий и возможности отладки через лог
     *
     * в колбэк-функцию передаётся 1 параметр типа '\Bitrix\Main\Event'
     *
     * @param array $arParams
     *
     * @return string
     * @throws \Exception
     */
    public static function exec(array $arParams = array())
    {
        $event = null;
        try {
            /** @var Output $log */
            $log = null;
            if(!empty($arParams['logClass'])) {
                $log = new $arParams['logClass'](...$arParams['logArgs']);
            }

            $event = new \Bitrix\Main\Event('sb', 'AgentHandler', [
                'log' => $log,
                'args' => $arParams['functionArgs']
            ]);

            \call_user_func($arParams['function'], $event);
        } catch (\Exception $e) {
            if ($log === null) {
                throw $e;
            }

            $log->writeln($e->getMessage());
        } finally {
            if(!$arParams['repeat']) {
                return;
            }

            if (isset($event)) {

                foreach ($event->getResults() as $eventResult) {
                    if($eventResult->getType() === EventResult::ERROR) {
                        return;
                    }

                    /** @var array $params */
                    if($params = $eventResult->getParameters()) {
                        $arParams['functionArgs'] = $params['args'] ?: $arParams['functionArgs'];
                    }
                }
            }

            return static::buildFunctionString($arParams);
        }
    }

    /**
     * построение строки для агента
     * @param array $functionArgs
     * @return string
     */
    public static function buildFunctionString(array $functionArgs = []): string
    {
        return '\\' . ltrim(static::class, '\\') . '::exec(' . var_export($functionArgs, true) . ');';
    }


    /**
     * Создаёт агента в обёртке
     * @return bool|int
     */
    public function create()
    {
        $function = self::buildFunctionString([
            'function' => $this->function,
            'functionArgs' => $this->functionArgs,
            'repeat' => $this->repeat,
            'logClass' => $this->logClass,
            'logArgs' => $this->logArgs
        ]);

        if($this->unique) {
            $dbAgents = \CAgent::GetList([], ['=NAME' => $function]);
            if($arAgents = $dbAgents->Fetch()) {
                return (int) $arAgents['ID'];
            }
        }

        $agentId = \CAgent::Add([
            'MODULE_ID' => $this->moduleName,
            'SORT' => $this->sort,
            'NAME' => $function,
            'ACTIVE' => $this->active ? 'Y' : 'N',
            'AGENT_INTERVAL' => $this->interval,
            'IS_PERIOD' => $this->period ? 'Y' : 'N',
            'NEXT_EXEC' => $this->nextExecTime
        ]);

        return (int) $agentId;
    }
}