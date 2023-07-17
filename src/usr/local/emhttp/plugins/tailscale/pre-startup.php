#!/usr/bin/php -q
<?php

require_once "include/common.php";

$version = parse_ini_file('/etc/unraid-version');

// Log current settings
foreach ($tailscale_config as $key => $value) {
    logmsg("Setting: {$key}: {$value}");
}
if ($configure_extra_interfaces) {
    require "include/set-tailscale-interface.php";
}
if ($tailscale_config['SYSCTL_IP_FORWARD'] == "1") {
    logmsg("Enabling IP forwarding");
    $sysctl = "net.ipv4.ip_forward = 1" . PHP_EOL . "net.ipv6.conf.all.forwarding = 1";
    file_put_contents('/etc/sysctl.d/99-tailscale.conf', $sysctl);
    run_command("sysctl -p /etc/sysctl.d/99-tailscale.conf", true);
}
if ($version['version'] == "6.12.0") {
    logmsg("Unraid 6.12.0: Checking SSH startup script");
    $ssh = file_get_contents('/etc/rc.d/rc.sshd');

    if (str_contains($ssh, '$family')) {
        logmsg("Unraid 6.12.0: Repairing SSH startup script");
        $ssh = str_replace('$family', 'any', $ssh);
        file_put_contents('/etc/rc.d/rc.sshd', $ssh);
    }
}
