<?php

require_once __DIR__ . '/../include/bittorrent.php';
require_once INCL_DIR . 'function_users.php';
check_user_status();
global $CURUSER, $site_config, $fluent, $user_stuffs;

$set = [
    'override_class' => 255,
];
$user_stuffs->update($set, $CURUSER['id']);
$fluent->deleteFrom('ajax_chat_online')
       ->where('userID = ?', $CURUSER['id'])
       ->execute();

header("Location: {$site_config['paths']['baseurl']}/index.php");
die();
