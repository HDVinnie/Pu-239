<?php

global $CURUSER, $site_config, $lang, $cache, $message_stuffs;

if (empty($_POST['pm'])) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    die();
}
$pm_messages = is_array($_POST['pm']) ? $_POST['pm'] : [$_POST['pm']];
if (isset($_POST['move'])) {
    $set = [
        'location' => $_POST['boxx'],
    ];
    foreach ($pm_messages as $pm_message) {
        $message_stuffs->update($set, $pm_message);
    }
    $cache->delete('inbox_' . $CURUSER['id']);
    header('Location: ' . $site_config['baseurl'] . '/messages.php?action=view_mailbox&multi_move=1&box=' . $mailbox);
    die();
}
if (isset($_POST['delete'])) {
    foreach ($pm_messages as $id) {
        $message = $message_stuffs->get_by_id($id);
        if ($message['receiver'] == $CURUSER['id'] && $message['urgent'] === 'yes' && $message['unread'] === 'yes') {
            stderr($lang['pm_error'], '' . $lang['pm_delete_err'] . '<a class="altlink" href="' . $site_config['baseurl'] . '/messages.php?action=view_message&id=' . $pm_id . '">' . $lang['pm_delete_back'] . '</a>' . $lang['pm_delete_msg'] . '');
        }
        if (($message['receiver'] == $CURUSER['id'] || $message['sender'] == $CURUSER['id']) && $message['location'] == PM_DELETED) {
            $result = $message_stuffs->delete($id, $CURUSER['id']);
        } elseif ($message['receiver'] == $CURUSER['id']) {
            $set = [
                'location' => 0,
                'unread' => 'no',
            ];
            $result = $message_stuffs->update($set, $id);
            $cache->decrement('inbox_' . $CURUSER['id']);
        } elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED) {
            $set = [
                'saved' => 'no',
            ];
            $result = $message_stuffs->update($set, $id);
        }
    }

    if (!$result) {
        stderr($lang['pm_error'], $lang['pm_delete_err_multi']);
    }
    if (isset($_POST['returnto'])) {
        header('Location: ' . $site_config['baseurl'] . '/messages.php?action=' . $_POST['returnto'] . '&multi_delete=1');
    } elseif (isset($_POST['draft_section'])) {
        header('Location: ' . $site_config['baseurl'] . '/messages.php?action=viewdrafts&multi_delete=1');
    } else {
        header('Location: ' . $site_config['baseurl'] . '/messages.php?action=view_mailbox&multi_delete=1&box=' . $mailbox);
    }
    die();
}
