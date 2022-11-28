<?php

namespace SB\Korona\Type;

class PreCalculatedBns
{

    /**
     * @var \SB\Korona\Type\Active
     */
    private $Active;

    /**
     * @var \SB\Korona\Type\Inactive
     */
    private $Inactive;

    /**
     * @return \SB\Korona\Type\Active
     */
    public function getActive()
    {
        return $this->Active;
    }

    /**
     * @param \SB\Korona\Type\Active $Active
     * @return PreCalculatedBns
     */
    public function withActive($Active)
    {
        $new = clone $this;
        $new->Active = $Active;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Inactive
     */
    public function getInactive()
    {
        return $this->Inactive;
    }

    /**
     * @param \SB\Korona\Type\Inactive $Inactive
     * @return PreCalculatedBns
     */
    public function withInactive($Inactive)
    {
        $new = clone $this;
        $new->Inactive = $Inactive;

        return $new;
    }


}

