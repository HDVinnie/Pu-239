<?php

require_once dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php';
require_once INCL_DIR . 'user_functions.php';
check_user_status();
global $session;

$session->set('is-danger', 'Access Not Allowed');
header("Location: {$site_config['baseurl']}/index.php");
die();
