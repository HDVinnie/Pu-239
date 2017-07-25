<?php
/**
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
//By Froggaard
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php';
require_once INCL_DIR.'user_functions.php';
dbconn();
$lang = array_merge(load_language('global'), load_language('ajax_thumbsup'));
$HTML = '';
$id = (int) $_REQUEST['id'];
$wtf = mysqli_num_rows(sql_query('SELECT id, type, torrentid, userid FROM thumbsup WHERE torrentid = '.sqlesc($id)));
$res = sql_query('SELECT id, type, torrentid, userid FROM thumbsup WHERE userid = '.sqlesc($CURUSER['id']).' AND torrentid = '.sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$thumbsup = mysqli_num_rows($res);
if ($thumbsup == 0) {
    sql_query('INSERT INTO thumbsup (userid, torrentid) VALUES ('.sqlesc($CURUSER['id']).', '.sqlesc($id).')') or sqlerr(__FILE__, __LINE__);
    $mc1->delete_value('thumbs_up_'.$id);
    $HTML .= "<img src='{$INSTALLER09['pic_base_url']}thumb_up.png' alt='{$lang['ajaxthumbs_up']}' title='{$lang['ajaxthumbs_up']}' width='12' height='12' /> (".($wtf + 1).')';
} else {
    $HTML .= "<img src='{$INSTALLER09['pic_base_url']}thumb_up.png' alt='{$lang['ajaxthumbs_up']}' title='{$lang['ajaxthumbs_up']}' width='12' height='12' /> ({$wtf})";
}
echo $HTML;
