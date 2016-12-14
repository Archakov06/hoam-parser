<?

/**
 * Функция для получения объявления в JSON.
 * @param  string $url - ссылка на объявление.
 * @return array - возвращает JSON
 */
function getAd($url){
	$html = file_get_contents($url);

	$doc = phpQuery::newDocument($html);

	// Получаем заголовок
	$title = explode(' -', $doc->find('head > title')->text())[0];

	// Получаем номер телефона
	$phone = $doc->find('span.board_item_contacts > span')->text();

	// Получаем цену
	$price = $doc->find('div.board_item_price')->text();

	// Получаем описание
	$desc = $doc->find('div.board_item_desc')->text();

	// Получаем тип объявления
	$ad_type = trim($doc->find('div.content_item_props td.value')->text());

	// Удаляем из описания лишнее. В беркате все через одно место и пришлось применять такой способ, чтобы избавиться от мусора.
	$desc = explode('	', trim($desc))[0];

	// Парсим прикрепленные из объявления
	$fotorama = $doc->find('.fotorama a');
	foreach ($fotorama as $el) {
		$q = pq($el);
		$images[] = 'http://berkat.ru'.$q->attr('data-full');
	}

	// Получаем список категорий
	$breadcrumbs = explode('  ',trim($doc->find('#breadcrumbs')->text()));

	$categories = [];

	// Убираем из полученного текста категорий, пустые значения
	// Для этого, проверяем регуляркой на наличие ниже описанных символов
	for($i = 0; $i < count($breadcrumbs); $i++) {
		if (preg_match('/([а-яА-Яa-zA-Z0-9])/i', $breadcrumbs[$i])) $categories[] = $breadcrumbs[$i];
	}

	// Удаляем из массива последний элемент, который содержит заголовок объявления.
	// Ибо он естьв $title
	array_pop($categories);

	$result = array(
		'title' => $title,
		'phone' => $phone,
		'price' => $price,
		'desc' => $desc,
		'ad_type' => $ad_type,
		'images' => $images,
		'categories' => $categories
	);

	return $result;
}

/**
 * Функция для получения списка всех URL объявлений из категории.
 * @param  string $url - ссылка на категорию
 * @return array - возвращается массив ссылок
 */
function getLinks($url){
	$html = file_get_contents($url);

	$doc = phpQuery::newDocument($html);

	// Получаем все объявляения из категории
	$items = $doc->find('.board_list_item_title > a');

	$links = [];

	// Вытаскиваем из каждого элемента ссылку + домен
	foreach ($items as $el) {
		$q = pq($el);
		$links[] = 'http://berkat.ru'.$q->attr('href');
	}

	return $links;
}