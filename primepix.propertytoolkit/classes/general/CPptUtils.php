<?php
class CPptUtils {

	public static function getView($path, $viewData = array(), $bExtract = FALSE) {

		if ($bExtract) {
			extract($viewData);
		}
	
		ob_start();
		include($path . '.tpl.php');
		$view = ob_get_contents();
		ob_end_clean();

		return $view;
		
	}

}