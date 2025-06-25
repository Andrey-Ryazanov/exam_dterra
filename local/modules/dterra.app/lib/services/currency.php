<?php
namespace DTerra\App\Services;

use Bitrix\Main\Web\HttpClient;

class Currency
{
    private const SUPPORTED_CURRENCIES = ['USD', 'EUR', 'CNY'];
    private const CBR_API_URL = 'https://www.cbr.ru/scripts/XML_daily.asp';
    private const CBR_DATE_FORMAT = 'd/m/Y';
    
    private HttpClient $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient([
            'socketTimeout' => 10,
            'streamTimeout' => 10,
        ]);
    }

    public function getRatesByDate(string $date): array
    {
        $xml = $this->makeCbrRequest($date);
        return $this->extractRatesFromXml($xml);
    }

    private function makeCbrRequest(string $date): \SimpleXMLElement
    {
        $formattedDate = date(self::CBR_DATE_FORMAT, strtotime($date));
        $url = self::CBR_API_URL . "?date_req={$formattedDate}";

        $response = $this->httpClient->get($url);

        if (!$response || $this->httpClient->getStatus() !== 200) {
            throw new \RuntimeException(
                sprintf('Не удалось получить ставки от ЦБР. Статус: %s', $this->httpClient->getStatus())
            );
        }

        $xml = simplexml_load_string($response);
        if ($xml === false) {
            throw new \RuntimeException('Не удалось спарсить XML из ЦБР.');
        }

        return $xml;
    }

    private function extractRatesFromXml(\SimpleXMLElement $xml): array
    {
        $rates = [];

        foreach ($xml->Valute as $valute) {
            $charCode = (string)$valute->CharCode;
            if (in_array($charCode, self::SUPPORTED_CURRENCIES, true)) {
                $rates[$charCode] = $this->formatRateValue((string)$valute->Value);
            }
        }

        return $rates;
    }

    private function formatRateValue(string $value): float
    {
        return round((float)str_replace(',', '.', $value), 2);
    }
}