<?php

namespace SB\Bitrix\Entity;

use Bitrix\Main\Loader;
use Bitrix\Main\NotImplementedException;
use SB\Bitrix\IBlockBase;
use SB\Exception;

/**
 * Class IBlockElement
 * @package SB\Bitrix\Entity
 */
class IBlockElement extends IBlockBase
{

    /** @var IBlock $iBlock */
    public $iBlock;

    /**
     * IBlockElement constructor.
     * @param IBlock $iBlock
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct(IBlock $iBlock)
    {
        Loader::includeModule('iblock');
        $this->iBlock = $iBlock;
    }

    /**
     * Создает элемент инфоблока
     * @return IBlockElement
     * @throws Exception
     */
    public function create(): self
    {


        $obElement = new \CIBlockElement;


        $arFields = [
            'CODE' => $this->code,
            'NAME' => $this->name,
        ];

        $arFields = array_merge($arFields, $this->arFields);


        if ($elementID = $obElement->Add($arFields)) {

            $this->id = $elementID;
            return $this;
        }

        throw new Exception($obElement->LAST_ERROR);
    }

    /**
     * @return array
     */
    public function getSectionIdList(): array
    {
        $dbSectionList = \CIBlockElement::GetElementGroups($this->id, true, array('ID', 'IBLOCK_ELEMENT_ID'));

        $arSectionsIdList = [];
        while ($arSection = $dbSectionList->Fetch()) {
            $arSectionsIdList[] = (int)$arSection['ID'];
        }

        return $arSectionsIdList;
    }

    /**
     * @return bool|void
     * @throws NotImplementedException
     */
    public function isExist(): bool
    {
        // TODO: Implement isExist() method.
        throw new NotImplementedException();
    }

    /**
     * @param int $id
     * @return mixed|void
     * @throws NotImplementedException
     */
    public static function getById(int $id): self
    {
        // TODO: Implement getById() method.
        throw new NotImplementedException();
    }

    /**
     * @param string $code
     * @return mixed|void
     * @throws NotImplementedException
     */
    public static function getByCode(string $code): self
    {
        // TODO: Implement getByCode() method.
        throw new NotImplementedException();
    }

    /**
     * @return mixed|void
     * @throws NotImplementedException
     */
    public function update(): self
    {
        // TODO: Implement update() method.
        throw new NotImplementedException();
    }
}