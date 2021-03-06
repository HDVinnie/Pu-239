<?php

global $lang, $post_stuff, $CURUSER;

$page = $colour = $arr_quote = $extension_error = $size_error = '';
$topic_id = (isset($_GET['topic_id']) ? intval($_GET['topic_id']) : (isset($_POST['topic_id']) ? intval($_POST['topic_id']) : 0));
if (!is_valid_id($topic_id)) {
    stderr($lang['gl_error'], $lang['gl_bad_id']);
}
$res = sql_query('SELECT t.topic_name, t.locked, f.min_class_read, f.min_class_write, f.id AS real_forum_id, s.id AS subscribed_id FROM topics AS t LEFT JOIN forums AS f ON t.forum_id = f.id LEFT JOIN subscriptions AS s ON s.topic_id = t.id WHERE ' . ($CURUSER['class'] < UC_STAFF ? 't.status = \'ok\' AND' : ($CURUSER['class'] < $min_delete_view_class ? 't.status != \'deleted\'  AND' : '')) . ' t.id=' . sqlesc($topic_id)) or sqlerr(__FILE__, __LINE__);
$arr = mysqli_fetch_assoc($res);
//=== stop them, they shouldn't be here lol
if ($arr['locked'] === 'yes') {
    stderr($lang['gl_error'], $lang['fe_this_topic_is_locked']);
}
if ($CURUSER['class'] < $arr['min_class_read'] || $CURUSER['class'] < $arr['min_class_write']) {
    stderr($lang['gl_error'], $lang['gl_bad_id']);
}
if ($CURUSER['forum_post'] === 'no' || $CURUSER['suspended'] === 'yes') {
    stderr($lang['gl_error'], $lang['fe_your_no_post_right']);
}
$quote = (isset($_GET['quote_post']) ? intval($_GET['quote_post']) : 0);
$key = (isset($_GET['key']) ? intval($_GET['key']) : 0);
$body = (isset($_POST['body']) ? $_POST['body'] : '');
$post_title = strip_tags((isset($_POST['post_title']) ? $_POST['post_title'] : ''));
$icon = htmlsafechars((isset($_POST['icon']) ? $_POST['icon'] : ''));
$bb_code = !isset($_POST['bb_code']) || $_POST['bb_code'] === 'yes' ? 'yes' : 'no';
$subscribe = ((isset($_POST['subscribe']) && $_POST['subscribe'] === 'yes') ? 'yes' : ((!isset($_POST['subscribe']) && $arr['subscribed_id'] > 0) ? 'yes' : 'no'));
$topic_name = htmlsafechars($arr['topic_name']);
$anonymous = (isset($_POST['anonymous']) && '' != $_POST['anonymous'] ? 'yes' : 'no');
//== if it's a quote
if ($quote !== 0 && $body === '') {
    $res_quote = sql_query('SELECT p.body, p.staff_lock, p.anonymous, p.user_id, u.username FROM posts AS p LEFT JOIN users AS u ON p.user_id = u.id WHERE p.id=' . sqlesc($quote)) or sqlerr(__FILE__, __LINE__);
    $arr_quote = mysqli_fetch_array($res_quote);
    //=== if member exists, then add username, and then link back to post that was quoted with date :-D
    //==Anonymous
    if ($arr_quote['anonymous'] === 'yes') {
        $quoted_member = ('' == $arr_quote['username'] ? '' . $lang['pr_lost_member'] . '' : '' . get_anonymous_name() . '');
    } else {
        $quoted_member = ('' == $arr_quote['username'] ? '' . $lang['pr_lost_member'] . '' : htmlsafechars($arr_quote['username']));
    }
    //==
    $body = '[quote=' . $quoted_member . ($quote > 0 ? ' | post=' . $quote : '') . ($key > 0 ? ' | key=' . $key : '') . ']' . htmlsafechars($arr_quote['body']) . '[/quote]';
    if ($arr_quote['staff_lock'] != 0) {
        stderr($lang['gl_error'], '' . $lang['pr_this_post_is_staff_locked_nomod_nodel'] . '');
    }
}
if (isset($_POST['button']) && $_POST['button'] === 'Post') {
    if ($body === '') {
        stderr($lang['gl_error'], $lang['fe_no_body_txt']);
    }
    $ip = ($CURUSER['ip'] === '' ? htmlsafechars(getip()) : $CURUSER['ip']);
    $values = [
        'topic_id' => $topic_id,
        'user_id' => $CURUSER['id'],
        'added' => TIME_NOW,
        'body' => $body,
        'icon' => $icon,
        'post_title' => $post_title,
        'bbcode' => $bb_code,
        'ip' => inet_pton($ip),
        'anonymous' => $anonymous,
    ];
    $post_id = $post_stuffs->insert($values);
    clr_forums_cache($arr['real_forum_id']);
    $cache->delete('forum_posts_' . $CURUSER['id']);
    sql_query('UPDATE topics SET last_post = ' . sqlesc($post_id) . ', post_count = post_count + 1 WHERE id=' . sqlesc($topic_id)) or sqlerr(__FILE__, __LINE__);
    sql_query('UPDATE `forums` SET post_count = post_count + 1 WHERE id =' . sqlesc($arr['real_forum_id'])) or sqlerr(__FILE__, __LINE__);
    sql_query('UPDATE usersachiev SET forumposts = forumposts + 1 WHERE userid = ' . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
    if ($site_config['autoshout_on']) {
        $message = $CURUSER['username'] . ' ' . $lang['pr_replied_to_topic'] . " [quote][url={$site_config['baseurl']}/forums.php?action=view_topic&topic_id=$topic_id&page=last#{$post_id}]{$topic_name}[/url][/quote]";
        if (!in_array($arr['real_forum_id'], $site_config['staff_forums'])) {
            autoshout($message);
        }
    }
    if ($site_config['seedbonus_on']) {
        sql_query('UPDATE users SET seedbonus = seedbonus + ' . sqlesc($site_config['bonus_per_post']) . ' WHERE id = ' . sqlesc($CURUSER['id']) . '') or sqlerr(__FILE__, __LINE__);
        $update['seedbonus'] = ($CURUSER['seedbonus'] + $site_config['bonus_per_post']);
        $cache->update_row('userstats_' . $CURUSER['id'], [
            'seedbonus' => $update['seedbonus'],
        ]);
        $cache->update_row('user_stats_' . $CURUSER['id'], [
            'seedbonus' => $update['seedbonus'],
        ]);
    }
    if ($subscribe === 'yes' && $arr['subscribed_id'] < 1) {
        sql_query('INSERT INTO `subscriptions` (`user_id`, `topic_id`) VALUES (' . sqlesc($CURUSER['id']) . ', ' . sqlesc($topic_id) . ')') or sqlerr(__FILE__, __LINE__);
    } elseif ($subscribe === 'no' && $arr['subscribed_id'] > 0) {
        sql_query('DELETE FROM `subscriptions` WHERE `user_id`= ' . sqlesc($CURUSER['id']) . ' AND  `topic_id` = ' . sqlesc($topic_id)) or sqlerr(__FILE__, __LINE__);
    }
    $res_sub = sql_query('SELECT user_id FROM subscriptions WHERE topic_id =' . sqlesc($topic_id)) or sqlerr(__FILE__, __LINE__);
    while ($row = mysqli_fetch_assoc($res_sub)) {
        $res_yes = sql_query('SELECT subscription_pm, username FROM users WHERE id = ' . sqlesc($row['user_id'])) or sqlerr(__FILE__, __LINE__);
        $arr_yes = mysqli_fetch_array($res_yes);
        $msg = '' . $lang['pr_hey_there'] . "!!! \n " . $lang['pr_a_thread_you_subscribed_to'] . ': ' . htmlsafechars($arr['topic_name']) . ' ' . $lang['pr_has_had_a_new_post'] . "!\n click [url={$site_config['baseurl']}/forums.php?action=view_topic&amp;topic_id={$topic_id}&page=last#{$post_id}][b]" . $lang['pr_here'] . '[/b][/url] ' . $lang['pr_to_read_it'] . "!\n\n" . $lang['pr_to_view_your_subscriptions_or_unsubscribe'] . " [url={$site_config['baseurl']}/forums.php?action=subscriptions][b]" . $lang['pr_here'] . "[/b][/url].\n\nCheers.";
        if ($arr_yes['subscription_pm'] === 'yes' && $row['user_id'] != $CURUSER['id']) {
            sql_query("INSERT INTO messages (sender, subject, receiver, added, msg) VALUES(0, '" . $lang['pr_new_post_in_subscribed_thread'] . "!', " . sqlesc($row['user_id']) . ", '" . TIME_NOW . "', " . sqlesc($msg) . ')') or sqlerr(__FILE__, __LINE__);
        }
    }
    //=== stuff for file uploads
    if ($CURUSER['class'] >= $min_upload_class) {
        foreach ($_FILES['attachment']['name'] as $key => $name) {
            if (!empty($name)) {
                $size = intval($_FILES['attachment']['size'][$key]);
                $type = $_FILES['attachment']['type'][$key];
                $extension_error = $size_error = 0;
                $name = str_replace(' ', '_', $name);
                $accepted_file_types = [
                    'application/zip',
                    'application/x-zip',
                    'application/rar',
                    'application/x-rar',
                ];
                $accepted_file_extension = strrpos($name, '.');
                $file_extension = strtolower(substr($name, $accepted_file_extension));
                $name = preg_replace('#[^a-zA-Z0-9_-]#', '', $name); // hell, it could even be 0_0 if it wanted to!
                switch (true) {
                    case $size > $max_file_size:
                        $size_error = ($size_error + 1);
                        break;

                    case !in_array($file_extension, $accepted_file_extension) && false == $accepted_file_extension:
                        $extension_error = ($extension_error + 1);
                        break;

                    case 0 === $accepted_file_extension:
                        $extension_error = ($extension_error + 1);
                        break;

                    case !in_array($type, $accepted_file_types):
                        $extension_error = ($extension_error + 1);
                        break;

                    default:
                        $name = substr($name, 0, -strlen($file_extension));
                        $upload_to = $upload_folder . $name . '(id-' . $post_id . ')' . $file_extension;
                        sql_query('INSERT INTO `attachments` (`post_id`, `user_id`, `file`, `file_name`, `added`, `extension`, `size`) VALUES ( ' . sqlesc($post_id) . ', ' . sqlesc($CURUSER['id']) . ', ' . sqlesc($name . '(id-' . $post_id . ')' . $file_extension) . ', ' . sqlesc($name) . ', ' . TIME_NOW . ', ' . ('.zip' === $file_extension ? '\'zip\'' : '\'rar\'') . ', ' . $size . ')') or sqlerr(__FILE__, __LINE__);
                        copy($_FILES['attachment']['tmp_name'][$key], $upload_to);
                        chmod($upload_to, 0777);
                }
            }
        }
    }
    header('Location: forums.php?action=view_topic&topic_id=' . $topic_id . ($extension_error === '' ? '' : '&ee=' . $extension_error) . ($size_error === '' ? '' : '&se=' . $size_error) . '&page=last#' . $post_id);
    die();
}

$HTMLOUT .= '
    <h1 class="has-text-centered">' . $lang['pr_reply_in_topic'] . ' "<a class="altlink" href="' . $site_config['baseurl'] . '/forums.php?action=view_topic&amp;topic_id=' . $topic_id . '">' . htmlsafechars($arr['topic_name'], ENT_QUOTES) . '</a>"</h1>
    <form method="post" action="' . $site_config['baseurl'] . '/forums.php?action=post_reply&amp;topic_id=' . $topic_id . '" enctype="multipart/form-data">';

require_once FORUM_DIR . 'editor.php';

$HTMLOUT .= '
        <div class="has-text-centered margin20">
            <input type="submit" name="button" class="button is-small" value="' . $lang['fe_post'] . '">
        </div>
    </form>';

require_once FORUM_DIR . 'last_ten.php';
