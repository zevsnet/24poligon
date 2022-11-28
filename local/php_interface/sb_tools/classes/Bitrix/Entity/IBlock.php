<?php

namespace SB\Bitrix\Entity;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\NotImplementedException;
use SB\Bitrix\IBlockBase;
use SB\Exception;

/**
 * Class IBlock - Инфоблок
 * TODO все выглядит красиво, но нужны тесты
 * Class IBlock
 * @package SB\Bitrix\Entity
 */
class IBlock extends IBlockBase
{

    /** @var array todo need test */
    public static $PERMISSION_ALL_READ = ['*'];


    /** @var iBlockType $iBlockType */
    protected $iBlockType;



    /**
     * IBlock constructor.
     * @param string $code
     * @param IBlockType $iBlockType
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct(string $code, IBlockType $iBlockType)
    {
        Loader::includeModule('iblock');
        $this->code = $code;
        $this->iBlockType = $iBlockType;
    }

    /**
     * Проверка на существование инфоблока
     * @return bool
     */
    public function isExist(): bool
    {
        $arIBlockType = IblockTable::getRow([
            'filter' => [
                'CODE' => $this->code,
                'TYPE.ID' => $this->iBlockType->getId()
            ],
            'select' => ['ID']
        ]);
        return null !== $arIBlockType;
    }

    /**
     * Получение инфоблока по ид
     * @param int $id
     * @return IBlock
     * @throws NotImplementedException
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getById(int $id): self
    {
        $iBlockResult = IblockTable::getById($id)->fetch();
        $iBlockType = IBlockType::getById($iBlockResult['IBLOCK_TYPE_ID']);

        $iBlock = new self($iBlockResult['CODE'], $iBlockType);
        $iBlock->id = $iBlockResult['ID'];
        $iBlock->name = $iBlockResult['NAME'];
        $iBlock->iBlockType = $iBlockType;
        $iBlock->setFields($iBlockResult);

        return $iBlock;
    }

    /**
     * Получение инфоблока по символьному коду
     * @param string $code
     * @return IBlock
     * @throws NotImplementedException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getByCode(string $code): self
    {
        $iBlockResult = IblockTable::getList(
            [
                'filter' => [
                    'CODE' => $code
                ]
            ]
        )->fetch();

        $iBlockType = IBlockType::getById($iBlockResult['IBLOCK_TYPE_ID']);
        $iBlock = new self($iBlockResult['CODE'], $iBlockType);
        $iBlock->id = $iBlockResult['ID'];
        $iBlock->name = $iBlockResult['NAME'];
        $iBlock->iBlockType = $iBlockType;
        $iBlock->setFields($iBlockResult);

        return $iBlock;
    }


    /**
     * Создает инфоблок
     * @return IBlock
     * @throws Exception
     * @throws NotImplementedException
     * @throws \Bitrix\Main\LoaderException
     */
    public function create(): self
    {
        if ($this->isExist()) {
            throw new Exception('Инфоблок существует');
        }

        $arFields = [
            'NAME' => $this->name,
            'CODE' => $this->code,
            'XML_ID' => $this->code,
            'IBLOCK_TYPE_ID' => $this->iBlockType->getId(),
            'RSS_ACTIVE' => 'N',
            'GROUP_ID' => static::$PERMISSION_ALL_READ,
            'WORKFLOW' => 'N',
            'SITE_ID' => SITE_ID
        ];

        $arFields = array_merge($this->arFields, $arFields);

        $obIBlock = new \CIBlock;

        $result = $obIBlock->Add($arFields);
        if ($result) {
            $this->id = $result;
            $this->setFields(self::getById($this->id)->getFields());
        } else {
            throw new Exception($obIBlock->LAST_ERROR);
        }
        return $this;
    }

    /**
     * @return iBlockType
     */
    public function getIBlockType(): iBlockType
    {
        return $this->iBlockType;
    }

    /**
     * @param iBlockType $iBlockType
     */
    public function setIBlockType(iBlockType $iBlockType)
    {
        $this->iBlockType = $iBlockType;
    }


    /**
     * @return mixed
     * @throws NotImplementedException
     */
    public function update()
    {
        // TODO: Implement update() method.
        throw new NotImplementedException();
    }
}