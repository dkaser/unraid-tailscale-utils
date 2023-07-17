<?php

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
