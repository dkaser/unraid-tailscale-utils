<?php

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