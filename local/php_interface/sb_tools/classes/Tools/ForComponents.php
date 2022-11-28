<?php

namespace SB\Tools;

/**
 * Class ForComponents
 * @package SB\Tools
 */
class ForComponents
{
    /**
     * @param string $path
     * @param array $params
     * @param string $template
     * @return string
     */
    public static function renderIncludeArea(string $path, array $params = [], string $template = ''): string
    {
        return Common::renderTpl($_SERVER['DOCUMENT_ROOT'] . '/include/area.php', [
            'path' => $path,
            'params' => $params,
            'template' => $template
        ]);
    }
}