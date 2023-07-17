<?php

function run_command($command)
{
    $output = null;
    $retval = null;
    logmsg($command);
    exec("{$command} 2>&1", $output, $retval);
    if ($retval != 0) {
        logmsg("Command returned {$retval}" . PHP_EOL . implode(PHP_EOL, $output));
    }
}

switch ($tailscale_config['ACCEPT_ROUTES']) {
    case "0":
        run_command("/usr/local/sbin/tailscale set --accept-routes=false");
        break;
    case "1":
        run_command("/usr/local/sbin/tailscale set --accept-routes=true");
        break;
    default:
        logmsg("Ignoring accept-routes");
}

switch ($tailscale_config['ACCEPT_DNS']) {
    case "0":
        run_command("/usr/local/sbin/tailscale set --accept-dns=false");
        break;
    case "1":
        run_command("/usr/local/sbin/tailscale set --accept-dns=true");
        break;
    default:
        logmsg("Ignoring accept-dns");
}
