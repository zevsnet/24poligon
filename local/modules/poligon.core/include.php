<?php
$itrackCorePath = '/local/modules/poligon.core/lib';
if ($_REQUEST['mode'] !== 'import') { // FIX 1c
    Poligon\Core\Iblock\Events::addEventHandlers();//События Инфоблоков
    Poligon\Core\Sale\Events::addEventHandlers();//События Инфоблоков
}