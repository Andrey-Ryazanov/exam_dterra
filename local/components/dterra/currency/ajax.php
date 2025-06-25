<?
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Context;
use DTerra\App\Services\Currency;

header('Content-Type: application/json');

function getSelectedDate(): string
{
    $request = Context::getCurrent()->getRequest();
    return $request->getPost('date') ?: date('d.m.Y');
}

try {
    if (!check_bitrix_sessid()) {
        throw new \RuntimeException(GetMessage('CSRF_ERROR'));
    }

    $date = getSelectedDate();
    
    $service = new Currency();
    $rates = $service->getRatesByDate($date);

    echo json_encode([
        'success' => true,
        'date' => $date,
        'rates' => $rates,
    ], JSON_UNESCAPED_UNICODE);
} 
catch (\Throwable $e) 
{
    \CEventLog::Add([
        'SEVERITY' => 'ERROR',
        'AUDIT_TYPE_ID' => 'DTC_CURRENCY_AJAX_ERROR',
        'MODULE_ID' => 'main',
        'ITEM_ID' => 'CurrencyRatesAjax',
        'DESCRIPTION' => 'AJAX: ' . $e->getMessage(),
    ]);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => GetMessage('DATA_ERROR') . ': ' . $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}