<?php


namespace Poligon\Core\Order\DaData;


class Common extends DaData
{
    public function execute()
    {
        return $this->send($this->getData());
    }
}
