#!/usr/bin/php -q
<?php

require_once "include/common.php";

$timer           = 60;
$saved_addresses = array();

logmsg("Starting tailscale-watcher");

while (true) {
    sleep($timer);

    $interfaces    = net_get_interfaces();
    $new_addresses = array();

    if (isset($interfaces["tailscale1"]["unicast"])) {
        foreach ($interfaces["tailscale1"]["unicast"] as $interface) {
            if (isset($interface["address"])) {
                $new_addresses[] = $interface["address"];
            }
        }
    }

    if (sort($new_addresses) != $saved_addresses) {
        logmsg("Interface has changed, applying configuration");
        $saved_addresses = $new_addresses;

        if ($configure_extra_interfaces) {
            logmsg("Restarting Unraid services");
            exec($restart_command);
        }

        $command = "Ignoring accept-routes";
        switch ($settings_config['ACCEPT_ROUTES']) {
            case 0:
                $command = "/usr/local/sbin/tailscale set --accept-routes=false";
                exec($command);
                break;
            case 1:
                $command = "/usr/local/sbin/tailscale set --accept-routes=true";
                exec($command);
                break;
        }
        logmsg($command);

        $command = "Ignoring accept-dns";
        switch ($settings_config['ACCEPT_DNS']) {
            case 0:
                $command = "/usr/local/sbin/tailscale set --accept-dns=false";
                exec($command);
                break;
            case 1:
                $command = "/usr/local/sbin/tailscale set --accept-dns=true";
                exec($command);
                break;
        }
        logmsg($command);
    }
}
