<?php

$endpoint = "https://plugin-usage.edacerton.win/";

if ($tailscale_config['USAGE']) {
    if (! isset($var)) {
        $var = parse_ini_file('/usr/local/emhttp/state/var.ini');
    }

    $version = parse_ini_file('/var/local/emhttp/plugins/tailscale/tailscale.ini');
    $query = array(
        'clientId' => hash("crc32b", $var['flashGUID']),
        'plugin' => 'tailscale',
        'version' => $version['VERSION']
    );

    $queryString = http_build_query($query);

    logmsg("Sending usage data: {$queryString}");

    $request = file_get_contents("{$endpoint}?{$queryString}");

    if( ! mb_strpos($http_response_header[0],'201'))
    {
        logmsg("Error occurred while transmitting usage data.");
    }
} else {
    logmsg("Usage collection disabled.");
}
