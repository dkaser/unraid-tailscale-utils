#!/usr/bin/php -q
<?php

$docroot = $docroot ?? $_SERVER['DOCUMENT_ROOT'] ?: '/usr/local/emhttp';

require_once "{$docroot}/plugins/tailscale/include/common.php";

foreach (glob("{$docroot}/plugins/tailscale/include/daily/*.php") as $file) {
    require_once $file;
}