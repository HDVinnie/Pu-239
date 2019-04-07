<?php

require_once INCL_DIR . 'function_event.php';
global $fluent, $site_config, $cache;

$free = get_event(false);
$freeleech_enabled = $double_upload_enabled = $half_down_enabled = false;
if (!empty($free) && $free['modifier'] != 0) {
    $begin = $free['begin'];
    $expires = $free['expires'];
    if ($free['modifier'] === 1) {
        $freeleech_start_time = $free['begin'];
        $freeleech_end_time = $free['expires'];
        $freeleech_enabled = true;
    } elseif ($free['modifier'] === 2) {
        $double_upload_start_time = $free['begin'];
        $double_upload_end_time = $free['expires'];
        $double_upload_enabled = true;
    } elseif ($free['modifier'] === 3) {
        $freeleech_start_time = $free['begin'];
        $freeleech_end_time = $free['expires'];
        $freeleech_enabled = true;
        $double_upload_start_time = $free['begin'];
        $double_upload_end_time = $free['expires'];
        $double_upload_enabled = true;
    } elseif ($free['modifier'] === 4) {
        $half_down_start_time = $free['begin'];
        $half_down_end_time = $free['expires'];
        $half_down_enabled = true;
    }
}

$freeleech = $cache->get('freeleech_alerts_');
if ($freeleech === false || is_null($freeleech)) {
    $freeleech = $fluent->from('bonus')
                        ->select(null)
                        ->select('pointspool / points * 100 AS percent')
                        ->select('enabled')
                        ->where('id=11')
                        ->fetch();

    $cache->set('freeleech_alerts_', $freeleech, 0);
}

$percent_fl = number_format($freeleech['percent'], 2);
if ($freeleech['enabled'] === 'yes') {
    switch ($percent_fl) {
        case $percent_fl >= 90:
            $font_color_fl = "<span class='has-text-green'> {$percent_fl}%</span>";
            break;
        case $percent_fl >= 80:
            $font_color_fl = "<span class='has-text-lightgreen'> {$percent_fl}%</span>";
            break;
        case $percent_fl >= 70:
            $font_color_fl = "<span class='has-text-jade'> {$percent_fl}%</span>";
            break;
        case $percent_fl >= 50:
            $font_color_fl = "<span class='has-text-turquoise'> {$percent_fl}%</span>";
            break;
        case $percent_fl >= 40:
            $font_color_fl = "<span class='has-text-lghtblue'> {$percent_fl}%</span>";
            break;
        case $percent_fl >= 30:
            $font_color_fl = "<span class='has-text-gold'> {$percent_fl}%</span>";
            break;
        case $percent_fl >= 20:
            $font_color_fl = "<span class='has-text-oragne'> {$percent_fl}%</span>";
            break;
        case $percent_fl < 20:
            $font_color_fl = "<span class='has-text-danger'> {$percent_fl}%</span>";
            break;
    }
}

$doubleupload = $cache->get('doubleupload_alerts_');
if ($doubleupload === false || is_null($doubleupload)) {
    $doubleupload = $fluent->from('bonus')
                           ->select(null)
                           ->select('pointspool / points * 100 AS percent')
                           ->select('enabled')
                           ->where('id=12')
                           ->fetch();

    $cache->set('doubleupload_alerts_', $doubleupload, 0);
}

$percent_du = number_format($doubleupload['percent'], 2);
if ($doubleupload['enabled'] === 'yes') {
    switch ($percent_du) {
        case $percent_du >= 90:
            $font_color_du = "<span class='has-text-green'> {$percent_du}%</span>";
            break;
        case $percent_du >= 80:
            $font_color_du = "<span class='has-text-lightgreen'> {$percent_du}%</span>";
            break;
        case $percent_du >= 70:
            $font_color_du = "<span class='has-text-jade'> {$percent_du}%</span>";
            break;
        case $percent_du >= 50:
            $font_color_du = "<span class='has-text-turquoise'> {$percent_du}%</span>";
            break;
        case $percent_du >= 40:
            $font_color_du = "<span class='has-text-lghtblue'> {$percent_du}%</span>";
            break;
        case $percent_du >= 30:
            $font_color_du = "<span class='has-text-gold'> {$percent_du}%</span>";
            break;
        case $percent_du >= 20:
            $font_color_du = "<span class='has-text-oragne'> {$percent_du}%</span>";
            break;
        case $percent_du < 20:
            $font_color_du = "<span class='has-text-danger'> {$percent_du}%</span>";
            break;
    }
}

