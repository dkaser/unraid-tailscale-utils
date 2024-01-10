<?php

function getTailscaleNetbiosWarning($var)
{
    if (($var['USE_NETBIOS'] == "yes") && ($var['shareSMBEnabled'] != "no")) {
        return "<span class='warn' style='text-align: center; font-size: 1.4em; font-weight: bold;'>" . _("NetBIOS is enabled in SMB settings - this can prevent shares from being accessed via Tailscale.") . "</span>";
    }
    return "";
}
