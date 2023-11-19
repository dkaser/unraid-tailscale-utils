#!/usr/bin/php -q
<?php

$docroot = $docroot ?? $_SERVER['DOCUMENT_ROOT'] ?: '/usr/local/emhttp';
require_once "{$docroot}/plugins/tailscale/include/common.php";

$timer           = 15;
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
                $timer = 60;
            }
        }
    }

    if (sort($new_addresses) != $saved_addresses) {
        logmsg("Interface has changed, applying configuration");
        $saved_addresses = $new_addresses;

        foreach (glob("{$docroot}/plugins/tailscale/include/tailscale-watcher/*.php") as $file) {
            try {
                require $file;
            } catch (Exception $e) {
                logmsg("Caught exception in $file : " . $e->getMessage());
            }
        }
    }

    foreach (glob("{$docroot}/plugins/tailscale/include/tailscale-watcher/always/*.php") as $file) {
        try {
            require $file;
        } catch (Exception $e) {
            logmsg("Caught exception in $file : " . $e->getMessage());
        }
    }
}
