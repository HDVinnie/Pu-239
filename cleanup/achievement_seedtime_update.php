<?php

/**
 * @param $data
 *
 * @throws Exception
 */
function achievement_seedtime_update($data)
{
    $time_start = microtime(true);
    dbconn();
    global $site_config, $queries, $cache, $message_stuffs;

    set_time_limit(1200);
    ignore_user_abort(true);

    $seedtime = 604800; // 7days
    $seedtime2 = 1209600; // 14days
    $seedtime3 = 1814400; // 21days
    $seedtime4 = 2419200; // 28days
    $seedtime5 = 3888000; // 45days
    $seedtime6 = 5184000; // 60days
    $seedtime7 = 7776000; // 90days
    $seedtime8 = 10368000; // 120days
    $seedtime9 = 12960000; // 200days
    $seedtime10 = 31536000; //1year
    $res = sql_query('SELECT s.userid, s.seedtime, a.dayseed
                    FROM snatched AS s
                    LEFT JOIN usersachiev AS a ON s.userid = a.userid
                    WHERE seedtime >= ' . sqlesc($seedtime) . '
                    GROUP BY userid, seedtime, dayseed
                    ORDER BY seedtime DESC') or sqlerr(__FILE__, __LINE__);
    $msgs_buffer = $usersachiev_buffer = $achievements_buffer = [];
    if (mysqli_num_rows($res) > 0) {
        $dt = TIME_NOW;
        $subject = 'New Achievement Earned!';
        $points = random_int(1, 3);
        $var1 = 'dayseed';
        while ($arr = mysqli_fetch_assoc($res)) {
            $timeseeded = (int) $arr['seedtime'];
            $dayseed = (int) $arr['dayseed'];
            $arr['userid'] = (int) $arr['userid'];
            if ($dayseed === 0 && $timeseeded >= $seedtime) {
                $msg = 'Congratulations, you have just earned the [b]7 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/7dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'7 Day Seeder\', \'7dayseed.png\' , \'Seeded a snatched torrent for a total of at least 7 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',7, ' . $points . ')';
            } elseif ($dayseed === 7 && $timeseeded >= $seedtime2) {
                $msg = 'Congratulations, you have just earned the [b]14 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/14dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'14 Day Seeder\', \'14dayseed.png\' , \'Seeded a snatched torrent for a total of at least 14 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',14, ' . $points . ')';
            } elseif ($dayseed === 14 && $timeseeded >= $seedtime3) {
                $msg = 'Congratulations, you have just earned the [b]21 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/21dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'21 Day Seeder\', \'21dayseed.png\' , \'Seeded a snatched torrent for a total of at least 21 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',21, ' . $points . ')';
            } elseif ($dayseed === 21 && $timeseeded >= $seedtime4) {
                $msg = 'Congratulations, you have just earned the [b]28 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/28dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'28 Day Seeder\', \'28dayseed.png\' , \'Seeded a snatched torrent for a total of at least 28 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',28, ' . $points . ')';
            } elseif ($dayseed === 28 && $timeseeded >= $seedtime5) {
                $msg = 'Congratulations, you have just earned the [b]45 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/45dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'45 Day Seeder\', \'45dayseed.png\' , \'Seeded a snatched torrent for a total of at least 45 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',45, ' . $points . ')';
            } elseif ($dayseed === 45 && $timeseeded >= $seedtime6) {
                $msg = 'Congratulations, you have just earned the [b]60 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/60dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'60 Day Seeder\', \'60dayseed.png\' , \'Seeded a snatched torrent for a total of at least 60 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',60, ' . $points . ')';
            } elseif ($dayseed === 60 && $timeseeded >= $seedtime7) {
                $msg = 'Congratulations, you have just earned the [b]90 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/90dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'90 Day Seeder\', \'90dayseed.png\' , \'Seeded a snatched torrent for a total of at least 90 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',90, ' . $points . ')';
            } elseif ($dayseed === 90 && $timeseeded >= $seedtime8) {
                $msg = 'Congratulations, you have just earned the [b]120 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/120dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'120 Day Seeder\', \'120dayseed.png\' , \'Seeded a snatched torrent for a total of at least 120 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',120, ' . $points . ')';
            } elseif ($dayseed === 120 && $timeseeded >= $seedtime9) {
                $msg = 'Congratulations, you have just earned the [b]200 Day Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/200dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'200 Day Seeder\', \'200dayseed.png\' , \'Seeded a snatched torrent for a total of at least 200 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',200, ' . $points . ')';
            } elseif ($dayseed === 200 && $timeseeded >= $seedtime10) {
                $msg = 'Congratulations, you have just earned the [b]1 Year Seeder[/b] achievement. :) [img]' . $site_config['pic_baseurl'] . 'achievements/365dayseed.png[/img]';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . $dt . ', \'365 Day Seeder\', \'365dayseed.png\' , \'Seeded a snatched torrent for a total of at least 1 Year.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',365, ' . $points . ')';
            }
            if (!empty($msg)) {
                $msgs_buffer[] = [
                    'sender' => 0,
                    'receiver' => $arr['userid'],
                    'added' => $dt,
                    'msg' => $msg,
                    'subject' => $subject,
                ];
                $cache->delete('user_achievement_points_' . $arr['userid']);
            }
        }
        $count = count($achievements_buffer);
        if ($count > 0) {
            $message_stuffs->insert($msgs_buffer);
            sql_query('INSERT INTO achievements (userid, date, achievement, icon, description) VALUES ' . implode(', ', $achievements_buffer) . ' ON DUPLICATE KEY UPDATE date = VALUES(date),achievement = VALUES(achievement),icon = VALUES(icon),description = VALUES(description)') or sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO usersachiev (userid, $var1, achpoints) VALUES " . implode(', ', $usersachiev_buffer) . " ON DUPLICATE KEY UPDATE $var1 = VALUES($var1), achpoints=achpoints + VALUES(achpoints)") or sqlerr(__FILE__, __LINE__);
        }
        $time_end = microtime(true);
        $run_time = $time_end - $time_start;
        $text = " Run time: $run_time seconds";
        echo $text . "\n";
        if ($data['clean_log'] && $queries > 0) {
            write_log("Achievements Cleanup: Seedtime Completed using $queries queries. Seedtime Achievements awarded to - " . $count . ' Member(s).' . $text);
        }
        unset($usersachiev_buffer, $achievements_buffer, $msgs_buffer, $count);
    }
}
