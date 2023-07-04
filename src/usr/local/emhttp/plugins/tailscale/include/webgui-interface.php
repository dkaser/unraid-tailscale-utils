<?php

require_once "{$docroot}/plugins/tailscale/include/common.php";
require_once "{$docroot}/plugins/tailscale/include/tailscale-status.php";
require_once "{$docroot}/plugins/tailscale/include/webgui-info.php";
require_once "{$docroot}/plugins/tailscale/include/webgui-key-expiration.php";
require_once "{$docroot}/plugins/tailscale/include/tailscale-lock.php";

$tailscale_output = array();

$tailscale_status = getTailscaleStatus();
$tailscale_prefs  = getTailscalePrefs();
$tailscale_lock   = getTailscaleLock();

$tailscale_output['key_expiry_warning'] = getKeyExpirationWarning($tailscale_status);
$tailscale_output['status_info']        = getStatusInfo($tailscale_status, $tailscale_prefs, $tailscale_lock);
$tailscale_output['connection_info']    = getConnectionInfo($tailscale_status, $tailscale_prefs);

$tailscale_output['attach_file_tree'] = ($var['fsState'] == 'Started') ? "$('#taildropdir').fileTreeAttach();" : "";
$tailscale_output['background_color'] = strstr('white,azure', $display['theme']) ? '#f2f2f2' : '#1c1c1c';

$tailscale_output['lock_enabled'] = getTailscaleLockEnabled($tailscale_lock);
$tailscale_output['lock_signed']  = getTailscaleLockSigned($tailscale_lock);
$tailscale_output['lock_signing'] = getTailscaleLockSigning($tailscale_lock);
$tailscale_output['lock_pending'] = getTailscaleLockPending($tailscale_lock);
$tailscale_output['lock_pubkey']  = getTailscaleLockPubkey($tailscale_lock);
$tailscale_output['lock_nodekey'] = getTailscaleLockNodekey($tailscale_lock);
$tailscale_output['lock_warning'] = getTailscaleLockWarning($tailscale_lock);
