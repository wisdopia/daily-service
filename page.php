<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>


<style type="text/css">

html { font-size: 62.5%; }
body { font-size: 1.5em;}

@media (max-width: 300px) {
    html { font-size: 70%; }
}

@media (min-width: 500px) {
    html { font-size: 80%; }
}

@media (min-width: 700px) {
    html { font-size: 120%; }
}

@media (min-width: 1200px) {
    html { font-size: 200%; }
}

</style>
</head>
<body>

<?php
include_once('./simple_html_dom.php');

class PraiseInfo
{
    public $title;
    public $num;
}

class BibleInfo
{
    public $book;
    public $chapter;
    public $beginVerse;
    public $endVerse;
}

function getPraiseInfo(&$header)
{
    $info = new PraiseInfo();

    $pos = strpos($header, "<br>");
    $text = substr($header, 0, $pos);

    //echo $text . "<br>";

    // song title
    $titleStartPos = strpos($text, "‘");
    $titleEndPos  = strpos($text, "’");
    $title = mb_substr ($text, $titleStartPos+3, $titleEndPos - $titleStartPos-3);


    // song number
    $textSize = strlen($text);        
    $startPos = 0;
    $endPos = 0;
    $isCountMode = false;
    for($i = $titleEndPos; $i < $textSize; $i++)
    {
        if( ord($text[$i]) >= ord('0')  &&  ord($text[$i]) <= ord('9') ) {
            if( $isCountMode == false ) {
                $startPos = $i;    
                $isCountMode = true;
            }
            $endPos = $i;
        }
        else
        {
            if( $isCountMode == true )
                break;
        }
    }

    //echo $startPos . " " . $endPos . "<br>";
    $songNum = substr($text, $startPos,  $endPos-$startPos+1);
    $songNum = sprintf("%03d", (int)$songNum);

    $info->title = $title;
    $info->num = $songNum;

    return $info;
}


function getBibleInfo(&$header)
{
    $info = new BibleInfo();
    $text = mb_strstr($header, "본문");

    $pos = mb_strpos($text, ":");
    $textSize = mb_strlen($text); 

    $text = mb_substr($text, $pos+1, $textSize-$pos);

    list($info->book, $info->chapter, $verse) = sscanf($text, "%s %s %s");


    // Remove "장"
    $pos = mb_strpos($info->chapter, "장");
    $textSize = mb_strlen($info->chapter); 
    $info->chapter = mb_substr($info->chapter, 0, $pos);

    // Remove "절"
    $pos = mb_strpos($verse, "절");
    $textSize = mb_strlen($verse); 
    $verse = mb_substr($verse, 0, $pos);

    // split the verse into begin and end part if needed. 
    $strTok =explode("∼", $verse);
    $cnt = count($strTok);

    if(1 == $cnt) {
        $info->beginVerse = $strTok[0];
    }
    else if ( 1 < $cnt) {
        $info->beginVerse = $strTok[0];
        $info->endVerse   = $strTok[1];
    }

    return $info;
/*
    echo $pos . "&nbsp" . $textSize . "<br>";
    echo $info->book . "<br>";
    echo $info->chapter . "<br>";
    echo $info->beginVerse . "<br>";
    echo $info->endVerse . "<br>";

    $textSize = strlen($text); 
*/
}

