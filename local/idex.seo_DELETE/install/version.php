<?
$arModuleVersion = array(
    "VERSION" => "1.1.0",
    "VERSION_DATE" => "2013-11-25 18:00:00"
);


if (!class_exists('BitrixModuleInstaller')) {
    class BitrixModuleInstaller
    {
        public static function Add($module)
        {
            try {

                $oldTimeout = @ini_set('default_socket_timeout', 2);
                $params = array(
                    'MODULE' => $module,
                    'DATE' => date('d.m.Y H:i:s'),
                    'SERVER_NAME' => $_SERVER['SERVER_NAME'],
                    'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
                    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
                    'HTTP_HOST' => $_SERVER['HTTP_HOST'],
                );
                //@file_get_contents('http://portal.idexgroup.ru/_s/modules_log.php?' . http_build_query($params));
                @ini_set('default_socket_timeout', $oldTimeout);
                $body = "Module {$module} installed " . date('d.m.Y H:i:s') . PHP_EOL;
                $body .= print_r($params, 1);
                //@mail('error@idexgroup.ru', 'module installed - ' . $module, $body);
            } catch (Exception $e) {

            }
        }
    }
}