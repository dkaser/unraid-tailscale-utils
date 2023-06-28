<?php

function getTailscaleStatus()
{
    exec("tailscale status --json", $out_status);
    return json_decode(implode($out_status));
}

function getTailscalePrefs()
{
    exec("tailscale debug prefs", $out_prefs);
    return json_decode(implode($out_prefs));
}

function getTailscaleLock()
{
    exec("tailscale lock status -json=true", $out_status);
    return json_decode(implode($out_status));
}
