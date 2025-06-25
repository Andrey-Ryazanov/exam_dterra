<?php
if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

# init core
CJSCore::Init(["jquery3"]);
CUtil::InitJSCore(['ajax']);

# favicon
Asset::getInstance()->addString('<link rel="icon" type="image/png" href="' . SITE_TEMPLATE_PATH . '/favicon.png" />');
Asset::getInstance()->addString('<meta content="' . bitrix_sessid() .'" name="bitrix-sessid-jquery-token" />');


# Css files
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/style.min.css");

# Other files and functions
Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/svg4everybody/2.1.9/svg4everybody.min.js"></script>');
Asset::getInstance()->addString('<script>svg4everybody();</script>');

# Js files
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/scripts.js");