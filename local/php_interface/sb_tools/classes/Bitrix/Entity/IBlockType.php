<?php

namespace SB\Bitrix\Entity;

use Bitrix\Iblock\TypeLanguageTable;
use Bitrix\Iblock\TypeTable;
use Bitrix\Main\Loader;
use Bitrix\Main\NotImplementedException;
use SB\Bitrix\IBlockBase;
use SB\Exception;
use SB\Tools\ForString;

/**
 * Class IBlockType - Тип инфоблока
 * @package SB\Bitrix\Entity
 */
class IBlockType extends IBlockBase
{


    /**
     * @return bool
     * @throws NotImplementedException
     */
    public function isExist(): bool
    {
        // TODO: Implement isExist() method.
        throw new NotImplementedException();
    }

    /**
     * @param int $id
     * @return IBlockType
     * @throws NotImplementedException
     */
    public static function getById(int $id): self
    {
        // TODO: Implement getById() method.
        throw new NotImplementedException();
    }

    /**
     * @param string $code
     * @return IBlockType
     * @throws NotImplementedException
     */
    public static function getByCode(string $code): self
    {
        // TODO: Implement getByCode() method.
        throw new NotImplementedException();
    }

    /**
     * @return IBlockType
     * @throws NotImplementedException
     */
    public function create(): self
    {
        // TODO: Implement create() method.
        throw new NotImplementedException();
    }

    /**
     * @return IBlockType
     * @throws NotImplementedException
     */
    public function update(): self
    {
        // TODO: Implement update() method.
        throw new NotImplementedException();
    }
}