<?php

require_once dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php';
require_once INCL_DIR . 'pager_functions.php';
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'share_images.php';
check_user_status();
global $site_config, $fluent, $cache;

$lang = array_merge(load_language('global'), load_language('browse'));
$valid_search = [
    'sn',
    'sys',
    'sye',
    'srs',
    'sre',
];


$count = $fluent->from('torrents AS t')
    ->select(null)
    ->select('COUNT(*) AS count')
    ->where('t.category', $site_config['movie_cats']);

$select = $fluent->from('torrents AS t')
    ->select(null)
    ->select('t.id')
    ->select('t.name')
    ->select('t.poster')
    ->select('t.imdb_id')
    ->select('t.seeders')
    ->select('t.leechers')
    ->select('t.year')
    ->select('t.rating')
    ->where('t.category', $site_config['movie_cats'])
    ->groupBy('t.imdb_id, t.id');

$title = $addparam = '';
foreach ($valid_search as $search) {
    if (!empty($_GET[$search])) {
        $cleaned = searchfield($_GET[$search]);
        $title .= " $cleaned";

        if ($search != 'srs' && $search != 'sre') {
            $addparam .= "{$search}=" . urlencode($cleaned) . '&amp;';
        }
        if ($search === 'sn') {
            $count = $count->where('MATCH (t.name) AGAINST (? IN NATURAL LANGUAGE MODE)', $cleaned);
            $select = $select->where('MATCH (t.name) AGAINST (? IN NATURAL LANGUAGE MODE)', $cleaned);
        } elseif ($search === 'sys') {
            $count = $count->where('t.year >= ?', (int) $cleaned);
            $select = $select->where('t.year >= ?', (int) $cleaned)
                ->orderBy('t.year DESC');
        } elseif ($search === 'sye') {
            $count = $count->where('t.year <= ?', (int) $cleaned);
            $select = $select->where('t.year <= ?', (int) $cleaned)
                ->orderBy('t.year DESC');
        } elseif ($search === 'srs') {
            $addparam .= "{$search}=" . urlencode($_GET['srs']) . '&amp;';
            $count = $count->where('t.rating >= ?', (float) $_GET['srs']);
            $select = $select->where('t.rating >= ?', (float) $_GET['srs'])
                ->orderBy('t.rating DESC');
        } elseif ($search === 'sre') {
            $addparam .= "{$search}=" . urlencode($_GET['sre']) . '&amp;';
            $count = $count->where('t.rating <= ?', (float) $_GET['sre']);
            $select = $select->where('t.rating <= ?', (float) $_GET['sre'])
                ->orderBy('t.rating DESC');
        }
    }
}

$count = $count->fetch('count');
$perpage = 25;
$addparam = empty($addparam) ? '?' : $addparam . '&amp;';
$pager = pager($perpage, $count, "{$site_config['baseurl']}/tmovies.php{$addparam}");
$select = $select->limit($pager['pdo'])
    ->orderBy('t.added DESC');
$HTMLOUT = "
    <h1 class='has-text-centered top20'>Movies</h1>";

$body = "
        <div class='masonry padding20'>";
