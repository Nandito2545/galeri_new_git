<?php
require_once __DIR__ . '/midtrans/Midtrans/Config.php';
require_once __DIR__ . '/midtrans/Midtrans/ApiRequestor.php';

class Snap {
    public static function getSnapToken($params) {
        $baseUrl = \Midtrans\Config::$isProduction
            ? "https://app.midtrans.com/snap/v1/transactions"
            : "https://app.sandbox.midtrans.com/snap/v1/transactions";

        $result = \Midtrans\ApiRequestor::request('POST', $baseUrl, $params);
        return $result->token ?? null;
    }
}