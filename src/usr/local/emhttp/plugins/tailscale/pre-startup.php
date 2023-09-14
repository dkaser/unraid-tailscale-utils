#!/usr/bin/php -q
<?php

$docroot = $docroot ?? $_SERVER['DOCUMENT_ROOT'] ?: '/usr/local/emhttp';
require_once "{$docroot}/plugins/tailscale/include/common.php";

// Log current settings
foreach ($tailscale_config as $key => $value) {
    logmsg("Setting: {$key}: {$value}");
}

foreach (glob("{$docroot}/plugins/tailscale/include/pre-startup/*.php") as $file) {
    logmsg("Executing {$file}");
    require_once $file;
}
