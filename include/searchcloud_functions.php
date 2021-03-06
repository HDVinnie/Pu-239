<?php

/**
 * @param int $limit
 *
 * @return array|string
 */
function searchcloud($limit = 50)
{
    global $cache, $fluent;

    $searchcloud = $cache->get('searchcloud');
    if ($searchcloud === false || is_null($searchcloud)) {
        $search = $fluent->from('searchcloud')
            ->select('searchedfor')
            ->select('howmuch')
            ->select('search_column')
            ->orderBy('howmuch DESC');
        if ($limit > 0) {
            $search = $search->limit($limit);
        }
        $searchcloud = [];
        $min = 100000;
        $max = 0;
        foreach ($search as $item) {
            $min = $min > $item['howmuch'] ? $item['howmuch'] : $min;
            $max = $max < $item['howmuch'] ? $item['howmuch'] : $max;
            $searchcloud[] = [
                'searchedfor' => $item['searchedfor'],
                'howmuch' => $item['howmuch'],
                'column' => $item['search_column'],
            ];
        }
        if (!empty($searchcloud)) {
            $searchcloud = [
                'search' => $searchcloud,
                'min' => $min,
                'max' => $max
            ];
            $cache->set('searchcloud', $searchcloud, 0);
            return $searchcloud;
        }

        return [];
    }

    return $searchcloud;
}

/**
 * @param $word
 *
 * @throws \Envms\FluentPDO\Exception
 * @throws \MatthiasMullie\Scrapbook\Exception\UnbegunTransaction
 */
function searchcloud_insert($word, $column)
{
    global $cache, $searchcloud_stuffs;

    $searchcloud = searchcloud();
    $ip = getip();
    $howmuch = 1;
    $add = true;
    foreach ($searchcloud['search'] as $cloud) {
        if (strtolower($word) === strtolower($cloud['searchedfor'])) {
            $add = false;
            $howmuch = $cloud['howmuch'] + 1;
        }
    }


    if ($add) {
        $searchcloud['search'][] = [
            'searchedfor' => $word,
            'howmuch' => $howmuch,
            'column' => $column,
        ];
        $cache->set('searchcloud', $searchcloud, 0);
    } else {
        $cache->delete('searchcloud');
    }

    $values = [
        'searchedfor' => $word,
        'search_column' => $column,
        'howmuch' => 1,
        'ip' => inet_pton($ip),
    ];
    $update = [
        'howmuch' => $howmuch,
        'search_column' => new Envms\FluentPDO\Literal('VALUES(search_column)'),
        'ip' => new Envms\FluentPDO\Literal('VALUES(ip)'),
    ];

    $searchcloud_stuffs->insert($values, $update);
}

/**
 * @return string
 */
function cloud()
{
    global $site_config;

    $small = 14;
    $big = 40;
    $tags = searchcloud();

    if (!empty($tags)) {
        $minimum_count = $tags['min'];
        $maximum_count = $tags['max'];
        $spread = $maximum_count - $minimum_count;
        if ($spread == 0) {
            $spread = 1;
        }
        $cloud_html = '';
        $cloud_tags = [];
        foreach ($tags['search'] as $tag) {
            if (!empty($tag['searchedfor'])) {
                $search[$tag['searchedfor']] = $tag;
            }
        }
        $tags = shuffle_assoc($search, 10);
        foreach ($tags as $tag => $values) {
            $count = $values['howmuch'];
            $column = $values['column'];
            $size = floor($small + round(($count - $minimum_count) * ($big - $small) / $spread, 0, PHP_ROUND_HALF_UP));
            $color = random_color(100, 200);
            $cloud_tags[] = "
                            <a class='tooltipper tag_cloud' style='color:{$color}; font-size: {$size}px' href='{$site_config['baseurl']}/browse.php?search_{$column}=" . urlencode($tag) . "&amp;incldead=1' title='<div class=\"size_5 has-text-primary has-text-centered\">\"" . htmlsafechars($tag) . "\"</div><br>has been searched for {$count} times.'>
                                <span class='padding10 has-no-wrap'>" . htmlsafechars(stripslashes($tag)) . '</span>
                            </a>';
        }
        $cloud_html = implode("\n", $cloud_tags) . "\n";

        return $cloud_html;
    }
}

