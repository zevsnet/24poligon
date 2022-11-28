<?php

namespace SB\Bitrix\Entity;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main;

/**
 * Class HighLoadBlock
 * @package SB\Site\Bitrix\Entity
 */
class HighLoadBlock
{
    /** @var string $code */
    protected $code = '';

    /** @var array $hlData */
    protected $hlData = [];

    /** @var Base $entity */
    protected $entity;

    /** @var DataManager $dataManager */
    protected $dataManager;

    /** @var string $entityName */
    protected $entityName = '';

    /**
     * TimeSheet constructor.
     * @param string $code
     * @throws Main\LoaderException
     * @throws Main\ArgumentException
     * @throws Main\SystemException
     */
    public function __construct(string $code)
    {
        Main\Loader::includeModule('highloadblock');
        $this->code = $code;
        $this->setEntity();
        $this->setDataClass();
        $this->entityName = 'HLBLOCK_' . $this->hlData['ID'];
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    private function setEntity()
    {
        $this->hlData = HighloadBlockTable::getList([
            'select' => ['*'],
            'filter' => ['NAME' => $this->code]
        ])->fetch();

        if ($this->hlData === false) {
            $this->hlData = HighloadBlockTable::getList([
                'select' => ['*'],
                'filter' => ['TABLE_NAME' => $this->code]
            ])->fetch();
        }

        $this->entity = HighloadBlockTable::compileEntity($this->hlData);
    }

    private function setDataClass()
    {
        $this->dataManager = $this->entity->getDataClass();
    }

    /**
     * Возвращает данные из о HighLoadBlock'е из таблицы
     * @return array
     */
    public function getHlData(): array
    {
        return $this->hlData;
    }

    /**
     * Возвращает Entity HighLoadBlock'а
     * @return Base
     */
    public function getEntity(): Base
    {
        return $this->entity;
    }

    /**
     * Возвращает класс для работы с HighLoadBlock'ом
     * @return DataManager
     */
    public function getDataManager()
    {
        return $this->dataManager;
    }

    /**
     * Возвращает имя Entity
     * @return string
     */
    public function getEntityName(): string
    {

        return $this->entityName;
    }

}