<?php
\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
?>

<div class="SB_DPD_baloon">
    <div class="SB_DPD_iName"><?= $item['NAME'] ?></div>
    <div class="SB_DPD_iAdress"><?= $item['ADDRESS_FULL'] ?></div>

    <? $timetable = [$item['PROP']['SCHEDULE']['VALUE']]; ?>

    <?php if ($timetable) { ?>
        <div class="SB_DPD_iSchedule">
            <?php foreach (preg_split('!<br>!', $timetable) as $schedule) { ?>
                <div>
                    <div class="SB_DPD_iTime SB_DPD_icon"></div>
                    <div class="SB_DPD_baloonDiv"><?= $schedule ?></div>
                    <div style="clear: both;"></div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (!empty($item['ADDRESS_DESCR'])) { ?>
        <div class="SB_DPD_address-descr">
            <div><b><?= GetMessage('IPOLH_SB_DPD_PICKUP_ADDRESS_DESCR') ?></b></div>
            <div><?= $item['ADDRESS_DESCR'] ?></div>
        </div>
    <?php } ?>
</div>
