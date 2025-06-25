<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<section class="section">
    <div class="currency-exchange">
        <div class="currency-exchange__content">
            <div class="currency-exchange__heading">
                <h2 class="ui-h2 currency-exchange__ui-h2">
                    <?= GetMessage("CURRENCY_EXCHANGE_RATES"); ?>
                </h2>
                <div class="currency-exchange__date">
                    <input type="date" id="currency-date" value="<?= date('Y-m-d', strtotime($arResult['DATE'])) ?>">
                </div>
            </div>

            <div class="currency-exchange__table" id="currency-table">
                <div class="currency-exchange__line">
                    <div class="ui-p4">
                        <?= GetMessage('CURRENCY'); ?>
                    </div>
                    <div class="ui-p4">
                        <?= GetMessage('EXCHANGE_RATE'); ?>
                    </div>
                </div>

                <? if (!empty($arResult["RATES"])): ?>
                    <? foreach ($arResult['RATES'] as $code => $value): ?>
                        <div class="currency-exchange__line">
                            <div class="ui-p1">
                                <?= $code ?>
                                <span>/RUB</span>
                            </div>
                            <div class="ui-p1">
                                <?= $value ?>
                            </div>
                        </div>
                    <? endforeach; ?>
                <? endif; ?>

                <? if (empty($arResult['RATES'])): ?>
                    <div class="currency-exchange__message">
                        <div class="ui-p1">
                            <?= GetMessage("DATA_NOT_FOUND") ?>
                        </div>
                    </div>
                <? endif; ?>

                <? if (!empty($arResult['ERROR'])): ?>
                    <div class="currency-exchange__error">
                        <div class="alert alert-danger">
                            <?= $arResult['ERROR'] ?>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        </div>

        <div class="currency-exchange__image">
            <img src="<?= SITE_TEMPLATE_PATH ?>/img/main/currency-exchange_img1.png" alt="<?= GetMessage("CURRENCY_EXCHANGE_RATES"); ?>"/>
        </div>
    </div>
</section>

<?php
$jsMessages = [
    'CURRENCY' => GetMessage('CURRENCY'),
    'EXCHANGE_RATE' => GetMessage('EXCHANGE_RATE'),
    'DATA_NOT_FOUND' => GetMessage('DATA_NOT_FOUND'),
    'LOADING' => GetMessage("LOADING"),
    'DATA_ERROR' => GetMessage("DATA_ERROR")
];
?>

<script>
    window.CurrencyLang = <?= CUtil::PhpToJSObject($jsMessages) ?>;
</script>
