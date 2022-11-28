<?php

namespace SB\Korona\Type;

class Deal
{

    /**
     * @var \SB\Korona\Type\Id
     */
    private $id;

    /**
     * @var \SB\Korona\Type\TypeId
     */
    private $typeId;

    /**
     * @return \SB\Korona\Type\Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \SB\Korona\Type\Id $id
     * @return Deal
     */
    public function withId($id)
    {
        $new = clone $this;
        $new->id = $id;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\TypeId
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param \SB\Korona\Type\TypeId $typeId
     * @return Deal
     */
    public function withTypeId($typeId)
    {
        $new = clone $this;
        $new->typeId = $typeId;

        return $new;
    }


}