foreach ($select as $torrent) {
    $cast = $cache->get('cast_' . $torrent['imdb_id']);
    if ($cast === false || is_null($cast)) {
        $cast = $fluent->from('person AS p')
            ->select(null)
            ->select('p.id')
            ->select('p.name')
            ->innerJoin('imdb_person AS i ON p.imdb_id = i.person_id')
            ->where('i.imdb_id = ?', str_replace('tt', '', $torrent['imdb_id']))
            ->where('i.type = "cast"')
            ->orderBy('p.id')
            ->limit(7)
            ->fetchAll();
        $cache->set('cast_' . $torrent['imdb_id'], $cast, 604800);
    }

    $casts[] = $cast;
    $people = [];
    foreach ($cast as $person) {
        $people[] = "<div><a href='{$site_config['baseurl']}/browse.php?sp=" . urlencode(htmlsafechars($person['name'])) . "'>" . htmlsafechars($person['name']) . "</a></div>";
    }

    $name = "<a href='{$site_config['baseurl']}/browse.php?si={$torrent['imdb_id']}'>" . htmlsafechars($torrent['name']) . "</a>";
    if (empty($torrent['poster'])) {
        $image = find_images($torrent['imdb_id'], 'poster');
        if (!empty($image)) {
            $image = url_proxy($image, true);
        } else {
            $image = $site_config['pic_baseurl'] . 'noposter.png';
        }
    } else {
        $image = url_proxy($torrent['poster'], true);
    }
    $percent = $torrent['rating'] * 10;
    $rating = "
                <a href='{$site_config['baseurl']}/browse.php?srs={$torrent['rating']}&amp;sre={$torrent['rating']}'>
                    <div>
                        <span class='level-left'>
                            <div class='right5'>{$percent}%</div>
                            <div class='star-ratings-css'>
                                <div class='star-ratings-css-top' style='width: {$percent}%'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                                <div class='star-ratings-css-bottom'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                            </div>
                        </span>
                    </div>
                </a>";

    $seeders = "<a href='{$site_config['baseurl']}/peerlist.php?id={$torrent['seeders']}#seeders'>{$torrent['seeders']}</a>";
    $leechers = "<a href='{$site_config['baseurl']}/peerlist.php?id={$torrent['leechers']}#leechers'>{$torrent['leechers']}</a>";
    $year = "<a href='{$site_config['baseurl']}/browse.php?sys={$torrent['year']}&amp;sye={$torrent['year']}'>{$torrent['year']}</a>";
    $body .= "
                <div class='masonry-item padding10 bg-04 round10'>
                    <div class='columns'>
                        <div class='column'>
                            <img src='{$image}' alt='" . htmlsafechars($torrent['name']) . "'>
                        </div>
                        <div class='column'>
                            <div class='has-text-left'>$name ({$year})</div>
                            $rating
                            <div><span class='has-text-primary'>Peers:</span><span class='has-text-white'> {$seeders} / {$leechers}</span></div>" . implode("\n", $people) . "
                        </div>
                    </div>
                </div>";
}
$body .= "
        </div>";

$HTMLOUT .= main_div("
            <form id='test1' method='get' action='{$site_config['baseurl']}/tmovies.php'>
                <div class='padding20'>
                    <div class='padding10 w-100'>
                        <div class='columns'>
                            <div class='column'>
                                <div class='has-text-centered bottom10'>{$lang['browse_name']}</div>
                                <input id='search' name='sn' type='text' data-csrf='" . $session->get('csrf_token') . "' placeholder='{$lang['search_name']}' class='search w-100' value='" . (!empty($_GET['sn']) ? $_GET['sn'] : '') . "' onkeyup='autosearch()'>
                            </div>
                            <div class='column'>
                                <div class='columns'>
                                    <div class='column'>
                                        <div class='has-text-centered bottom10'>{$lang['browse_year_start']}</div>
                                        <input name='sys' type='number' min='1900' max='" . (date('Y') + 1) . "' placeholder='{$lang['search_year_start']}' class='search w-100' value='" . (!empty($_GET['sys']) ? $_GET['sys'] : '') . "'>
                                    </div>
                                    <div class='column'>
                                        <div class='has-text-centered bottom10'>{$lang['browse_year_end']}</div>
                                        <input name='sye' type='number' min='1900' max='" . (date('Y') + 1) . "' placeholder='{$lang['search_year_end']}' class='search w-100' value='" . (!empty($_GET['sye']) ? $_GET['sye'] : '') . "'>
                                    </div>
                                </div>
                            </div>
                            <div class='column'>
                                <div class='columns'>
                                    <div class='column'>
                                        <div class='has-text-centered bottom10'>{$lang['browse_rating_start']}</div>
                                        <input name='srs' type='number' min='0' max='10' step='0.1' placeholder='{$lang['search_rating_start']}' class='search w-100' value='" . (!empty($_GET['srs']) ? $_GET['srs'] : '') . "'>
                                    </div>
                                    <div class='column'>
                                        <div class='has-text-centered bottom10'>{$lang['browse_rating_end']}</div>
                                        <input name='sre' type='number' min='0' max='10' step='0.1' placeholder='{$lang['search_rating_end']}' class='search w-100' value='" . (!empty($_GET['sre']) ? $_GET['sre'] : '') . "'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='margin10 has-text-centered'>
                        <input type='submit' value='{$lang['search_search_btn']}' class='button is-small'>
                    </div>
                </div>
            </form>");

$HTMLOUT .= "<div class='top20'>" . ($count > $perpage ? $pager['pagertop'] : '') . main_div($body, 'top20') . ($count > $perpage ? $pager['pagertop'] : '') . '</div>';

echo stdhead('Movies' . $title) . wrapper($HTMLOUT) . stdfoot();