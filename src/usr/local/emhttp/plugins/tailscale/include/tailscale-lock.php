<?php

function getTailscaleLockEnabled($lock)
{
    return $lock->Enabled;
}

function getTailscaleLockSigned($lock)
{
    if ( ! getTailscaleLockEnabled($lock)) {
        return false;
    }

    return $lock->NodeKeySigned;
}

function getTailscaleLockNodekey($lock)
{
    if ( ! getTailscaleLockEnabled($lock)) {
        return false;
    }

    return $lock->NodeKey;
}

function getTailscaleLockPubkey($lock)
{
    if ( ! getTailscaleLockEnabled($lock)) {
        return false;
    }

    return $lock->PublicKey;
}

function getTailscaleLockSigning($lock)
{
    if ( ! getTailscaleLockSigned($lock)) {
        return false;
    }

    $isTrusted = false;
    $myKey     = getTailscaleLockPubkey($lock);

    foreach ($lock->TrustedKeys as $item) {
        if ($item->Key == $myKey) {
            $isTrusted = true;
        }
    }

    return $isTrusted;
}

function getTailscaleLockPending($lock)
{
    if ( ! getTailscaleLockSigning($lock)) {
        return array();
    }

    $pending = array();

    foreach ($lock->FilteredPeers as $item) {
        $pending[$item->Name] = $item->NodeKey;
    }

    return $pending;
}

function getTailscaleLockWarning($lock)
{
    if (getTailscaleLockEnabled($lock) && ( ! getTailscaleLockSigned($lock))) {
        return "<span class='error' style='text-align: center; font-size: 1.4em; font-weight: bold;'>The tailnet has lock enabled, but this node has not been signed. It will not be able to communicate with the tailnet.</span>";
    }
    return "";
}
