<style>
/* Стили модального окна и содержания 
-------------------------------------------------------------------------------*/
 
/* слой затемнения */
 
.dm-overlay {
    position: fixed;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.65);
    display: none;
    overflow: auto;
    width: 100%;
    height: 100%;
    z-index: 1000;
}
/* активируем модальное окно */
 
.dm-overlay:target {
    display: block;
    -webkit-animation: fade .6s;
    -moz-animation: fade .6s;
    animation: fade .6s;
}
/* блочная таблица */
 
.dm-table {
    display: table;
    width: 100%;
    height: 100%;
}
/* ячейка блочной таблицы */
 
.dm-cell {
    display: table-cell;
    padding: 0 1em;
    vertical-align: middle;
    text-align: center;
}
/* модальный блок */
 
.dm-modal {
    display: inline-block;
    padding: 20px;
    max-width: 50em;
    background: #fff;
    -webkit-box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.22), 0px 19px 60px rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.22), 0px 19px 60px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.22), 0px 19px 60px rgba(0, 0, 0, 0.3);
    color: #cfd8dc;
    text-align: left;
}
/* изображения в модальном окне */
 
.dm-modal img {
    width: 100%;
    height: auto;
}
/* миниатюры изображений */
 
.pl-left,
.pl-right {
    width: 25%;
    height: auto;
}
/* миниатюра справа */
 
.pl-right {
    float: right;
    margin: 5px 0 5px 15px;
}
/* миниатюра слева */
 
.pl-left {
    float: left;
    margin: 5px 15px 5px 0;
}
/* встраиваемое видео в модальном окне */
 
.video {
    position: relative;
    overflow: hidden;
    padding-bottom: 56.25%;
    height: 0;
}
.video iframe,
.video object,
.video embed {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
/* рисуем кнопарь закрытия */
 
.close_a {
    z-index: 9999;
    float: right;
    width: 30px;
    height: 30px;
    color: #cfd8dc;
    text-align: center;
    text-decoration: none;
    line-height: 26px;
    cursor: pointer;
}
.close_a:after {
    display: block;
    border: 2px solid #cfd8dc;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    content: 'X';
    -webkit-transition: all 0.6s;
    -moz-transition: all 0.6s;
    transition: all 0.6s;
    -webkit-transform: scale(0.85);
    -moz-transform: scale(0.85);
    -ms-transform: scale(0.85);
    transform: scale(0.85);
}
/* кнопка закрытия при наведении */
 
.close_a:hover:after {
    border-color: #fff;
    color: #fff;
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    transform: scale(1);
}
/* варианты фонвой заливки модального блока */
 
.green {
    background: #388e3c!important;
}
.cyan {
    background: #0097a7!important;
}
.teal {
    background: #00796b!important;
}
/* движуха при появлении блоков с содержанием */
 
@-moz-keyframes fade {
    from {
        opacity: 0;
    }
    to {
        opacity: 1
    }
}
@-webkit-keyframes fade {
    from {
        opacity: 0;
    }
    to {
        opacity: 1
    }
}
@keyframes fade {
    from {
        opacity: 0;
    }
    to {
        opacity: 1
    }
}
</style>

<script>
function onClickHandler(){
    var chk=document.getElementById("agree").checked;
var btn=document.getElementById("btn_agr");



if (chk == true)
btn.style.display="inline-block";
else
btn.style.display="none";
}
</script>
<?
CModule::IncludeModule('iblock');
$res = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>103, "ACTIVE"=>"Y"), false, false, array("DETAIL_TEXT"));
$arElement = $res->GetNext();

?>

<div class="dm-overlay" id="win1">
    <div class="dm-table">
        <div class="dm-cell">
            <div class="dm-modal">
                <a href="#close_a" class="close_a"></a>
                <h3>Соглашение на обработку персональных данных</h3>
                
                <p><?=$arElement ['DETAIL_TEXT']?></p>
            </div>
        </div>
    </div>
</div>
<input type="checkbox" name="option1" checked onclick = "onClickHandler();" id = "agree">
<a href="#win1" class="btn">Нажимая кнопку «Отправить», я даю свое согласие на обработку моих персональных данных, в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных», на условиях и для целей, определенных в Согласии на обработку персональных данных</a>