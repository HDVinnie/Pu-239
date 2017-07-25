<?php
/**
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
function docleanup($data)
{
    global $INSTALLER09, $queries;
    set_time_limit(0);
    ignore_user_abort(1);
    $dt = TIME_NOW;
    $subject = sqlesc('New Achievement Earned!');
    $points = rand(1, 3);
    //Reset the daily shoutbox limits
    sql_query("UPDATE `usersachiev` SET `dailyshouts` = '0'") or sqlerr(__FILE__, __LINE__);
    if ($queries > 0) {
        write_log("Achievements Cleanup:  Achievements dailyshouts reset Completed using $queries queries");
    }
    if (false !== mysqli_affected_rows($GLOBALS['___mysqli_ston'])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS['___mysqli_ston']).' items updated';
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
function cleanup_log($data)
{
    $text = sqlesc($data['clean_title']);
    $added = TIME_NOW;
    $ip = sqlesc($_SERVER['REMOTE_ADDR']);
    $desc = sqlesc($data['clean_desc']);
    sql_query("INSERT INTO cleanup_log (clog_event, clog_time, clog_ip, clog_desc) VALUES ($text, $added, $ip, {$desc})") or sqlerr(__FILE__, __LINE__);
}
