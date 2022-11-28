<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?
$NAME_SITE = '';
$PHONE = '';
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Сайт <?= $NAME_SITE ?> закрыт на реконструкция</title>
    <style>body, html {
            height: 100%;
        }

        html, body, div, span, applet, object, iframe, /*h1,2, h3, h4, h5, h6,*/
        p, blockquote, pre, a, abbr, acronym, address, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
        }

        body {
            line-height: 1;
        }

        ol, ul {
            list-style: none;
        }

        blockquote, q {
            quotes: none;
        }

        blockquote:before, blockquote:after, q:before, q:after {
            content: '';
            content: none;
        }

        :focus {
            outline: 0;
        }

        del {
            text-decoration: line-through;
        }

        table {
            border-spacing: 0;
        }

        .clear {
            clear: both;
            display: block;
            overflow: hidden;
            visibility: hidden;
            width: 0;
            height: 0;
        }

        /*------------------------------------------------------------------ */

        /* Page Style */
        body {
            color: #e9e2ee;
            font-size: 18px;
            font-family: sans-serif;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
            background: seagreen;
            -webkit-box-shadow: inset 0 0 300px rgba(0, 0, 0, 0.5);
            -moz-box-shadow: inset 0 0 300px rgba(0, 0, 0, 0.5);
            box-shadow: inset 0 0 300px rgba(0, 0, 0, 0.5);
        }

        .main-block {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: auto;
        }

        a,
        a:link,
        a:visited {
            color: #c6eaf7;
            font-weight: bold;
            text-decoration: none;
        }

        a:active,
        a:hover {
            color: #d8f3fd;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75), 0 0 5px rgba(198, 234, 247, 0.4);
        }

        p {
            margin-bottom: 0.3em;
        }

        .center,
        img.center {
            text-align: center;
            clear: both;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        #coming-soon {
            background: rgba(0, 0, 0, 0.0);
            display: block;
            width: 800px;
            margin: 140px auto;
        }

        #coming-soon h1 {
            text-align: center;
            font-size: 35px;
        }

        #coming-soon img {
            clear: both;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
        }

        #coming-soon p {
            text-align: center;
        }</style>
    <meta name="keywords"
          content=""/>
    <meta name="description"
          content=""/>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="main-block">
    <div id="coming-soon">
        <h1>Сайт <?= $NAME_SITE ?> закрыт на реконструкция</h1>
        <p>Сайт находится на техническом обслуживании</p>
        <p>обращаться по телефону <?= $PHONE ?></p>
    </div>
</div>
</body>
</html>