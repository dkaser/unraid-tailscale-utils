<?php

function logmsg($message, $priority = LOG_INFO)
{
    $timestamp = date('Y/m/d H:i:s');
    $filename  = basename($_SERVER['PHP_SELF']);
    file_put_contents("/var/log/tailscale-utils.log", "{$timestamp} {$filename}: {$message}" . PHP_EOL, FILE_APPEND);
}

function run_command($command, $alwaysShow = false)
{
    $output = null;
    $retval = null;
    logmsg($command);
    exec("{$command} 2>&1", $output, $retval);
    if (($retval != 0) || $alwaysShow) {
        logmsg("Command returned {$retval}" . PHP_EOL . implode(PHP_EOL, $output));
    }

    return $output;
}


function ip4_in_network($ip, $network)
{
    if ( strpos( $network, '/' ) === false ) {
        return false;
    }

    list( $subnet, $mask ) = explode( '/', $network, 2 );
    $ip_bin_string = sprintf("%032b", ip2long($ip));
    $net_bin_string = sprintf("%032b", ip2long($subnet));

    return (substr_compare($ip_bin_string, $net_bin_string, 0, $mask) === 0);
}

$plugin = "tailscale";
$ifname = 'tailscale1';

$config_file        = '/boot/config/plugins/tailscale/tailscale.cfg';
$defaults_file      = '/usr/local/emhttp/plugins/tailscale/settings.json';
$network_extra_file = '/boot/config/network-extra.cfg';
$restart_command    = '/usr/local/emhttp/webGui/scripts/reload_services';

// Load configuration file
if (file_exists($config_file)) {
    $tailscale_config = parse_ini_file($config_file);
} else {
    $tailscale_config = array();
}

// Load default settings and assign values
$settings_config = json_decode(file_get_contents($defaults_file), true);
foreach ($settings_config as $key => $value) {
    if ( ! isset($tailscale_config[$key])) {
        $tailscale_config[$key] = $value['default'];
    }
}

$configure_extra_interfaces = file_exists($restart_command);
