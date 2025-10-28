<?php
// Файл: stat.php

$csvFile = 'stat.csv';

// --- НОВЫЙ БЛОК ДЛЯ КОРРЕКТНОГО СКАЧИВАНИЯ CSV ---
if (isset($_GET['download'])) {
    if (file_exists($csvFile)) {
        // Устанавливаем заголовки, чтобы браузер предложил скачать файл
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="stat_export_' . date('Y-m-d') . '.csv"');

        // Добавляем BOM (Byte Order Mark), чтобы Excel правильно распознал UTF-8
        echo "\xEF\xBB\xBF";

        // Выводим содержимое файла напрямую в браузер
        readfile($csvFile);
        
        // Завершаем выполнение скрипта
        exit;
    } else {
        die("Ошибка: Файл stat.csv не найден.");
    }
}
// --- КОНЕЦ НОВОГО БЛОКА ---


// --- ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ---
// (Этот блок без изменений)
function parseUserAgent(string $userAgentStr): string {
    if (empty($userAgentStr) || $userAgentStr === 'unknown') return 'Unknown';
    $os = 'Unknown OS';
    if (preg_match('/windows nt 10/i', $userAgentStr)) $os = 'Windows 10/11';
    elseif (preg_match('/macintosh|mac os x/i', $userAgentStr)) $os = 'macOS';
    elseif (preg_match('/linux/i', $userAgentStr)) $os = 'Linux';
    elseif (preg_match('/android/i', $userAgentStr)) $os = 'Android';
    elseif (preg_match('/iphone|ipad|ipod/i', $userAgentStr)) $os = 'iOS';
    $browser = 'Unknown Browser';
    if (preg_match('/firefox/i', $userAgentStr)) $browser = 'Firefox';
    elseif (preg_match('/chrome/i', $userAgentStr) && !preg_match('/edge/i', $userAgentStr)) $browser = 'Chrome';
    elseif (preg_match('/safari/i', $userAgentStr) && !preg_match('/chrome/i', $userAgentStr)) $browser = 'Safari';
    elseif (preg_match('/edge/i', $userAgentStr)) $browser = 'Edge';
    elseif (preg_match('/opera|opios/i', $userAgentStr)) $browser = 'Opera';
    return "$browser on $os";
}

function getFilteredData(string $csvFile, string $dateFrom, string $dateTo, string $searchQuery): array {
    $dataRows = [];
    $header = [];
    if (file_exists($csvFile) && ($handle = fopen($csvFile, 'r')) !== FALSE) {
        $header = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== FALSE) {
            if (empty(array_filter($row))) continue;
            $timestamp = strtotime(substr($row[0], 0, 10));
            $rowAsString = implode(' ', $row);
            if ($dateFrom && $timestamp < strtotime($dateFrom)) continue;
            if ($dateTo && $timestamp > strtotime($dateTo)) continue;
            if ($searchQuery && stripos($rowAsString, $searchQuery) === false) continue;
            $dataRows[] = $row;
        }
        fclose($handle);
    }
    $sortedData = array_reverse($dataRows);
    return [$header, $sortedData];
}

function renderTableRows(array $dataRows, array $header): string {
    $html = '';
    $browserColumnIndex = array_search('Браузер', $header);
    $textColumnIndex = array_search('Текст редиректа', $header);
    $redirectUrlColumnIndex = array_search('Адрес редиректа', $header);

    if (empty($dataRows)) {
        $colspan = !empty($header) ? count($header) : 1;
        return "<tr><td colspan='{$colspan}' class='no-data'>Данные не найдены.</td></tr>";
    }

    foreach ($dataRows as $row) {
        $originalCsvRow = '"' . implode('","', $row) . '"';
        $rowHash = md5($originalCsvRow);
        $html .= "<tr data-hash='{$rowHash}'>";
        foreach ($row as $index => $cell) {
            if ($index === $redirectUrlColumnIndex) continue;
            $cellContent = '';
            $safeCell = htmlspecialchars($cell, ENT_QUOTES, 'UTF-8');
            if ($browserColumnIndex !== false && $index === $browserColumnIndex) {
                $cellContent = parseUserAgent($cell);
            } elseif ($textColumnIndex !== false && $index === $textColumnIndex) {
                $redirectUrl = htmlspecialchars($row[$redirectUrlColumnIndex] ?? '#', ENT_QUOTES, 'UTF-8');
                $cellContent = "<a href='{$redirectUrl}' target='_blank' rel='noopener noreferrer'>{$safeCell}</a>";
            } else {
                $cellContent = $safeCell;
            }
            $html .= "<td><span class='cell-content'>{$cellContent}</span></td>";
        }
        $html .= "<td><a href='?delete_hash={$rowHash}' class='action-delete'>Удалить</a></td>";
        $html .= "</tr>";
    }
    return $html;
}

