<?php
$itrackCorePath = '/local/modules/poligon.core/lib';

Poligon\Core\Main\Events::addEventHandlers();
Poligon\Core\Admin\Events::addEventHandlers();
Poligon\Core\Aspro\Events::addEventHandlers();
//Poligon\Core\Iblock\Events::addEventHandlers();//События Инфоблоков
Poligon\Core\Sale\Events::addEventHandlers();//События для Магазина
