<?php

require_once INCL_DIR . 'user_functions.php';
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'bbcode_functions.php';
require_once CLASS_DIR . 'class_check.php';
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
global $CURUSER, $lang, $site_config, $cache;

$lang = array_merge($lang, load_language('ad_grouppm'));

$stdhead = [
    'css' => [
    ],
];

$HTMLOUT      = '';
$err          = [];
$FSCLASS      = UC_STAFF; //== First staff class;
$LSCLASS      = UC_MAX; //== Last staff class;
$FUCLASS      = UC_MIN; //== First users class;
$LUCLASS      = UC_STAFF - 1; //== Last users class;
$sent2classes = [];
/**
 * @param $min
 * @param $max
 */
function classes2name($min, $max)
{
    global $sent2classes;
    for ($i = $min; $i < $max + 1; ++$i) {
        $sent2classes[] = get_user_class_name($i);
    }
}

/**
 * @param $x
 *
 * @return int
 */
function mkint($x)
{
    return (int) $x;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groups = isset($_POST['groups']) ? $_POST['groups'] : '';
    //$groups = isset($_POST["groups"]) ? array_map('mkint',$_POST["groups"]) : ""; //no need for this kind of check because every value its checked inside the switch also the array contains no integer values so that will be a problem
    $subject = isset($_POST['subject']) ? htmlsafechars($_POST['subject']) : '';
    $msg     = isset($_POST['body']) ? htmlsafechars($_POST['body']) : '';
    $msg     = str_replace('&amp', '&', $_POST['body']);
    $sender = isset($_POST['system']) && $_POST['system'] === 'yes' ? 0 : $CURUSER['id'];
    if (empty($subject)) {
        $err[] = $lang['grouppm_nosub'];
    }
    if (empty($msg)) {
        $err[] = $lang['grouppm_nomsg'];
    }
    //$msg .= "\n This is a group message !";
    if (empty($groups)) {
        $err[] = $lang['grouppm_nogrp'];
    }
    if (count($err) == 0) {
        $where = $classes = $ids = [];
        foreach ($groups as $group) {
            if (is_string($group)) {
                switch ($group) {
                    case 'all_staff':
                        $where[] = 'u.class BETWEEN ' . $FSCLASS . ' and ' . $LSCLASS;
                        classes2name($FSCLASS, $LSCLASS);
                        break;

                    case 'all_users':
                        $where[] = 'u.class BETWEEN ' . $FUCLASS . ' and ' . $LSCLASS;
                        classes2name($FUCLASS, $LSCLASS);
                        break;

                    case 'fls':
                        $where[]        = "u.support='yes'";
                        $sent2classes[] = '' . $lang['grouppm_fls'] . '';
                        break;

                    case 'donor':
                        $where[]        = "u.donor = 'yes'";
                        $sent2classes[] = '' . $lang['grouppm_donor'] . '';
                        break;

                    case 'all_friends':
                        $fq = sql_query('SELECT friendid FROM friends WHERE userid=' . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
                        if (mysqli_num_rows($fq)) {
                            while ($fa = mysqli_fetch_row($fq)) {
                                $ids[] = $fa[0];
                            }
                        }
                        break;
                }
            }
            if (is_numeric($group + 0) && $group + 0 > 0) {
                $classes[]      = $group;
                $sent2classes[] = get_user_class_name($group);
            }
        }
        if (count($classes) > 0) {
            $where[] = 'u.class IN (' . join(',', $classes) . ')';
        }
        if (count($where) > 0) {
            $q1 = sql_query('SELECT u.id FROM users AS u WHERE ' . join(' OR ', $where)) or sqlerr(__FILE__, __LINE__);
            if (mysqli_num_rows($q1) > 0) {
                while ($a = mysqli_fetch_row($q1)) {
                    $ids[] = $a[0];
                }
            }
        }
        $ids = array_unique($ids);
        if (count($ids) > 0) {
            $pms = [];
            $msg .= "\n[p]" . $lang['grouppm_this'] . join(', ', $sent2classes) . '[/p]';
            foreach ($ids as $rid) {
                $pms[] = '(' . $sender . ',' . $rid . ',' . TIME_NOW . ',' . sqlesc($msg) . ',' . sqlesc($subject) . ')';
            }
            if (count($pms) > 0) {
                $r = sql_query('INSERT INTO messages(sender,receiver,added,msg,subject) VALUES ' . join(',', $pms)) or sqlerr(__FILE__, __LINE__);
            }
            foreach ($ids as $rid) {
                $cache->increment('inbox_' . $rid);
            }
            $err[] = ($r ? $lang['grouppm_sent'] : $lang['grouppm_again']);
        } else {
            $err[] = $lang['grouppm_nousers'];
        }
    }
}

$groups          = [];
$groups['staff'] = ['opname'   => $lang['grouppm_staff'],
                    'minclass' => UC_MIN, ];
for ($i = $FSCLASS; $i <= $LSCLASS; ++$i) {
    $groups['staff']['ops'][$i] = get_user_class_name($i);
}
$groups['staff']['ops']['fls']       = $lang['grouppm_fls'];
$groups['staff']['ops']['all_staff'] = $lang['grouppm_allstaff'];
$groups['members']                   = [];
$groups['members']                   = ['opname' => $lang['grouppm_mem'],
                      'minclass'                 => UC_STAFF, ];
for ($i = $FUCLASS; $i <= $LUCLASS; ++$i) {
    $groups['members']['ops'][$i] = get_user_class_name($i);
}
$groups['members']['ops']['donor']     = $lang['grouppm_donor'];
$groups['members']['ops']['all_users'] = $lang['grouppm_allusers'];
$groups['friends']                     = ['opname' => $lang['grouppm_related'],
                      'minclass'                   => UC_MIN,
                      'ops'                        => ['all_friends' => $lang['grouppm_friends']], ];

/**
 * @return string
 */
function dropdown()
{
    global $CURUSER, $groups;

    $r = '<select multiple="multiple" name="groups[]"  size="11" style="padding:5px; width:180px;">';
    foreach ($groups as $group) {
        if ($group['minclass'] >= $CURUSER['class']) {
            continue;
        }
        $r .= '<optgroup label="' . $group['opname'] . '">';
        $ops = $group['ops'];
        foreach ($ops as $k => $v) {
            $r .= '<option value="' . $k . '">' . $v . '</option>';
        }
        $r .= '</optgroup>';
    }
    $r .= '</select>';

    return $r;
}

$HTMLOUT .= begin_main_frame();
if (count($err) > 0) {
    $class = (stristr($err[0], 'sent!') == true ? 'sent' : 'notsent');
    $errs  = '<ul><li>' . join('</li><li>', $err) . '</li></ul>';
    $HTMLOUT .= '<div class="' . $class . "\">$errs</div>";
}
$HTMLOUT .= "<fieldset style='border:1px solid #333333; padding:5px;'>
    <legend style='padding:3px 5px 3px 5px; border:solid 1px #333333; font-size:12px;font-weight:bold;'>{$lang['grouppm_head']}</legend>
    <form action='staffpanel.php?tool=grouppm&amp;action=grouppm' method='post'>
      <table width='500' style='border-collapse: collapse;'>
        <tr>
          <td nowrap='nowrap' colspan='2'><b>{$lang['grouppm_sub']}</b> &#160;&#160;
            <input type='text' name='subject' size='30' style='width:300px;'/></td>
        </tr>
        <tr>
          <td nowrap='nowrap'><b>{$lang['grouppm_body']}</b></td>
          <td nowrap='nowrap'><b>{$lang['grouppm_groups']}</b></td>
          </tr>
        <tr>
          <td width='100%'>" . BBcode() . "</td>
          <td width='100%' >" . dropdown() . "</td>
        </tr>
        <tr>
         <td><label for='sys'>{$lang['grouppm_sendas']}</label><input id='sys' type='checkbox' name='system' value='yes' /></td><td ><input type='submit' value='{$lang['grouppm_send']}' class='button is-small' /></td>
        </tr>
      </table>
    </form>
    </fieldset>";
$HTMLOUT .= end_main_frame();
echo stdhead($lang['grouppm_stdhead'], true, $stdhead) . $HTMLOUT . stdfoot();
