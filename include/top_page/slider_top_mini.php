<!-- Swiper -->
<? $arElementTopSliderMini = \SB\Site\Bitrix\SBElement::getElement([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_SLIDER_TOP_MINI,
    'ACTIVE' => 'Y'
]); ?>

<div class="swiper-container">
    <div class="swiper-wrapper">
        <? foreach ($arElementTopSliderMini as $sbItem): ?>
            <? $color = $sbItem['PROP']['COLOR']['VALUE'] ?>
            <? if ($sbItem['DETAIL_PICTURE']) {
                ?>
                <div style="background-color: <?= $color ? '#' . $color : '#000' ?>; display: flex;"
                     class="swiper-slide"><?
                $SRC = CFile::GetPath($sbItem['DETAIL_PICTURE']);

                ?><a class="sb_full" href="<?= $sbItem['PROP']['LINK']['VALUE'] ?: '#' ?>">
                <img src="<?= $SRC ?>" alt="<?= $sbItem['NAME'] ?>">
                </a>
                </div><?
            } else { ?>
                <div style="background-color: <?= $color ? '#' . $color : '#000' ?>;"
                     class="swiper-slide"><?= $sbItem['~PREVIEW_TEXT'] ?></div><? } ?>
        <? endforeach; ?>
    </div>
</div>