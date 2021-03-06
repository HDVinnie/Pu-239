<?php

$foo = [
    'Database' => [
        [
            'text' => 'Host',
            'input' => 'config[mysql_host]',
            'info' => 'Usually this will be localhost unless your on a cluster server.',
            'placeholder' => 'localhost',
        ],
        [
            'text' => 'Username',
            'input' => 'config[mysql_user]',
            'info' => 'Your mysql username.',
            'placeholder' => 'mydbuser',
        ],
        [
            'text' => 'Password',
            'input' => 'config[mysql_pass]',
            'info' => 'Your mysql password.',
            'placeholder' => 'secret',
        ],
        [
            'text' => 'Database',
            'input' => 'config[mysql_db]',
            'info' => 'Your mysql database name.',
            'placeholder' => 'pu239',
        ],
    ],
    'Tracker' => [
        [
            'text' => 'Announce Url',
            'input' => 'config[announce_http]',
            'info' => 'Your announce url. ie. http://pu-239.pw',
            'placeholder' => 'http://Pu-239.pw',
        ],
        [
            'text' => 'HTTPS Announce Url',
            'input' => 'config[https_announce]',
            'info' => 'Your HTTPS announce url. ie. https://pu-239.pw',
            'placeholder' => 'https://Pu-239.pw',
        ],
        [
            'text' => 'Site Email',
            'input' => 'config[site_email]',
            'info' => 'Your site email address.',
            'placeholder' => 'myuser@mymail.com',
        ],
        [
            'text' => 'Site Name',
            'input' => 'config[site_name]',
            'info' => 'Your site name.',
            'placeholder' => 'Crafty',
        ],
        /*
                [
                    'text'  => 'Using XBT Tracker',
                    'input' => 'config[xbt_tracker]',
                    'info'  => 'Check if yes.',
                    'placeholder' => 'checked',
                ],
        */
    ],
    'Cookie' => [
        [
            'text' => 'Session Name',
            'input' => 'config[sessionName]',
            'info' => 'A single word that uniquely identifies this install.',
            'placeholder' => 'pu239',
        ],
        [
            'text' => 'Prefix',
            'input' => 'config[cookie_prefix]',
            'info' => 'A single word that uniquely identifies this install. Can be the same as Session Name, but not required.',
            'placeholder' => 'pu239',
        ],
        [
            'text' => 'Path',
            'input' => 'config[cookie_path]',
            'info' => 'Required "/" or any other path.',
            'placeholder' => '/',
        ],
        [
            'text' => 'Cookie Lifetime',
            'input' => 'config[cookie_lifetime]',
            'info' => 'The number of minutes that the cookie is alive. 15 is recommended as the remember me cookie will handle longer expiration times.',
            'placeholder' => '15',
        ],
        [
            'text' => 'Cookie Domain',
            'input' => 'config[cookie_domain]',
            'info' => 'Your site domain name - note exclude http and www. This must match the domain used to access the site',
            'explain' => '<div>For the sessions to work correctly, the session "Cookie Domain" must match the webserver\'s "server_name".<br>For example:</div><div class="flex"><span>Nginx: </span><span>server_name Pu-239.pw;</span></div><div class="flex"><span>Cookie Domain: </span><span>Pu-239.pw&#160;</span></div>',
            'placeholder' => 'Pu-239.pw',
        ],
        [
            'text' => 'Domain',
            'input' => 'config[domain]',
            'info' => 'Your site domain name - note exclude http or www. This must match the domain used to access the site.',
            'placeholder' => 'Pu-239.pw',
        ],
    ],
    'System - Site BOT' => [
        [
            'text' => 'Username',
            'input' => 'config[bot_username]',
            'info' => "The name for your 'System' user/Site BOT.",
            'placeholder' => 'CraftyBOT',
        ],
    ],
];
/**
 * @param $x
 *
 * @return string
 */
function foo($x)
{
    return '/\#' . $x . '/';
}

/**
 * @param $fo
 * @param $foo
 *
 * @return string
 */