$halfdownload = $cache->get('halfdownload_alerts_');
if ($halfdownload === false || is_null($halfdownload)) {
    $halfdownload = $fluent->from('bonus')
                           ->select(null)
                           ->select('pointspool / points * 100 AS percent')
                           ->select('enabled')
                           ->where('id=13')
                           ->fetch();

    $cache->set('halfdownload_alerts_', $halfdownload, 0);
}

$percent_hd = number_format($halfdownload['percent'], 2);
if ($halfdownload['enabled'] === 'yes') {
    switch ($percent_hd) {
        case $percent_hd >= 90:
            $font_color_hd = "<span class='has-text-green'> {$percent_hd}%</span>";
            break;
        case $percent_hd >= 80:
            $font_color_hd = "<span class='has-text-lightgreen'> {$percent_hd}%</span>";
            break;
        case $percent_hd >= 70:
            $font_color_hd = "<span class='has-text-jade'> {$percent_hd}%</span>";
            break;
        case $percent_hd >= 50:
            $font_color_hd = "<span class='has-text-turquoise'> {$percent_hd}%</span>";
            break;
        case $percent_hd >= 40:
            $font_color_hd = "<span class='has-text-lghtblue'> {$percent_hd}%</span>";
            break;
        case $percent_hd >= 30:
            $font_color_hd = "<span class='has-text-gold'> {$percent_hd}%</span>";
            break;
        case $percent_hd >= 20:
            $font_color_hd = "<span class='has-text-oragne'> {$percent_hd}%</span>";
            break;
        case $percent_hd < 20:
            $font_color_hd = "<span class='has-text-danger'> {$percent_hd}%</span>";
            break;
    }
}

if ($freeleech['enabled'] === 'yes') {
    if ($freeleech_enabled) {
        $fstatus = "<span class='has-text-green'> ON </span>";
    } else {
        $fstatus = $font_color_fl . '';
    }
}
if ($doubleupload['enabled'] === 'yes') {
    if ($double_upload_enabled) {
        $dstatus = "<span class='has-text-green'> ON </span>";
    } else {
        $dstatus = $font_color_du . '';
    }
}
if ($halfdownload['enabled'] === 'yes') {
    if ($half_down_enabled) {
        $hstatus = "<span class='has-text-green'> ON </span>";
    } else {
        $hstatus = $font_color_hd . '';
    }
}
if ($freeleech['enabled'] === 'yes' || $halfdownload['enabled'] === 'yes' || $doubleupload['enabled'] === 'yes') {
    $htmlout .= "
        <li>
            <a href='{$site_config['paths']['baseurl']}/mybonus.php'>
                <span class='button tag is-success dt-tooltipper-large' data-tooltip-content='#karma_tooltip'>Karma Contribution's</span>
                <div class='tooltip_templates'>
                    <div id='karma_tooltip' class='margin20'>
                        <div class='size_6 has-text-centered has-text-success has-text-weight-bold bottom10'>
                            Karma Contribution's
                        </div>";
    if ($freeleech['enabled'] === 'yes') {
        $htmlout .= "
                        <div class='level is-marginless'>
                            <span>Freeleech</span><span class='left10'> [ ";
        if ($freeleech_enabled) {
            $htmlout .= "<span class='has-text-success'> ON </span>" . get_date($freeleech_start_time, 'DATE') . ' - ' . get_date($freeleech_end_time, 'DATE');
        } else {
            $htmlout .= $fstatus;
        }
        $htmlout .= ' ]
                            </span>
                        </div>';
    }
    if ($doubleupload['enabled'] === 'yes') {
        $htmlout .= "
                        <div class='level is-marginless'>
                            <span>DoubleUpload</span><span class='left10'> [ ";
        if ($double_upload_enabled) {
            $htmlout .= "<span class='has-text-success'> ON </span>" . get_date($double_upload_start_time, 'DATE') . ' - ' . get_date($double_upload_end_time, 'DATE');
        } else {
            $htmlout .= $dstatus;
        }
        $htmlout .= ' ]
                            </span>
                        </div>';
    }
    if ($halfdownload['enabled'] === 'yes') {
        $htmlout .= "
                        <div class='level is-marginless'>
                            <span>Half Download</span><span class='left10'> [ ";
        if ($half_down_enabled) {
            $htmlout .= '<span class="has-text-success"> ON</span> ' . get_date($half_down_start_time, 'DATE') . ' - ' . get_date($half_down_end_time, 'DATE');
        } else {
            $htmlout .= $hstatus;
        }
        $htmlout .= ' ]
                            </span>
                        </div>';
    }
    $htmlout .= '
                    </div>
                </div>
            </a>
        </li>';
}
