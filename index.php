<?php
require('phpQuery/phpQuery.php');

$html = file_get_contents('http://berkat.ru/368965-terristornyi-stabilizator-naprjazhenija.html');

$document = phpQuery::newDocument($html);

$asd = $document->find('head > title');

die($asd);