<?php

if ($tailscale_config['SYSCTL_IP_FORWARD'] == "1") {
    logmsg("Enabling IP forwarding");
    $sysctl = "net.ipv4.ip_forward = 1" . PHP_EOL . "net.ipv6.conf.all.forwarding = 1";
    file_put_contents('/etc/sysctl.d/99-tailscale.conf', $sysctl);
    run_command("sysctl -p /etc/sysctl.d/99-tailscale.conf", true);
}
