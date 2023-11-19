<?php

$ips = parse_ini_file("/boot/config/network.cfg");
if(array_key_exists(('IPADDR'), $ips)) {
    $route_table = run_command("ip route list table 52");

    foreach($ips['IPADDR'] as $ip) {
        foreach($route_table as $route) {
            $net = explode(' ', $route)[0];
            if(ip4_in_network($ip, $net)) {
                logmsg("Detected local IP $ip in Tailscale route $net, removing");
                run_command("ip route del '$net' dev tailscale1 table 52");
            }
        }
    }
}