function getBibleText(&$bibleInfo)
{

    $bibleKey = array();
    $bibleKey["창세기"] = "gen";
    $bibleKey["출애굽기"] = "exo";
    $bibleKey["레위기"] = "lev";
    $bibleKey["민수기"] = "num";
    $bibleKey["신명기"] = "deu";
    $bibleKey["여호수아"] = "jos";
    $bibleKey["사사기"] = "jdg";
    $bibleKey["룻기"] = "rut";
    $bibleKey["사무엘상"] = "1sa";
    $bibleKey["사무엘하"] = "2sa";
    $bibleKey["열왕기상"] = "1ki";
    $bibleKey["열왕기하"] = "2ki";
    $bibleKey["역대상"] = "1ch";
    $bibleKey["역대하"] = "2ch";
    $bibleKey["에스라"] = "ezr";
    $bibleKey["느헤미야"] = "neh";
    $bibleKey["에스더"] = "est";
    $bibleKey["욥기"] = "job";
    $bibleKey["시편"] = "psa";
    $bibleKey["잠언"] = "pro";
    $bibleKey["전도서"] = "ecc";
    $bibleKey["아가"] = "sng";
    $bibleKey["이사야"] = "isa";
    $bibleKey["예레미야"] = "jer";
    $bibleKey["예레미야애가"] = "lam";
    $bibleKey["에스겔"] = "ezk";
    $bibleKey["다니엘"] = "dan";
    $bibleKey["호세아"] = "hos";
    $bibleKey["요엘"] = "jol";
    $bibleKey["아모스"] = "amo";
    $bibleKey["오바댜"] = "oba";
    $bibleKey["요나"] = "jnh";
    $bibleKey["미가"] = "mic";
    $bibleKey["나훔"] = "nam";
    $bibleKey["하박국"] = "hab";
    $bibleKey["스바냐"] = "zep";
    $bibleKey["학개"] = "hag";
    $bibleKey["스가랴"] = "zec";
    $bibleKey["말라기"] = "mal";
    $bibleKey["마태복음"] = "mat";
    $bibleKey["마가복음"] = "mrk";
    $bibleKey["누가복음"] = "luk";
    $bibleKey["요한복음"] = "jhn";
    $bibleKey["사도행전"] = "act";
    $bibleKey["로마서"] = "rom";
    $bibleKey["고린도전서"] = "1co";
    $bibleKey["고린도후서"] = "2co";
    $bibleKey["갈라디아서"] = "gal";
    $bibleKey["에베소서"] = "eph";
    $bibleKey["빌립보서"] = "php";
    $bibleKey["골로새서"] = "col";
    $bibleKey["데살로니가전서"] = "1th";
    $bibleKey["데살로니가후서"] = "2th";
    $bibleKey["디모데전서"] = "1ti";
    $bibleKey["디모데후서"] = "2ti";
    $bibleKey["디도서"] = "tit";
    $bibleKey["빌레몬서"] = "phm";
    $bibleKey["히브리서"] = "heb";
    $bibleKey["야고보서"] = "jas";
    $bibleKey["베드로전서"] = "1pe";
    $bibleKey["베드로후서"] = "2pe";
    $bibleKey["요한1서"] = "1jn";
    $bibleKey["요한2서"] = "2jn";
    $bibleKey["요한3서"] = "3jn";
    $bibleKey["유다서"] = "jud";
    $bibleKey["요한계시록"] = "rev";

    $format = "http://www.bskorea.or.kr/infobank/korSearch/korbibReadpage.aspx?version=GAE&book=%s&chap=%s&sec=1&cVersion=&fontString=12px&fontSize=1";

    $query = sprintf($format, $bibleKey[$bibleInfo->book], $bibleInfo->chapter);

    $html = file_get_html($query);
    $bibleText = $html->find('td[id=tdBible1]', 0)->innertext;
    $bibleText = strip_tags($bibleText, "<BR>");
    
    return $bibleText;
}


$newUrl = $_GET[newUrl];
$html = file_get_html($newUrl);

// remove all comment elements
foreach($html->find('comment') as $e)
    $e->outertext = '';

// get a title
$meta_title = $html->find("meta[name='title']", 0)->content;

// get a header. [praise, bible]
$header = $html->find('div.tx[id=articleBody] b', 0)->innertext;
$header = iconv( "EUC-KR", "UTF-8", $header);

// get the praise info
$songInfo = getPraiseInfo($header);

// get the bible info
$bibleInfo = getBibleInfo($header);

$bibleText = getBibleText($bibleInfo);


$entire = $html->find('div.tx[id=articleBody]', 0)->innertext;
$entire = trim($entire);

$startKeyword = iconv("UTF-8", "EUC-KR", "말씀");
$articlePos = strpos($entire, $startKeyword);

$praiseBibleText = substr($entire, 0, $articlePos);

// Main article
$articleText = substr($entire, $articlePos);
$articleText = iconv( "EUC-KR", "UTF-8", $articleText);

echo "<h2>" . $meta_title . "</h2><br>";
echo $praiseBibleText . "<br>";

echo "<br>";
echo $bibleText . "<br>";
echo "<br><br>";


$songPath = "/songs/" . $songInfo->num . ".JPG";
echo "<img width=100% src=\"" . $songPath . "\"><br>";

echo "<br>";
echo $articleText . "<br>";
?>

</body>
</html>