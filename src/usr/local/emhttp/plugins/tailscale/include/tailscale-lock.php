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
