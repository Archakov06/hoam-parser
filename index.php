<?php

require('phpQuery/phpQuery.php');
require('functions.php');

$catUrl = 'http://berkat.ru/board/bytovaja-elektronika/tehnika-dlja-doma';

// Получаем все объявления из категории "Техника для дома"
$links = getLinks($catUrl);

// Преобразуем каждое объявление в JSON
foreach ($links as $el => $url) {
	$ad = parseAd($url);
}

// ... дальше уже интегрировать