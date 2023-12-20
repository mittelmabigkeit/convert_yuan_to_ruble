<?
function get_content()
{
	// Формируем ссылку
	$link = "http://www.cbr.ru/scripts/XML_daily.asp";
	// Загружаем HTML-страницу
	$fd = fopen($link, "r");
	$text="";
	if (!$fd) echo "Запрашиваемая страница не найдена";
	else
	{
		// Чтение содержимого файла в переменную $text
		while (!feof ($fd)) $text .= fgets($fd, 4096);
	}
	// Закрыть открытый файловый дескриптор
	fclose ($fd);
	return $text;
}

function convertYuanToRuble($price)
{
	// Получаем текущие курсы валют в rss-формате с сайта www.cbr.ru
	$content = get_content();
	// Разбираем содержимое, при помощи регулярных выражений
	$pattern = "#<Valute ID=\"([^\"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i";
	preg_match_all($pattern, $content, $out, PREG_SET_ORDER);
	$yuan = "";
	foreach($out as $cur)
	{
		if($cur[2] == 156) $yuan = str_replace(",",".",$cur[4]);
	}
	$noFormatedPrice = str_replace(" ", '', $price);
	$convertedPrice = $noFormatedPrice * $yuan;
	$FormatedPrice = number_format($convertedPrice, 0, '.', ' ');

	return $FormatedPrice;
}
?>