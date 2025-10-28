<?php
// Файл: index.php

// --- СЮДА ДОБАВЛЯТЬ НОВЫE ССЫЛКИ ---
// Ключ ('wa', 'promo_block' и т.д.) - это то, что вы пишете в ?f=...
$redirect_targets = [
    'wa' => [
        'type'    => 'whatsapp',
        'phone'   => '79670590816',
        'text'    => 'Добрый день! Получил сообщение в WhatsApp, интересуют плиты.',
    ],
];
// --- БОЛЬШЕ НИЧЕГО НИЖЕ МЕНЯТЬ НЕ НУЖНО ---


/**
 * Определяет геолокацию по IP через API DaData.
 * @param string $ip
 * @return string
 */
function getGeoByIpDadata(string $ip): string
{
    // !!! ВАЖНО: Замените этот ключ на ваш собственный с сайта dadata.ru !!!
    $token = "7c64bad5478644ec171f7eaa01e6146da9580086"; // <-- ВАШ API-КЛЮЧ

    // Игнорируем локальные и неизвестные IP
    if (in_array($ip, ['127.0.0.1', '::1', 'unknown'])) {
        return 'Local';
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=" . $ip);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Accept: application/json",
        "Authorization: Token " . $token,
    ]);

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        // Если есть ошибка cURL (например, нет связи с сервисом), возвращаем N/A
        return 'N/A';
    }

    $data = json_decode($result, true);

    // Если DaData вернул результат, используем его, иначе - N/A
    return $data['location']['value'] ?? 'N/A';
}


/**
 * Записывает информацию о клике в stat.csv
 * @param array $targetConfig
 * @param string $finalUrl
 */
function logClick(array $targetConfig, string $finalUrl): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Данные для записи в CSV
    $logData = [
        'timestamp'    => date('Y-m-d H:i:s'),
        'utm_query'    => $_SERVER['QUERY_STRING'] ?? '',
        'location'     => getGeoByIpDadata($ip),
        'contact'      => $targetConfig['phone'] ?? 'N/A',
        'text'         => $targetConfig['text'] ?? '',
        'redirect_url' => $finalUrl,
        'ip'           => $ip,
        'user_agent'   => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    ];

    $csvFile = __DIR__ . '/stat.csv';
    $csvLine = '"' . implode('","', $logData) . '"' . PHP_EOL;
    
    // Если файла еще нет, добавим заголовки
    if (!file_exists($csvFile)) {
        $headers = [
            "Время клика", "UTM", "Геолокация", "Телефон редиректа", "Текст редиректа",
            "Адрес редиректа", "IP", "Браузер"
        ];
        file_put_contents($csvFile, '"' . implode('","', $headers) . '"' . PHP_EOL);
    }

    file_put_contents($csvFile, $csvLine, FILE_APPEND | LOCK_EX);
}


/**
 * Генерирует URL для редиректа на основе конфигурации.
 * @param array $targetConfig
 * @return string
 */
function buildRedirectUrl(array $targetConfig): string
{
    switch ($targetConfig['type']) {
        case 'whatsapp':
            $encodedText = urlencode($targetConfig['text']);
            return 'https://wa.me/' . $targetConfig['phone'] . '?text=' . $encodedText;
        default:
            return '/';
    }
}

// --- ОСНОВНАЯ ЛОГИКА ---
$redirectKey = $_GET['f'] ?? null;

if ($redirectKey && isset($redirect_targets[$redirectKey])) {
    $currentTarget = $redirect_targets[$redirectKey];
    $finalUrl = buildRedirectUrl($currentTarget);
    logClick($currentTarget, $finalUrl);
    
    header('Location: ' . $finalUrl);
    exit();
} else {
    // Если ?f не указан или не найден, редирект на главную
    header('Location: /');
    exit();
}