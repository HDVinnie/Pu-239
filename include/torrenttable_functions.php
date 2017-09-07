<?php
function linkcolor($num)
{
    if (!$num) {
        return 'red';
    }

    return 'pink';
}

function readMore($text, $char, $link)
{
    return strlen($text) > $char ? substr(htmlsafechars($text), 0, $char - 1) . "...<br><a href='$link'>Read more...</a>" : htmlsafechars($text);
}

function torrenttable($res, $variant = 'index')
{
    global $INSTALLER09, $CURUSER, $lang, $free, $mc1;
    require_once INCL_DIR . 'bbcode_functions.php';
    require_once CLASS_DIR . 'class_user_options_2.php';
    $htmlout = $prevdate = $nuked = $free_slot = $freetorrent = $free_color = $slots_check = $double_slot = $private = $newgenre = $oldlink = $char = $description = $type = $sort = $row = $youtube = '';
    $count_get = 0;
    /* ALL FREE/DOUBLE **/
    foreach ($free as $fl) {
        switch ($fl['modifier']) {
            case 1:
                $free_display = '[Free]';
                break;

            case 2:
                $free_display = '[Double]';
                break;

            case 3:
                $free_display = '[Free and Double]';
                break;

            case 4:
                $free_display = '[Silver]';
                break;
        }
        $slot = make_freeslots($CURUSER['id'], 'fllslot_');
        $book = make_bookmarks($CURUSER['id'], 'bookmm_');
        $all_free_tag = ($fl['modifier'] != 0 && ($fl['expires'] > TIME_NOW || $fl['expires'] == 1) ? ' <a class="info" href="#">
            <b>' . $free_display . '</b>
            <span>' . ($fl['expires'] != 1 ? '
            Expires: ' . get_date($fl['expires'], 'DATE') . '<br>
            (' . mkprettytime($fl['expires'] - TIME_NOW) . ' to go)</span></a><br>' : 'Unlimited</span></a><br>') : '');
    }
    $oldlink = [];
    foreach ($_GET as $key => $var) {
        if (in_array($key, [
            'sort',
            'type',
        ])) {
            continue;
        }
        if (is_array($var)) {
            foreach ($var as $s_var) {
                $oldlink[] = sprintf('%s=%s', urlencode($key) . '%5B%5D', urlencode($s_var));
            }
        } else {
            $oldlink[] = sprintf('%s=%s', urlencode($key), urlencode($var));
        }
    }
    $oldlink = !empty($oldlink) ? join('&amp;', array_map('htmlsafechars', $oldlink)) . '&amp;' : '';
    $links = [
        'link1',
        'link2',
        'link3',
        'link4',
        'link5',
        'link6',
        'link7',
        'link8',
        'link9',
    ];
    $i = 1;
    foreach ($links as $link) {
        if (isset($_GET['sort']) && $_GET['sort'] == $i) {
            $$link = (isset($_GET['type']) && $_GET['type'] == 'desc') ? 'asc' : 'desc';
        } else {
            $$link = 'desc';
        }
        ++$i;
    }
    $htmlout .= "
   <table class='text-center' border='1' cellspacing='0' cellpadding='5'>
   <tr>
   <td class='colhead' class='text-center'>{$lang['torrenttable_type']}</td>
   <td class='colhead' align='left'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=1&amp;type={$link1}'>{$lang['torrenttable_name']}</a></td>
   <td class='colhead' align='left'><img src='{$INSTALLER09['pic_base_url']}zip.gif' border='0' alt='Download' title='Download' /></td>";
    $htmlout .= ($variant == 'index' ? "
            <td class='colhead' class='text-center'>
                <a href='{$INSTALLER09['baseurl']}/bookmarks.php'>
                    <i class='fa fa-check fa-browse' title='Go To My Bookmarks'></i>
                </a>
            </td>" : '');
    if ($variant == 'mytorrents') {
        $htmlout .= "<td class='colhead' class='text-center'>{$lang['torrenttable_edit']}</td>\n";
        $htmlout .= "<td class='colhead' class='text-center'>{$lang['torrenttable_visible']}</td>\n";
    }
    $htmlout .= "<td class='colhead' class='text-right'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=2&amp;type={$link2}'>{$lang['torrenttable_files']}</a></td>
   <td class='colhead' class='text-right'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=3&amp;type={$link3}'>{$lang['torrenttable_comments']}</a></td>
   <td class='colhead' class='text-center'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=4&amp;type={$link4}'>{$lang['torrenttable_added']}</a></td>
   <td class='colhead' class='text-center'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=5&amp;type={$link5}'>{$lang['torrenttable_size']}</a></td>
   <td class='colhead' class='text-center'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=6&amp;type={$link6}'>{$lang['torrenttable_snatched']}</a></td>
   <td class='colhead' class='text-right'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=7&amp;type={$link7}'>{$lang['torrenttable_seeders']}</a></td>
   <td class='colhead' class='text-right'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=8&amp;type={$link8}'>{$lang['torrenttable_leechers']}</a></td>";
    if ($variant == 'index') {
        $htmlout .= "<td class='colhead' class='text-center'><a href='{$_SERVER['PHP_SELF']}?{$oldlink}sort=9&amp;type={$link9}'>{$lang['torrenttable_uppedby']}</a></td>\n";
    }
    if ($CURUSER['class'] >= UC_STAFF) {
        $htmlout .= "<td class='colhead' class='text-center'>Tools</td>\n";
    }
    $htmlout .= "</tr>\n";
    $categories = genrelist();
    foreach ($categories as $key => $value) {
        $change[$value['id']] = [
            'id'    => $value['id'],
            'name'  => $value['name'],
            'image' => $value['image'],
        ];
    }
    while ($row = mysqli_fetch_assoc($res)) {
        //==
        if ($CURUSER['opt2'] & user_options_2::SPLIT) {
            if (get_date($row['added'], 'DATE') == $prevdate) {
                $cleandate = '';
            } else {
                $htmlout .= "<tr><td colspan='12' class='colhead' align='left'><b>{$lang['torrenttable_upped']} " . get_date($row['added'], 'DATE') . '</b></td></tr>';
            }
            $prevdate = get_date($row['added'], 'DATE');
        }
        $row['cat_name'] = htmlsafechars($change[$row['category']]['name']);
        $row['cat_pic'] = htmlsafechars($change[$row['category']]['image']);
        /** Freeslot/doubleslot in Use **/
        $id = (int)$row['id'];
        foreach ($slot as $sl) {
            $slots_check = ($sl['torrentid'] == $id && $sl['free'] == 'yes' or $sl['doubleup'] == 'yes');
        }
        if ($row['sticky'] == 'yes') {
            $htmlout .= "<tr class='highlight'>\n";
        } else {
            $htmlout .= '<tr class="' . (($free_color && $all_free_tag != '') || ($row['free'] != 0) || $slots_check ? 'freeleech_color' : 'browse_color') . '">';
        }
        $htmlout .= "<td class='text-center' style='padding: 0px'>";
        if (isset($row['cat_name'])) {
            $htmlout .= "<a href='browse.php?cat=" . (int)$row['category'] . "'>";
            if (isset($row['cat_pic']) && $row['cat_pic'] != '') {
                $htmlout .= "<img border='0' src='{$INSTALLER09['pic_base_url']}caticons/" . get_categorie_icons() . "/{$row['cat_pic']}' alt='{$row['cat_name']}' />";
            } else {
                $htmlout .= htmlsafechars($row['cat_name']);
            }
            $htmlout .= '</a>';
        } else {
            $htmlout .= '-';
        }
        $htmlout .= "</td>\n";
        $dispname = htmlsafechars($row['name']);
        $smalldescr = (!empty($row['description']) ? '<i>[' . htmlsafechars($row['description']) . ']</i>' : '');
        $checked = ((!empty($row['checked_by']) && $CURUSER['class'] >= UC_USER) ? "&#160;<img src='{$INSTALLER09['pic_base_url']}mod.gif' width='15' border='0' alt='Checked - by " . htmlsafechars($row['checked_by']) . "' title='Checked - by " . htmlsafechars($row['checked_by']) . "' />" : '');
        $poster = empty($row['poster']) ? "<img src=\'{$INSTALLER09['pic_base_url']}noposter.png\' width=\'150\' height=\'220\' border=\'0\' alt=\'Poster\' title=\'poster\' />" : "<img src=\'" . htmlsafechars($row['poster']) . "\' width=\'150\' height=\'220\' border=\'0\' alt=\'Poster\' title=\'poster\' />";
        //$rating = empty($row["rating"]) ? "No votes yet":"".ratingpic($row["rating"])."";
        $youtube = (!empty($row['youtube']) ? "<a href='" . htmlsafechars($row['youtube']) . "' target='_blank'><img src='{$INSTALLER09['pic_base_url']}youtube.png' width='14' height='14' border='0' alt='Youtube Trailer' title='Youtube Trailer' /></a>" : '');
        if (isset($row['descr'])) {
            $descr = str_replace('"', '&quot;', readMore($row['descr'], 350, 'details.php?id=' . (int)$row['id'] . '&amp;hit=1'));
        }
        $descr = str_replace('&', '&amp;', $descr);
        $descr = preg_replace('/\[img.*\[\/img\]/i', '', $descr);
        $htmlout .= "<td align='left'><a href='details.php?";
        if ($variant == 'mytorrents') {
            $htmlout .= 'returnto=' . urlencode($_SERVER['REQUEST_URI']) . '&amp;';
        }
        $htmlout .= "id=$id";
        if ($variant == 'index') {
            $htmlout .= '&amp;hit=1';
        }
        $newgenre = '';
        if (!empty($row['newgenre'])) {
            $newgenre = [];
            $row['newgenre'] = explode(',', $row['newgenre']);
            foreach ($row['newgenre'] as $foo) {
                $newgenre[] = '<a href="browse.php?search=' . trim(strtolower($foo)) . '&amp;searchin=genre">' . $foo . '</a>';
            }
            $newgenre = '<i>' . join(', ', $newgenre) . '</i>';
        }
        $sticky = ($row['sticky'] == 'yes' ? "<img src='{$INSTALLER09['pic_base_url']}sticky.gif' style='border:none' alt='Sticky' title='Sticky !' />" : '');
        $nuked = ($row['nuked'] == 'yes' ? "<img src='{$INSTALLER09['pic_base_url']}nuked.gif' style='border:none' alt='Nuked'  class='text-right' title='Reason :" . htmlsafechars($row['nukereason']) . "' />" : '');
        $release_group = ($row['release_group'] == 'scene' ? "&#160;<img src='{$INSTALLER09['pic_base_url']}scene.gif' title='Scene' alt='Scene' style='border:none' />" : ($row['release_group'] == 'p2p' ? "&#160;<img src='{$INSTALLER09['pic_base_url']}p2p.gif' title='P2P' alt='P2P' />" : ''));
        $viponly = ($row['vip'] == 1 ? "<i class='fa fa-star-half-o fa-browse text-yellow' title='Vip Torrent'></i>" : '');
        $bump = ($row['bump'] == 'yes' ? "<img src='{$INSTALLER09['pic_base_url']}up.gif' width='12px' alt='Re-Animated torrent' title='This torrent was ReAnimated!' />" : '');
        /** FREE Torrent **/
        $freetorrent = (XBT_TRACKER == true && $row['freetorrent'] >= 1 ? "<img src='{$INSTALLER09['pic_base_url']}freedownload.gif' border='0' alt='Vip Torrent' title='Vip Torrent' />" : '');
        $free_tag = ($row['free'] != 0 ? ' <a class="info" href="#"><b>[FREE]</b> <span>' . ($row['free'] > 1 ? 'Expires: ' . get_date($row['free'], 'DATE') . '<br>(' . mkprettytime($row['free'] - TIME_NOW) . ' to go)<br>' : 'Unlimited<br>') . '</span></a>' : $all_free_tag);
        /** Silver Torrent **/
        $silver_tag = ($row['silver'] != 0 ? ' <a class="info" href="#"><b>[SILVER]</b> <span>' . ($row['silver'] > 1 ? 'Expires: ' . get_date($row['silver'], 'DATE') . '<br>(' . mkprettytime($row['silver'] - TIME_NOW) . ' to go)<br>' : 'Unlimited<br>') . '</span></a>' : '');
        if (!empty($slot)) {
            foreach ($slot as $sl) {
                if ($sl['torrentid'] == $id && $sl['free'] == 'yes') {
                    $free_slot = 1;
                }
                if ($sl['torrentid'] == $id && $sl['doubleup'] == 'yes') {
                    $double_slot = 1;
                }
                if ($free_slot && $double_slot) {
                    break;
                }
            }
        }
        $free_slot = ($free_slot == 1 ? '&#160;<img src="' . $INSTALLER09['pic_base_url'] . 'freedownload.gif" width="12px" alt="Free Slot" title="Free Slot in Use" />&#160;<small>Free Slot</small>' : '');
        $double_slot = ($double_slot == 1 ? '&#160;<img src="' . $INSTALLER09['pic_base_url'] . 'doubleseed.gif" width="12px" alt="Double Upload Slot" title="Double Upload Slot in Use" />&#160;<small>Double Slot</small><br>' : '');
        $nuked = ($row['nuked'] != 'no' && $row['nuked'] != '' ? '&#160;<span title="Nuked ' . htmlsafechars($row['nuked']) . '" class="browse-icons-nuked"></span>' : '');
        //==
        $Subs = '';
        if (in_array($row['category'], $INSTALLER09['movie_cats']) && !empty($row['subs'])) {
            $subs_array = explode(',', $row['subs']);
            require_once CACHE_DIR . 'subs.php';
            foreach ($subs_array as $k => $sid) {
                foreach ($subs as $sub) {
                    if ($sub['id'] == $sid) {
                        $Subs = "<img border=\'0\' width=\'16px\' style=\'padding:3px;\' src=\'{$sub['pic']}\' alt=\'{$sub['name']}\' title=\'{$sub['name']}\' />";
                    }
                }
            }
        } else {
            $Subs = '---';
        }
        $viponly = "
                                <span class='flex-item'>
                                    $viponly
                                </span>";
        $htmlout .= "' onmouseover=\"Tip('<b>" . CutName($dispname, 80) . '</b><br><b>Added: ' . get_date($row['added'], 'DATE', 0, 1) . '</b><br><b>Size: ' . mksize(htmlsafechars($row['size'])) . "</b><br><b>Subtitle: {$Subs}</b><br><b>Seeders: " . htmlsafechars($row['seeders']) . '</b><br><b>Leechers: ' . htmlsafechars($row['leechers']) . "</b><br>$poster');\" onmouseout=\"UnTip();\"><b>" . CutName($dispname, 45) . "</b></a><a href=\"javascript:klappe_descr('descr" . (int)$row['id'] . "');\" ><img src=\"{$INSTALLER09['pic_base_url']}plus.png\" border=\"0\" alt=\"Show torrent info in this page\" title=\"Show torrent info in this page\" /></a>{$youtube}{$viponly}{$release_group}{$sticky}" . ($row['added'] >= $CURUSER['last_browse'] ? " <img src='{$INSTALLER09['pic_base_url']}newb.png' border='0' alt='New !' title='New !' />" : '') . "{$checked}{$freetorrent}{$free_tag}{$silver_tag}<br>{$free_slot}{$double_slot}{$nuked}{$newgenre}{$bump}{$smalldescr}</td>\n";
        if ($variant == 'mytorrents') {
            $htmlout .= "
                <td>
                    <div class='flex-container'>
                        <div class='flex-inrow'>
                            <a href='download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? '&amp;ssl=1' : '') . "' class='flex-item'>
                                <i class='fa fa-floppy-o fa-browse' title='Download This Torrent!'></i>
                            </a>
                        </div>
                    </div>
                </td>";
        }
        if ($variant == 'mytorrents') {
            $htmlout .= "
                <td>
                    <div class='flex-container'>
                        <div class='flex-inrow'>
                            <a href='edit.php?id=" . (int)$row['id'] . 'amp;returnto=' . urlencode($_SERVER['REQUEST_URI']) . "' class='flex-item'>
                                {$lang['torrenttable_edit']}
                            </a>
                        </div>
                    </div>
                </td>";
        }
        $htmlout .= ($variant == 'index' ? "
                <td class='text-center'>
                    <div class='flex-container'>
                        <div class='flex-inrow'>
                            <a href='download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? '&amp;ssl=1' : '') . "'  class='flex-item'>
                                <i class='fa fa-floppy-o fa-browse' title='Download This Torrent!'></i>
                            </a>
                        </div>
                    </div>
                </td>" : '');
        if ($variant == 'mytorrents') {
            $htmlout .= "<td class='text-right'>";
            if ($row['visible'] == 'no') {
                $htmlout .= "<b>{$lang['torrenttable_not_visible']}</b>";
            } else {
                $htmlout .= "{$lang['torrenttable_visible']}";
            }
            $htmlout .= "</td>\n";
        }
        /** pdq bookmarks **/
        $booked = '';
        if (!empty($book)) {
            foreach ($book as $bk) {
                if ($bk['torrentid'] == $id) {
                    $booked = 1;
                }
            }
        }
        $rm_status = (!$booked ? ' style="display:none;"' : ' style="display:inline;"');
        $bm_status = ($booked ? ' style="display:none;"' : ' style="display:inline;"');
        $bookmark = "
                    <span id='bookmark{$id}' {$bm_status}>
                        <div class='flex-container'>
                            <div class='flex-inrow'>
                                <a href='bookmark.php?torrent={$id}&amp;action=add' class='flex-item bookmark' name='{$id}'>
                                    <i class='fa fa-check fa-browse text-lime' title='Bookmark it!'></i>
                                </a>
                            </div>
                        </div>
                    </span>
                    <span id='remove{$id}' {$rm_status}>
                        <div class='flex-container'>
                            <div class='flex-inrow'>
                                <a href='bookmark.php?torrent={$id}&amp;action=delete' class='flex-item remove' name='{$id}'>
                                    <i class='fa fa-check fa-browse text-red' title='Delete Bookmark!'></i>
                                </a>
                            </div>
                        </div>
                    </span>";
        if ($variant == 'index') {
            $htmlout .= "<td class='text-right'>{$bookmark}</td>";
        }
        if ($row['numfiles'] <= 1) {
            $htmlout .= "<td class='text-right'>" . (int)$row['numfiles'] . "</td>\n";
        } else {
            if ($variant == 'index') {
                $htmlout .= "<td class='text-right'><b><a href='filelist.php?id=$id'>" . (int)$row['numfiles'] . "</a></b></td>\n";
            } else {
                $htmlout .= "<td class='text-right'><b><a href='filelist.php?id=$id'>" . (int)$row['numfiles'] . "</a></b></td>\n";
            }
        }
        if (!$row['comments']) {
            $htmlout .= "<td class='text-right'>" . (int)$row['comments'] . "</td>\n";
        } else {
            if ($variant == 'index') {
                $htmlout .= "<td class='text-right'><b><a href='details.php?id=$id&amp;hit=1&amp;tocomm=1'>" . (int)$row['comments'] . "</a></b></td>\n";
            } else {
                $htmlout .= "<td class='text-right'><b><a href='details.php?id=$id&amp;page=0#startcomments'>" . (int)$row['comments'] . "</a></b></td>\n";
            }
        }
        $htmlout .= "<td class='text-center'><span style='white-space: nowrap;'>" . str_replace(',', '<br>', get_date($row['added'], '')) . "</span></td>\n";
        $htmlout .= "<td class='text-center'>" . str_replace(' ', '<br>', mksize($row['size'])) . "</td>\n";
        if ($row['times_completed'] != 1) {
            $_s = '' . $lang['torrenttable_time_plural'] . '';
        } else {
            $_s = '' . $lang['torrenttable_time_singular'] . '';
        }
        $What_Script_S = (XBT_TRACKER == true ? 'snatches_xbt.php?id=' : 'snatches.php?id=');
        $htmlout .= "<td class='text-center'><a href='$What_Script_S" . "$id'>" . number_format($row['times_completed']) . "<br>$_s</a></td>\n";
        if ($row['seeders']) {
            if ($variant == 'index') {
                if ($row['leechers']) {
                    $ratio = $row['seeders'] / $row['leechers'];
                } else {
                    $ratio = 1;
                }
                $What_Script_P = (XBT_TRACKER == true ? 'peerlist_xbt.php?id=' : 'peerlist.php?id=');
                $htmlout .= "<td class='text-right'><b><a href='$What_Script_P" . "$id#seeders'><font color='" . get_slr_color($ratio) . "'>" . (int)$row['seeders'] . "</font></a></b></td>\n";
            } else {
                $What_Script_P = (XBT_TRACKER == true ? 'peerlist_xbt.php?id=' : 'peerlist.php?id=');
                $htmlout .= "<td class='text-right'><b><a class='" . linkcolor($row['seeders']) . "' href='$What_Script_P" . "$id#seeders'>" . (int)$row['seeders'] . "</a></b></td>\n";
            }
        } else {
            $htmlout .= "<td class='text-right'><span class='" . linkcolor($row['seeders']) . "'>" . (int)$row['seeders'] . "</span></td>\n";
        }
        if ($row['leechers']) {
            $What_Script_P = (XBT_TRACKER == true ? 'peerlist_xbt.php?id=' : 'peerlist.php?id=');
            if ($variant == 'index') {
                $htmlout .= "<td class='text-right'><b><a href='$What_Script_P" . "$id#leechers'>" . number_format($row['leechers']) . "</a></b></td>\n";
            } else {
                $htmlout .= "<td class='text-right'><b><a class='" . linkcolor($row['leechers']) . "' href='$What_Script_P" . "$id#leechers'>" . (int)$row['leechers'] . "</a></b></td>\n";
            }
        } else {
            $htmlout .= "<td class='text-right'>0</td>\n";
        }
        if ($variant == 'index') {
            $htmlout .= "<td class='text-center'>" . (isset($row['owner']) ? (($row['anonymous'] == 'yes' && $CURUSER['class'] < UC_STAFF && $row['owner'] != $CURUSER['id']) ? '<i>' . $lang['torrenttable_anon'] . '</i>' : format_username($row['owner'])) : '<i>(' . $lang['torrenttable_unknown_uploader'] . ')</i>') . "</td>\n";
        }
        if ($CURUSER['class'] >= UC_STAFF) {
            $returnto = !empty($_SERVER['REQUEST_URI']) ? '&amp;returnto=' . urlencode($_SERVER['REQUEST_URI']) : '';

            $edit_link = "
                <a href='./edit.php?id=" . (int)$row['id'] . "{$returnto}' class='flex-item'>
                    <i class='fa fa-pencil fa-browse' title='Fast Delete'></i>
                </a>";
            $del_link = ($CURUSER['class'] === UC_MAX ? "
                <a href='./fastdelete.php?id=" . (int)$row['id'] . "{$returnto}' class='flex-item'>
                    <i class='fa fa-trash-o fa-browse' title='Fast Delete'></i>
                </a>" : '');
            $htmlout .= "
                        <td>
                            <div class='flex-container'>
                                <div class='flex-inrow'>
                                    {$edit_link}
                                    {$del_link}
                                </div>
                            </div>
                        </td>";
        }
        $htmlout .= "</tr>\n";
        $htmlout .= '<tr id="kdescr' . (int)$row['id'] . '" style="display:none;"><td width="100%" colspan="13">' . format_comment($descr, false) . "</td></tr>\n";
    }
    $htmlout .= "</table>\n";

    return $htmlout;
}
