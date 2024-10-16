<?php

if ( ! isset($tailscale_config)) {
    echo('Tailscale config not defined.');
    return;
}
if ( ! isset($restart_command)) {
    echo('Restart command not defined.');
    return;
}

if ($tailscale_config["INCLUDE_INTERFACE"] == 1) {
    TailscaleHelpers::refreshWebGuiCert(false);

    TailscaleHelpers::logmsg("Restarting Unraid services");
    TailscaleHelpers::run_command($restart_command);

    // Wait to allow services to restart before continuing
    sleep(15);
}
