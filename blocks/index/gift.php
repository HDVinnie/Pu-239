<?php

global $CURUSER, $site_config, $lang;

if ($CURUSER['gotgift'] === 'no') {
    $christmas_gift .= "
    <a id='gift-hash'></a>
    <div id='gift' class='box'>
        <div class='bordered'>
            <div class='alt_bordered bg-00 has-text-centered'>
                <a href='{$site_config['baseurl']}/gift.php?open=1'>
                    <img src='{$site_config['pic_baseurl']}gift.png' class='tooltipper image_48' alt='{$lang['index_christmas_gift']}' title='{$lang['index_christmas_gift']}'>
                </a>
            </div>
        </div>
    </div>";
}
