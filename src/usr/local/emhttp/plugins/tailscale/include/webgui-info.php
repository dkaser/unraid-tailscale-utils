<?php

function printRow($title, $value)
{
    return "<tr><td>{$title}</td><td>{$value}</td></tr>" . PHP_EOL;
}

function printDash($title, $value)
{
    return "<tr><td><span class='w26'>{$title}</span>{$value}</td></tr>" . PHP_EOL;
}

function getStatusInfo($status, $prefs, $lock)
{
    $tsVersion     = isset($status->Version) ? $status->Version : _("Unknown");
    $keyExpiration = isset($status->Self->KeyExpiry) ? $status->Self->KeyExpiry : _("Disabled");
    $online        = isset($status->Self->Online) ? ($status->Self->Online ? _("Yes") : _("No")) : _("Unknown");
    $inNetMap      = isset($status->Self->InNetworkMap) ? ($status->Self->InNetworkMap ? _("Yes") : _("No")) : _("Unknown");
    $tags          = isset($status->Self->Tags) ? implode("<br />", $status->Self->Tags) : "";
    $loggedIn      = isset($prefs->LoggedOut) ? ($prefs->LoggedOut ? _("No") : _("Yes")) : _("Unknown");
    $tsHealth      = isset($status->Health) ? implode("<br />", $status->Health) : "";
    $lockEnabled   = getTailscaleLockEnabled($lock) ? _("Yes") : _("No");

    $output = "";
    $output .= printRow(_("Tailscale Version"), $tsVersion);
    $output .= printRow(_("Tailscale Health"), $tsHealth);
    $output .= printRow(_("Logged In"), $loggedIn);
    $output .= printRow(_("In Network Map"), $inNetMap);
    $output .= printRow(_("Online"), $online);
    $output .= printRow(_("Key Expiration"), $keyExpiration);
    $output .= printRow(_("Tags"), $tags);
    $output .= printRow(_("Tailscale Lock: Enabled"), $lockEnabled);

    if (getTailscaleLockEnabled($lock)) {
        $lockSigned  = getTailscaleLockSigned($lock) ? _("Yes") : _("No");
        $lockSigning = getTailscaleLockSigning($lock) ? _("Yes") : _("No");
        $pubKey      = getTailscaleLockPubkey($lock);
        $nodeKey     = getTailscaleLockNodekey($lock);

        $output .= printRow(_("Tailscale Lock: Node Key Signed"), $lockSigned);
        $output .= printRow(_("Tailscale Lock: Is Signing Node"), $lockSigning);
        $output .= printRow(_("Tailscale Lock: Node Key"), $nodeKey);
        $output .= printRow(_("Tailscale Lock: Public Key"), $pubKey);
    }

    return $output;
}

function getConnectionInfo($status, $prefs)
{
    $hostName         = isset($status->Self->HostName) ? $status->Self->HostName : _("Unknown");
    $dnsName          = isset($status->Self->DNSName) ? $status->Self->DNSName : _("Unknown");
    $tailscaleIPs     = isset($status->TailscaleIPs) ? implode("<br />", $status->TailscaleIPs) : _("Unknown");
    $magicDNSSuffix   = isset($status->MagicDNSSuffix) ? $status->MagicDNSSuffix : _("Unknown");
    $advertisedRoutes = isset($prefs->AdvertiseRoutes) ? implode("<br />", $prefs->AdvertiseRoutes) : _("None");
    $acceptRoutes     = isset($prefs->RouteAll) ? ($prefs->RouteAll ? _("Yes") : _("No")) : _("Unknown");
    $acceptDNS        = isset($prefs->CorpDNS) ? ($prefs->CorpDNS ? _("Yes") : _("No")) : _("Unknown");

    $output = "";
    $output .= printRow(_("Hostname"), $hostName);
    $output .= printRow(_("DNS Name"), $dnsName);
    $output .= printRow(_("Tailscale IPs"), $tailscaleIPs);
    $output .= printRow(_("MagicDNS Suffix"), $magicDNSSuffix);
    $output .= printRow(_("Advertised Routes"), $advertisedRoutes);
    $output .= printRow(_("Accept Routes"), $acceptRoutes);
    $output .= printRow(_("Accept DNS"), $acceptDNS);

    return $output;
}

function getDashboardInfo($status)
{
    $hostName     = isset($status->Self->HostName) ? $status->Self->HostName : _("Unknown");
    $dnsName      = isset($status->Self->DNSName) ? $status->Self->DNSName : _("Unknown");
    $tailscaleIPs = isset($status->TailscaleIPs) ? implode("<br /><span class='w26'>&nbsp;</span>", $status->TailscaleIPs) : _("Unknown");
    $online       = isset($status->Self->Online) ? ($status->Self->Online ? _("Yes") : _("No")) : _("Unknown");

    $output = "";
    $output .= printDash(_("Online"), $online);
    $output .= printDash(_("Hostname"), $hostName);
    $output .= printDash(_("DNS Name"), $dnsName);
    $output .= printDash(_("Tailscale IPs"), $tailscaleIPs);

    return $output;
}
