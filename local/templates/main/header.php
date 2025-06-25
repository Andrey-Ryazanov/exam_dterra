<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID;?>">
<head>
    <meta charset="utf-8">
    <title><?$APPLICATION->ShowTitle()?></title>
    <?require $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH.'/include/template/assets.php';?>
    <?$APPLICATION->ShowHead();?>
</head>
<body>
    <?$APPLICATION->ShowPanel();?>