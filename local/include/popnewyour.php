<?/* if($_COOKIE['pop_your'] !=1):?>
<?CUtil::InitJSCore(array('window'));?> 
 <script> 
 
var Dialog = new BX.CDialog({
   title: "Дорогой покупатель, с Новым годом!!! =)",
   content: 'Заказы сделаные с 29.12.2016 по 06.01.2017 будут обработаны только 7 января 2017 г.<br> Спраздником всех, с новым годом, с новым счастьем!',

   icon: 'head-block',

   resizable: true,
   draggable: true,
   height: '90',
   width: '700',
   buttons: [ BX.CDialog.btnClose]
}); 
Dialog.Show(); 
</script> 
<?setcookie ("pop_your", true,time()+3600);?>
<?endif;?>
 */?>