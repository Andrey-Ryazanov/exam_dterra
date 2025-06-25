<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use DTerra\App\Services\Currency;

class DterraCurrencyComponent extends CBitrixComponent
{
    public function executeComponent(): void
    {
        try {
            $this->prepareResult();
        } 
        catch (\Throwable $e) {
            $this->handleError($e);
        }

        $this->includeComponentTemplate();
    }

    private function prepareResult(): void
    {
        $date = $this->getSelectedDate();
        $service = new Currency();
        
        $this->arResult = [
            'DATE' => $date,
            'RATES' => $service->getRatesByDate($date),
            'ERROR' => null,
        ];
    }

    private function getSelectedDate(): string
    {
        $request = Context::getCurrent()->getRequest();
        return $request->get('date') ?: date('d.m.Y');
    }

    private function handleError(\Throwable $e): void
    {
        $this->logError($e);
        $this->arResult = [
            'RATES' => [],
            'ERROR' => GetMessage('DTC_ERROR_FETCH_RATES'),
            'DATE' => $this->getSelectedDate(),
        ];
    }

    private function logError(\Throwable $e): void
    {
        \CEventLog::Add([
            'SEVERITY' => 'ERROR',
            'AUDIT_TYPE_ID' => 'DTC_CURRENCY_COMPONENT_ERROR',
            'MODULE_ID' => 'main',
            'ITEM_ID' => 'CurrencyRates',
            'DESCRIPTION' => $e->getMessage(),
        ]);
    }
}