<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>

<?php

$baseUrl = 'http://news.kmib.co.kr/article/';
$mainUrl = 'list.asp?sid1=fai&sid2=0001';
$queryUrl = $baseUrl . $mainUrl;

include_once('./simple_html_dom.php');
$html = file_get_html($queryUrl);

echo "<ul data-role=\"listview\" data-theme=\"g\">";

foreach($html->find('dl.nws') as $dl)
{
  $title = $dl->find('dt', 0)->find('a', 0)->innertext;
  $link = $dl->find('dt', 0)->find('a', 0)->href;
  $contents = $dl->find('dd.tx', 0)->find('a', 0)->innertext;
  $date = $dl->find('dd.date', 0)->innertext;

  // convert encoding
  $title = iconv("EUC-KR", "UTF-8", $title);
  $contents = iconv("EUC-KR", "UTF-8", $contents);
  $date = iconv("EUC-KR", "UTF-8", $date);

  $newUrl = $baseUrl . $link;
  echo "<li><a href=\"page.php?newUrl=$newUrl\">" . $title . "</a></li>";  
/*
  echo $title;
  echo "<br>";
  echo $contents;
  echo "<br>";
  echo $date;
  echo "<br>";
  
  $newUrl = $baseUrl . $link;
  echo $newUrl;
  echo "<br>";
  echo "<a href=\"" . $newUrl . "\">" . "Click here" . "</a>";
  echo "<br>";
  echo "<br>";
  */
}

echo "</ul>";

?>

</body>
</html>