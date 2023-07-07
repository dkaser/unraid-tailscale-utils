#!/usr/bin/php -q
<?php

require_once "include/common.php";

$timer           = 60;
$saved_addresses = array();

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
        logmsg("Interface has changed, restarting Unraid services");
        $saved_addresses = $new_addresses;

        if ($configure_extra_interfaces) {
            logmsg("Restarting Unraid services");
            exec($restart_command);
        }
    }
}
