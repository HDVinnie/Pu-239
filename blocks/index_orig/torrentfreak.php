<?php

require_once ROOT_DIR . 'tfreak.php';
global $lang;

$tfreak_feed = rsstfreakinfo();
/*
$feed = rsstfreakinfo();
if (!empty($feed)) {
    $tfreak_feed .= "
    <a id='tfreak-hash'></a>
    <fieldset id='tfreak' class='header'>
        <legend class='flipper has-text-primary'><i class='icon-down-open size_2' aria-hidden='true'></i>{$lang['index_torr_freak']}</legend>
        <div>
            $feed
        </div>
    </fieldset>";
}
*/
