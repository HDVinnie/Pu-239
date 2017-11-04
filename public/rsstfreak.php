<?php
require_once realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php';
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'user_functions.php';
check_user_status();
$html = '';
$lang = load_language('global');
$use_limit = true;
$limit = 15;
$html .= "<div class='container'><div class='row-fluid'>";
$xml = file_get_contents('http://feed.torrentfreak.com/Torrentfreak/');
$icount = 1;
$doc = new DOMDocument();
@$doc->loadXML($xml);
$items = $doc->getElementsByTagName('item');
foreach ($items as $item) {
    $html .= "<div class='span12'><center><h2>" . $item->getElementsByTagName('title')->item(0)->nodeValue . '</h2></center><hr>' . preg_replace("/<p>Source\:(.*?)width=\"1\"\/>/is", '', $item->getElementsByTagName('encoded')->item(0)->nodeValue) . '<hr></div>';
    if ($use_limit && $icount == $limit) {
        break;
    }

    ++$icount;
}
$html = str_replace(['“', '”'], '"', $html);
$html = str_replace(['’', '‘', '‘'], "'", $html);
$html = str_replace('–', '-', $html);
$html = str_replace('="/images/', '="http://torrentfreak.com/images/', $html);

$html .= '</div></div>';
echo stdhead('Torrent freak news') . $html . stdfoot();