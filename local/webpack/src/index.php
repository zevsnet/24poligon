<? /** В этот файл инжектится JS с хешем, подключать из папки src */
$asset = \Bitrix\Main\Page\Asset::getInstance();
?>
<% for (var css in htmlWebpackPlugin.files.css) { %>
<? $asset->addCss('<%= htmlWebpackPlugin.files.css[css] %>'); ?>
<% } %>
<% for (var js in htmlWebpackPlugin.files.js) { %>
<? $asset->addJs('<%= htmlWebpackPlugin.files.js[js] %>'); ?>
<% } %>
<% if (htmlWebpackPlugin.options.scripts) { %>
<% for (var item of htmlWebpackPlugin.options.scripts) { %>
<? $asset->addJs('<%= webpackConfig.output.publicPath %><%= item %>'); ?>
<% } %>
<% } %>