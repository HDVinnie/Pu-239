<?php
function docleanup($data)
{
    global $INSTALLER09, $queries, $mc1;
    set_time_limit(0);
    ignore_user_abort(1);
    // *Updated* Invites Achievements Mod by MelvinMeow
    $res = sql_query("SELECT userid, invited, inviterach FROM usersachiev WHERE invited >= 1") or sqlerr(__FILE__, __LINE__);
    $msg_buffer = $usersachiev_buffer = $achievements_buffer = [];
    if (mysqli_num_rows($res) > 0) {
        $dt = TIME_NOW;
        $subject = sqlesc('New Achievement Earned!');
        $points = random_int(1, 3);
        while ($arr = mysqli_fetch_assoc($res)) {
            $invited = (int)$arr['invited'];
            $lvl = (int)$arr['inviterach'];
            if ($invited >= 1 && $lvl == 0) {
                $msg = sqlesc('Congratulations, you have just earned the [b]Inviter Level 1[/b] achievement. :) [img]' . $INSTALLER09['baseurl'] . '/pic/achievements/invite1.png[/img]');
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'Inviter LVL1\', \'invite1.png\' , \'Invited at least 1 new user to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',1, ' . $points . ')';
                $mc1->delete_value('inbox_new_' . $arr['userid']);
                $mc1->delete_value('inbox_new_sb_' . $arr['userid']);
                $mc1->delete_value('user_achievement_points_' . $arr['userid']);
                $var1 = 'inviterach';
            }
            if ($invited >= 2 && $lvl == 1) {
                $msg = sqlesc('Congratulations, you have just earned the [b]Inviter Level 2[/b] achievement. :) [img]' . $INSTALLER09['baseurl'] . '/pic/achievements/invite2.png[/img]');
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'Inviter LVL2\', \'invite2.png\' , \'Invited at least 2 new users to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',2, ' . $points . ')';
                $mc1->delete_value('inbox_new_' . $arr['userid']);
                $mc1->delete_value('inbox_new_sb_' . $arr['userid']);
                $var1 = 'inviterach';
            }
            if ($invited >= 3 && $lvl == 2) {
                $msg = sqlesc('Congratulations, you have just earned the [b]Inviter Level 3[/b] achievement. :) [img]' . $INSTALLER09['baseurl'] . '/pic/achievements/invite3.png[/img]');
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'Inviter LVL3\', \'invite3.png\' , \'Invited at least 3 new users to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',3, ' . $points . ')';
                $mc1->delete_value('inbox_new_' . $arr['userid']);
                $mc1->delete_value('inbox_new_sb_' . $arr['userid']);
                $mc1->delete_value('user_achievement_points_' . $arr['userid']);
                $var1 = 'inviterach';
            }
            if ($invited >= 5 && $lvl == 3) {
                $msg = sqlesc('Congratulations, you have just earned the [b]Inviter Level 4[/b] achievement. :) [img]' . $INSTALLER09['baseurl'] . '/pic/achievements/invite4.png[/img]');
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'Inviter LVL4\', \'invite4.png\' , \'Invited at least 5 new users to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',4, ' . $points . ')';
                $mc1->delete_value('inbox_new_' . $arr['userid']);
                $mc1->delete_value('inbox_new_sb_' . $arr['userid']);
                $mc1->delete_value('user_achievement_points_' . $arr['userid']);
                $var1 = 'inviterach';
            }
            if ($invited >= 10 && $lvl == 4) {
                $msg = sqlesc('Congratulations, you have just earned the [b]Inviter Level 5[/b] achievement. :) [img]' . $INSTALLER09['baseurl'] . '/pic/achievements/invite5.png[/img]');
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'Inviter LVL5\', \'invite5.png\' , \'Invited at least 10 new users to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',5, ' . $points . ')';
                $mc1->delete_value('inbox_new_' . $arr['userid']);
                $mc1->delete_value('inbox_new_sb_' . $arr['userid']);
                $mc1->delete_value('user_achievement_points_' . $arr['userid']);
                $var1 = 'inviterach';
            }
        }
        $count = count($achievements_buffer);
        if ($count > 0) {
            sql_query('INSERT INTO messages (sender,receiver,added,msg,subject) VALUES ' . implode(', ', $msgs_buffer)) or sqlerr(__FILE__, __LINE__);
            sql_query('INSERT INTO achievements (userid, date, achievement, icon, description) VALUES ' . implode(', ', $achievements_buffer) . ' ON DUPLICATE key UPDATE date=values(date),achievement=values(achievement),icon=values(icon),description=values(description)') or sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO usersachiev (userid, $var1, achpoints) VALUES " . implode(', ', $usersachiev_buffer) . " ON DUPLICATE key UPDATE $var1=values($var1), achpoints=achpoints+values(achpoints)") or sqlerr(__FILE__, __LINE__);
            if ($queries > 0) {
                write_log("Achievements Cleanup: Achievements Inviter Completed using $queries queries. Inviter Achievements awarded to - " . $count . ' Member(s)');
            }
        }
        unset($usersachiev_buffer, $achievements_buffer, $msgs_buffer, $count);
    }
    if (false !== mysqli_affected_rows($GLOBALS['___mysqli_ston'])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS['___mysqli_ston']) . ' items updated';
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
