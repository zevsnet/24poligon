<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление");
?>
<h2>Заголовок H2</h2>
<p>Интернет-магазин — сайт, торгующий товарами в интернете. Позволяет пользователям сформировать заказ на покупку, выбрать способ оплаты и доставки заказа в сети Интернет.&nbsp;</p>
<blockquote>Отслеживание ведется с помощью методов веб-аналитики. Часто при оформлении заказа предусматривается возможность сообщить некоторые дополнительные пожелания от покупателя продавцу. 	</blockquote> 
<h3>Заголовок H3</h3>
<p><i>Однако, в этом случае следует быть осторожным, поскольку доказать неполучение товара электронным способом существенно сложнее, чем в случае физической доставки.</i></p>
<h4>Маркированный список H4</h4>
<ul>
	<li>В интернет-магазинах, рассчитанных на повторные покупки, также ведется отслеживание возвратов песетителя и история покупок.</li>
	<li>Кроме того, существуют сайты, в которых заказ принимается по телефону, электронной почте, Jabber или ICQ.</li>
</ul>
<h5>Нумерованный список H5</h5>
<ol>
	<li>В интернет-магазинах, рассчитанных на повторные покупки, также ведется отслеживание возвратов песетителя и история покупок.</li>
	<li>Кроме того, существуют сайты, в которых заказ принимается по телефону, электронной почте, Jabber или ICQ.</li>
</ol>
<hr class="long"/>
<h5>Таблица</h5>
<table class="colored_table">
	<thead>
		<tr>
			<td>#</td>
			<td>First Name</td>
			<td>Last Name</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>Tim</td>
			<td>Tors</td>
		</tr>
		<tr>
			<td>2</td>
			<td>Denis</td>
			<td>Loner</td>
		</tr>
	</tbody>
</table>
<hr class="long"/>

<div class="view_sale_block">
	<div class="count_d_block">
		<span class="active_to hidden">30.10.2017</span>
		<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
		<span class="countdown values"></span>
	</div>
	<div class="quantity_block">
		<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
		<div class="values">
			<span class="item">
				<span>10</span>
				<div class="text"><?=GetMessage("TITLE_QUANTITY");?></div>
			</span>
		</div>
	</div>
</div>
<hr class="long"/>

<h5>Цвет текста</h5>
<div class="row">
	<div class="col-md-4">
		<div><a href="">Обычная ссылка</a></div>
		<div><a href="" class="dark-color">Темная ссылка</a></div>
	</div>
	<div class="col-md-4">
		<div>Обычный текст</div>
		<div class="darken">Темный текст</div>
		<div class="muted">Светлый (color:999) текст</div>
		<div class="muted888">Светлый (color:888) текст</div>
		<div class="muted777">Светлый (color:777) текст</div>
	</div>
	<div class="col-md-4">
		<div class="colored_theme_text">Текст в цвет темы</div>
		<div class="colored_theme_text_with_hover">Текст в цвет темы с ховером</div>
		<div class="colored_theme_hover_text">Текст в цвет темы при наведении</div>
	</div>
</div>
<hr class="long"/>

<h5>Блоки</h5>
<div class="row">
	<div class="col-md-4">
		<div>Блок с фоном в цвет темы</div>
		<div class="colored_theme_bg" style="height: 30px;"></div>
		<div>Блок с фоном в цвет темы при наведении</div>
		<div class="colored_theme_hover_bg bordered" style="height: 30px;"></div>
	</div>
	<div class="col-md-4">
		<div>Блок с рамкой</div>
		<div class="bordered" style="height: 30px;"></div>
		<div>Блок с тенью при наведении</div>
		<div class="bordered box-shadow" style="height: 30px;"></div>
	</div>
</div>

<hr class="long"/>

<h5>Шрифты</h5>
<div class="row">
	<div class="col-md-4">
		<p class="font_upper_xs mg-t-0">9px uppercase</p>
		<p class="font_xxss mg-t-0">10px</p>
		<p class="font_upper mg-t-0">10px uppercase</p>
		<p class="font_xxs mg-t-0">11px</p>
		<p class="font_upper_md mg-t-0">11px uppercase</p>
	</div>
	<div class="col-md-4">
		<p class="font_sxs mg-t-0">12px</p>
		<p class="font_xs mg-t-0">13px</p>
		<p class="font_sm mg-t-0">14px</p>
		<p class="mg-t-0">1em</p>
	</div>
	<div class="col-md-4">
		<p class="font_md mg-t-0">16px</p>
		<p class="font_mxs mg-t-0">17px</p>
		<p class="font_mlg mg-t-0">18px</p>
		<p class="font_lg mg-t-0">20px</p>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>