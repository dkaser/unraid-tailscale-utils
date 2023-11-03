<?php

$original = 'location ~ \.php$ {';
$replace  = <<<'END'
    location ~ ^(.+\.php)(.*)$ {
        fastcgi_split_path_info  ^(.+\.php)(.*)$;
        fastcgi_param PATH_INFO  $fastcgi_path_info;
    END;

$nginx = file_get_contents("/etc/rc.d/rc.nginx");

if (strpos($nginx, $original) !== false) {
    // Patch the rc.nginx file
    logmsg("Detected original rc.nginx, applying patch\n");

    if ( ! file_exists("/etc/rc.d/rc.nginx.pre-tailscale")) {
        copy("/etc/rc.d/rc.nginx", "/etc/rc.d/rc.nginx.pre-tailscale");
    }
    $newFile = str_replace($original, $replace, $nginx);
    file_put_contents("/etc/rc.d/rc.nginx", $newFile);
}

exec("/etc/rc.d/rc.nginx reload");