// --- ОБРАБОТКА AJAX-ЗАПРОСОВ ---
if (isset($_GET['ajax'])) {
    if (isset($_GET['delete_hash'])) {
        header('Content-Type: application/json');
        $hashToDelete = $_GET['delete_hash'];
        if (file_exists($csvFile)) {
            $rows = file($csvFile, FILE_IGNORE_NEW_LINES);
            $newContent = ''; $headerRow = true; $deleted = false;
            foreach ($rows as $row) {
                if (trim($row) === '') continue;
                if ($headerRow) { $newContent .= $row . PHP_EOL; $headerRow = false; continue; }
                if (md5($row) !== $hashToDelete) { $newContent .= $row . PHP_EOL; } else { $deleted = true; }
            }
            if ($deleted) {
                file_put_contents($csvFile, $newContent, LOCK_EX);
                echo json_encode(['status' => 'success']);
            } else { echo json_encode(['status' => 'error', 'message' => 'Row not found.']); }
        } else { echo json_encode(['status' => 'error', 'message' => 'File not found.']); }
        exit();
    }
    
    $dateFrom = $_GET['date_from'] ?? ''; $dateTo = $_GET['date_to'] ?? ''; $searchQuery = $_GET['search_query'] ?? '';
    list($header, $dataRows) = getFilteredData($csvFile, $dateFrom, $dateTo, $searchQuery);
    echo renderTableRows($dataRows, $header);
    exit();
}

// --- НАЧАЛЬНАЯ ЗАГРУЗКА СТРАНИЦЫ ---
list($initialHeader, $initialDataRows) = getFilteredData($csvFile, $_GET['date_from'] ?? '', $_GET['date_to'] ?? '', $_GET['search_query'] ?? '');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика переходов</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 100%; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; }
        .filters { background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 15px; align-items: end; }
        .filters .field { display: flex; flex-direction: column; }
        .filters label { font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .filters input { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .filters a { line-height: 36px; text-decoration: none; color: #555; }
        .download-link { margin-left: auto; }
        .table-wrapper { position: relative; overflow-x: auto; }
        #loading-indicator { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); background: rgba(255,255,255,0.8); padding: 20px; border-radius: 8px; font-weight: bold; z-index: 10; display: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px 15px; text-align: left; border-bottom: 1px solid #ddd; word-break: break-word; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .cell-content { display: block; max-width: 450px; white-space: normal; }
        td a { color: #007bff; text-decoration: none; }
        td a:hover { text-decoration: underline; }
        .action-delete { color: #dc3545; cursor: pointer; white-space: nowrap; }
        .action-delete:hover { color: #a71d2a; }
        .no-data { text-align: center; padding: 20px; font-size: 1.2em; color: #777; }
    </style>
</head>
<body>
<div class="container">
    <h1>Статистика переходов</h1>
    <form class="filters" id="filters-form" method="GET" action="">
        <div class="field">
            <label for="date_from">Дата от:</label>
            <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
        </div>
        <div class="field">
            <label for="date_to">Дата до:</label>
            <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
        </div>
        <div class="field">
            <label for="search_query">Поиск (по всем полям):</label>
            <input type="text" id="search_query" name="search_query" value="<?= htmlspecialchars($_GET['search_query'] ?? '') ?>" placeholder="Город, UTM, IP...">
        </div>
        <a href="stat.php">Сбросить</a>
        <?php if (file_exists($csvFile)): ?>
            <!-- ИЗМЕНЕНИЕ: Ссылка теперь ведет на этот же скрипт с параметром -->
            <a href="?download=1" class="download-link">Скачать CSV</a>
        <?php endif; ?>
    </form>
    
    <div class="table-wrapper">
        <div id="loading-indicator">Загрузка...</div>
        <table>
            <thead>
                <tr>
                    <?php if (!empty($initialHeader)): ?>
                        <?php foreach ($initialHeader as $col): ?>
                            <?php if ($col === 'Адрес редиректа') continue; ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                        <th>Действия</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="stats-table-body">
                <?= renderTableRows($initialDataRows, $initialHeader) ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// JavaScript остается без изменений
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filters-form');
    const tableBody = document.getElementById('stats-table-body');
    const loadingIndicator = document.getElementById('loading-indicator');
    let debounceTimer;

    const fetchTableData = () => {
        loadingIndicator.style.display = 'block';
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        params.append('ajax', '1');
        history.pushState(null, '', '?' + new URLSearchParams(formData).toString());
        fetch(`stat.php?${params.toString()}`).then(response => response.text()).then(html => {
            tableBody.innerHTML = html;
        }).catch(error => console.error('Error fetching data:', error)).finally(() => {
            loadingIndicator.style.display = 'none';
        });
    };

    form.addEventListener('submit', (e) => { e.preventDefault(); fetchTableData(); });
    ['date_from', 'date_to'].forEach(id => { document.getElementById(id).addEventListener('change', fetchTableData); });
    document.getElementById('search_query').addEventListener('input', () => { clearTimeout(debounceTimer); debounceTimer = setTimeout(fetchTableData, 300); });
    tableBody.addEventListener('click', (e) => {
        if (e.target && e.target.classList.contains('action-delete')) {
            e.preventDefault();
            if (!confirm('Вы уверены, что хотите удалить эту запись?')) { return; }
            const link = e.target;
            const row = link.closest('tr');
            loadingIndicator.style.display = 'block';
            fetch(`${link.href}&ajax=1`).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    row.style.transition = 'opacity 0.3s ease-out';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                } else { alert('Не удалось удалить запись: ' + (data.message || 'Неизвестная ошибка')); }
            }).catch(error => console.error('Error deleting row:', error)).finally(() => {
                loadingIndicator.style.display = 'none';
            });
        }
    });
});
</script>
</body>
</html>