<?php

/**
 * @param      $id
 * @param bool $invincible
 * @param bool $bypass_bans
 *
 * @throws \Envms\FluentPDO\Exception
 * @throws \MatthiasMullie\Scrapbook\Exception\UnbegunTransaction
 */
function invincible($id, $invincible = true, $bypass_bans = true)
{
    global $CURUSER, $site_config, $cache, $session, $user_stuffs;

    $ip = '127.0.0.1';
    $setbits = $clrbits = 0;
    if ($invincible) {
        $display = 'now';
        $setbits |= bt_options::PERMS_NO_IP; // don't log IPs
        if ($bypass_bans) {
            $setbits |= bt_options::PERMS_BYPASS_BAN;
        } // bypass ban on
        else {
            $clrbits |= bt_options::PERMS_BYPASS_BAN; // bypass ban off
            $display = 'now bypass bans off and';
        }
    } else {
        $display = 'no longer';
        $clrbits |= bt_options::PERMS_NO_IP; // log IPs
        $clrbits |= bt_options::PERMS_BYPASS_BAN; // bypass ban off
    }
    // update perms
    if ($setbits || $clrbits) {
        sql_query('UPDATE users SET perms = ((perms | ' . $setbits . ') & ~' . $clrbits . ') 
                 WHERE id = ' . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    }
    // grab current data
    $res = sql_query('SELECT username, torrent_pass, INET6_NTOA(ip), perms, modcomment FROM users 
                     WHERE id = ' . sqlesc($id) . ' LIMIT 1') or sqlerr(__FILE__, __LINE__);
    $row = mysqli_fetch_assoc($res);
    $row['perms'] = (int) $row['perms'];
    // delete from iplog current ip
    sql_query('DELETE FROM `ips` WHERE userid = ' . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    // delete any iplog caches
    $cache->delete('ip_history_' . $id);
    $cache->delete('u_passkey_' . $row['torrent_pass']);
    // update ip in db
    $modcomment = get_date(TIME_NOW, '', 1) . ' - ' . $display . ' invincible thanks to ' . $CURUSER['username'] . "\n" . $row['modcomment'];
    $set = [
        'ip' => inet_pton($ip),
        'modcomment' => $modcomment,
        'perms' => $row['perms'],
    ];
    $user_stuffs->update($set, $id);
    write_log('Member [b][url=userdetails.php?id=' . $id . ']' . (htmlsafechars($row['username'])) . '[/url][/b] is ' . $display . ' invincible thanks to [b]' . $CURUSER['username'] . '[/b]');
    // header ouput
    $session->set('is-info', "{$CURUSER['username']} is $display Invincible");
    header('Location: userdetails.php?id=' . $id);
    die();
}
