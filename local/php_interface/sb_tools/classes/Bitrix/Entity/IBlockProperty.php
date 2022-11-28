<?php

namespace SB\Bitrix\Entity;

use Bitrix\Main\NotImplementedException;
use SB\Bitrix\IBlockBase;


/**
 * Сущность "Свойства инфоблока"
 * Содержит общие свойства и методы
 */
class IBlockProperty extends IBlockBase
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
     * @return mixed|void
     * @throws NotImplementedException
     */
    public static function getById(int $id)
    {
        // TODO: Implement getById() method.
        throw new NotImplementedException();
    }

    /**
     * @param string $code
     * @return mixed|void
     * @throws NotImplementedException
     */
    public static function getByCode(string $code)
    {
        // TODO: Implement getByCode() method.
        throw new NotImplementedException();
    }

    /**
     * @return mixed|void
     * @throws NotImplementedException
     */
    public function create()
    {
        // TODO: Implement create() method.
        throw new NotImplementedException();
    }

    /**
     * @return mixed|void
     * @throws NotImplementedException
     */
    public function update()
    {
        // TODO: Implement update() method.
        throw new NotImplementedException();
    }
}