function createblock($fo, $foo)
{
    $out = '
    <fieldset>
        <legend>' . $fo . '</legend>
        <table align="left">';
    foreach ($foo as $bo) {
        $out .= '
            <tr>
                <td class="input_text">' . $bo['text'] . '</td>';
        if (strpos($bo['input'], 'pass') == true) {
            $type = 'password';
        } elseif ($bo['input'] == 'config[xbt_tracker]') {
            $type = 'checkbox" value="yes"';
        } else {
            $type = 'text';
        }
        $explain = !empty($bo['explain']) ? "<div class='info'>{$bo['explain']}</div>" : '';
        $out .= "
                <td class='input_input'>
                    <input type='{$type}' name='{$bo['input']}' size='30' placeholder='{$bo['placeholder']}' title='{$bo['info']}' required>$explain
                </td>
            </tr>";
    }
    $out .= '
        </table>
    </fieldset>';

    return $out;
}

function saveconfig()
{
    global $root;

    $continue = true;
    $out = '
    <fieldset>
        <legend>Write config</legend>';

    foreach ($_POST['config'] as $key => $value) {
        if (!isset($value) || $value === '') {
            $out .= "
        <div class='notreadable'>$key must not be empty</div>";
            $continue = false;
        }
    }

    if ($continue) {
        $file = $root . '.env.example';
        if (file_exists($file)) {
            $env = file_get_contents($file);
            $keys = array_map('foo', array_keys($_POST['config']));
            $values = array_values($_POST['config']);
            $env = preg_replace($keys, $values, $env);
            if (file_put_contents($root . '.env', $env)) {
                $out .= '
        <div class="readable">.env file was created</div>';
            } else {
                $out .= '
        <div class="notreadable">.env file could not be saved</div>';
                $continue = false;
            }
        }

        if (isset($_POST['config']['xbt_tracker'])) {
            $file = 'extra/config.xbtsample.php';
        //$xbt = 1;
        } else {
            $file = 'extra/config.phpsample.php';
            //$xbt = 0;
        }

        $config = file_get_contents($file);
        $keys = array_map('foo', array_keys($_POST['config']));
        $values = array_values($_POST['config']);
        $config = preg_replace($keys, $values, $config);
        $config = preg_replace('/#pass1/', bin2hex(random_bytes(16)), $config);
        $config = preg_replace('/#pass2/', bin2hex(random_bytes(16)), $config);
        $config = preg_replace('/#pass3/', bin2hex(random_bytes(16)), $config);
        $config = preg_replace('/#pass4/', bin2hex(random_bytes(16)), $config);
        $config = preg_replace('/#pass5/', bin2hex(random_bytes(16)), $config);
        $config = preg_replace('/#pass6/', bin2hex(random_bytes(16)), $config);

        if (file_put_contents($root . 'include/config.php', $config)) {
            $out .= '
        <div class="readable">config.php file was created</div>';
        } else {
            $out .= '
        <div class="notreadable">config.php file could not be saved</div>';
            $continue = false;
        }
    }
    if (rrmdir('/dev/shm/' . $_POST['config']['mysql_db'])) {
        $out .= '
        <div class="readable">default cache has ben cleared</div>';
    }

    if ($continue) {
        //$xbt = 0;
        if (isset($_POST['config']['xbt_tracker'])) {
            //$xbt = 1;
        }
        $out .= '
        </fieldset>
        <div style="text-align:center;">
            <input type="button" value="Next step" onclick="onClick(5)">
        </div>';
    } else {
        $out .= '
        </fieldset>
        <div style="text-align:center;" class="info">
            <input type="button" value="Go back" onclick="goBack()"/>
        </div>';
    }

    $out .= '
    <script>
        localStorage.setItem("step", 5);
        var processing = 5;

        function goBack() {
            localStorage.setItem("step", 4);
            var processing = 4;
            window.history.back();
        }
    </script>';

    echo $out;
}

/**
 * @param $dir
 *
 * @return bool
 */
function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                if (filetype($dir . '/' . $object) == 'dir') {
                    rrmdir($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }
        }
        reset($objects);
        rmdir($dir);

        return true;
    }

    return false;
}
