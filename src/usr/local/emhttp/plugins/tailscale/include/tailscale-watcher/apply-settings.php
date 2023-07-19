<?php

function apply_flag($setting, $flag)
{
    global $tailscale_config;

    switch ($tailscale_config[$setting]) {
        case "0":
            run_command("/usr/local/sbin/tailscale set {$flag}=false");
            break;
        case "1":
            run_command("/usr/local/sbin/tailscale set {$flag}=true");
            break;
        default:
            logmsg("Ignoring {$flag}");
    }
}

apply_flag('ACCEPT_ROUTES', '--accept-routes');
apply_flag('ACCEPT_DNS', '--accept-dns');
apply_flag('SSH', '--ssh');
