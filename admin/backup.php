<?php

require_once INCL_DIR . 'function_users.php';
require_once INCL_DIR . 'function_html.php';
require_once CLASS_DIR . 'class_check.php';
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
global $CURUSER, $site_config, $lang, $session, $fluent;

$lang = array_merge($lang, load_language('ad_backup'));
if (!in_array($CURUSER['id'], $staff_settings['is_staff'])) {
    stderr($lang['backup_stderr'], $lang['backup_stderr1']);
}

$lang = array_merge($lang, load_language('ad_backup'));
$dt = TIME_NOW;
$HTMLOUT = '';
$required_class = UC_MAX;

if (is_array($required_class)) {
    if (!in_array($CURUSER['class'], $required_class)) {
        stderr($lang['backup_stderr'], $lang['backup_stderr']);
    }
} else {
    if ($required_class != $CURUSER['class']) {
        stderr($lang['backup_stderr'], $lang['backup_stderr1']);
    }
}
$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

if (empty($mode)) {
    $backups = $fluent->from('dbbackup')
                      ->orderBy('added DESC')
                      ->fetchAll();

    if ($backups) {
        $HTMLOUT .= "
            <form method='post' action='{$site_config['paths']['baseurl']}/staffpanel.php?tool=backup&amp;mode=delete' accept-charset='utf-8'>
                <input type='hidden' name='action' value='delete'>
                {$lang['backup_welcome']}
                <table id='checkbox_container' class='table table-bordered table-striped top20 bottom20'>
                    <thead>
                        <tr>
                            <th>{$lang['backup_name']}</th>
                            <th>{$lang['backup_addedon']}</th>
                            <th>{$lang['backup_addedby']}</th>
                            <th><input type='checkbox' id='checkThemAll' class='tooltipper' title='{$lang['backup_markall']}'></th>
                        </tr>
                    </thead>
                    <tbody>";
        foreach ($backups as $arr) {
            $HTMLOUT .= "
                        <tr>
                            <td><a href='{$site_config['paths']['baseurl']}/staffpanel.php?tool=backup&amp;mode=download&amp;id=" . $arr['id'] . "'>" . htmlsafechars($arr['name']) . '</a></td>
                            <td>' . get_date($arr['added'], 'LONG', 1, 0) . '</td>
                            <td>';
            if (!empty($arr['userid'])) {
                $HTMLOUT .= format_username($arr['userid']);
            } else {
                $HTMLOUT .= '
                                unknown[' . $arr['userid'] . ']';
            }
            $HTMLOUT .= "
                            </td>
                            <td>
                                <input type='checkbox' name='ids[]' class='tooltipper' title='{$lang['backup_mark']}' value='" . $arr['id'] . "'>
                            </td>
                        </tr>";
        }

        $HTMLOUT .= "
                    </tbody>
                </table>
                <div class='has-text-centered top20 bottom20 level-center flex-center'>
                    <a class='button is-small' href='{$site_config['paths']['baseurl']}/staffpanel.php?tool=backup&amp;mode=backup'>{$lang['backup_dbbackup']}</a>
                    <input type='submit' class='button is-small' value='{$lang['backup_delselected']}' onclick=\"return confirm('{$lang['backup_confirm']}');\">
                </div>
            </form>
            <div class='has-text-centered'>
                <div class='flipper has-text-primary pointer bottom10'>
                    <i class='icon-up-open size_3 has-text-primary' aria-hidden='true'></i>
                    <span class='has-text-primary size_4 left10'>{$lang['backup_settingschk']}</span>
                </div>
                <div class='portlet is_hidden'>
                    <table class='table table-bordered table-striped'>
                        <tbody>
                            <tr>
                                <td>{$lang['backup_gzip']}</td>
                                <td>{$lang['backup_optional']}</td>
                                <td class='rowhead'>" . ($site_config['backup']['use_gzip'] ? "<div class='has-text-centered has-text-centered has-text-green'>{$lang['backup_yes']}</div>" : "<div class='has-text-centered has-text-danger'>{$lang['backup_no']}</div>") . "</td>
                            </tr>
                            <tr>
                                <td>{$lang['backup_gzippath']}</td>
                                <td>{$site_config['backup']['gzip_path']}</td>
                                <td>" . (is_file($site_config['backup']['gzip_path']) ? "<div class='has-text-centered has-text-green'>{$lang['backup_yes']}</div>" : "<div class='has-text-centered has-text-danger'>{$lang['backup_no']}</div>") . "</td>
                            </tr>
                            <tr>
                                <td>{$lang['backup_pathfolder']}</td>
                                <td>" . BACKUPS_DIR . '</td>
                                <td>' . (is_dir(BACKUPS_DIR) ? "<div class='has-text-centered has-text-green'>{$lang['backup_yes']}</div>" : "<div class='has-text-centered has-text-danger'>{$lang['backup_no']}</div>") . "</td>
                            </tr>
                            <tr>
                                <td colspan='2'>{$lang['backup_readfolder']}</td>
                                <td>" . (is_readable(BACKUPS_DIR) ? "<div class='has-text-centered has-text-green'>{$lang['backup_yes']}</div>" : "<div class='has-text-centered has-text-danger'>{$lang['backup_no']}</div>") . "</td>
                            </tr>
                            <tr>
                                <td colspan='2'>{$lang['backup_writable']}</td>
                                <td>" . (is_writable(BACKUPS_DIR) ? "<div class='has-text-centered has-text-green'>{$lang['backup_yes']}</div>" : "<div class='has-text-centered has-text-danger'>{$lang['backup_no']}</div>") . "</td>
                            </tr>
                            <tr>
                                <td>{$lang['backup_mysqldump']}</td>
                                <td>{$site_config['backup']['mysqldump_path']}</td>
                                <td>" . (is_file($site_config['backup']['mysqldump_path']) ? "<div class='has-text-centered has-text-green'>{$lang['backup_yes']}</div>" : "<div class='has-text-centered has-text-danger'>{$lang['backup_no']}</div>") . "</td>
                            </tr>
                            <tr>
                                <td colspan='2'>{$lang['backup_writeact']}</td>
                                <td>" . ($site_config['backup']['write_to_log'] ? "<div class='has-text-centered has-text-green'>{$lang['backup_yes']}</div>" : "<div class='has-text-centered has-text-danger'>{$lang['backup_no']}</div>") . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';
    } else {
        $HTMLOUT .= main_div("
                <div class='padding20 has-text-centered'>
                    {$lang['backup_nofound']}
                    <div class='top20'>
                        <a class='button is-small' href='{$site_config['paths']['baseurl']}/staffpanel.php?tool=backup&amp;mode=backup'>{$lang['backup_dbbackup']}</a>
                    </div>
                </div>");
    }

    if (isset($_GET['backedup'])) {
        $HTMLOUT .= stdmsg($lang['backup_success'], $lang['backup_backedup']);
    } elseif (isset($_GET['deleted'])) {
        $HTMLOUT .= stdmsg($lang['backup_success'], $lang['backup_deleted']);
    } elseif (isset($_GET['noselection'])) {
        $HTMLOUT .= stdmsg($lang['backup_stderr'], $lang['backup_selectb']);
    }
    echo stdhead($lang['backup_stdhead']) . wrapper($HTMLOUT) . stdfoot();
} elseif ($mode === 'backup') {
    global $site_config;

    $host = $site_config['database']['host'];
    $user = $site_config['database']['username'];
    $pass = quotemeta($site_config['database']['password']);
    $db = $site_config['database']['database'];
    $ext = $db . '_' . date('Y.m.d-H.i.s', $dt) . '.sql';
    $filepath = BACKUPS_DIR . $ext;
    if ($site_config['backup']['use_gzip']) {
        exec("{$site_config['backup']['mysqldump_path']} -h $host -u'{$user}' -p'{$pass}' $db | gzip -q9>{$filepath}.gz");
    } else {
        exec("{$site_config['backup']['mysqldump_path']} -h $host -u'{$user}' -p'{$pass}' $db>$filepath");
    }
    $values = [
        'name' => $ext . ($site_config['backup']['use_gzip'] ? '.gz' : ''),
        'added' => $dt,
        'userid' => $CURUSER['id'],
    ];
    $fluent->insertInto('dbbackup')
           ->values($values)
           ->execute();

    if ($site_config['backup']['write_to_log']) {
        write_log($CURUSER['username'] . '(' . get_user_class_name($CURUSER['class']) . ') ' . $lang['backup_successfully'] . '');
    }
    header('Location: staffpanel.php?tool=backup');
    die();
} elseif ($mode === 'delete') {
    $ids = (isset($_POST['ids']) ? $_POST['ids'] : (isset($_GET['id']) ? [
        $_GET['id'],
    ] : []));
    if (!empty($ids)) {
        foreach ($ids as $id) {
            if (!is_valid_id($id)) {
                stderr($lang['backup_stderr'], $lang['backup_id']);
            }
        }
        $files = $fluent->from('dbbackup')
                        ->select(null)
                        ->select('name')
                        ->where('id', $ids)
                        ->fetchAll();

        if ($files) {
            $count = count($files);
            foreach ($files as $arr) {
                $filename = BACKUPS_DIR . $arr['name'];
                if (is_file($filename)) {
                    unlink($filename);
                }
            }
            $fluent->deleteFrom('dbbackup')
                   ->where('id', $ids)
                   ->execute();

            if ($site_config['backup']['write_to_log']) {
                write_log($CURUSER['username'] . '(' . get_user_class_name($CURUSER['class']) . ') ' . $lang['backup_deleted1'] . ' ' . $count . ($count > 1 ? $lang['backup_database_plural'] : $lang['backup_database_singular']) . '.');
            }
            $location = 'backup';
        } else {
            $location = 'noselection';
        }
    } else {
        $location = 'noselection';
    }
    header('Location: staffpanel.php?tool=backup&mode=' . $location);
    die();
} else {
    stderr($lang['backup_srry'], $lang['backup_unknow']);
}
