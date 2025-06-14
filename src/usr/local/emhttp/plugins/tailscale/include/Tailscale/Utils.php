<?php

namespace Tailscale;

class Utils
{
    /**
    * @param array<mixed> $args
    */
    public static function run_task(string $functionName, array $args = array()): void
    {
        try {
            $reflectionMethod = new \ReflectionMethod($functionName);
            $reflectionMethod->invokeArgs(null, $args);
        } catch (\Throwable $e) {
            Utils::logmsg("Caught exception in {$functionName} : " . $e->getMessage());
        }
    }

    public static function setPHPDebug(): void
    {
        $version = parse_ini_file('/var/local/emhttp/plugins/tailscale/tailscale.ini');

        if ( ! $version) {
            Utils::logmsg("Could not retrieve system data, skipping debug check.");
            return;
        }

        if ((($version['BRANCH'] ?? "") == "trunk") && ! defined("TAILSCALE_TRUNK")) {
            error_reporting(E_ALL);
            define("TAILSCALE_TRUNK", true);
        }
    }

    public static function printRow(string $title, string $value): string
    {
        return "<tr><td>{$title}</td><td>{$value}</td></tr>" . PHP_EOL;
    }

    public static function printDash(string $title, string $value): string
    {
        return "<tr><td><span class='w26'>{$title}</span>{$value}</td></tr>" . PHP_EOL;
    }

    public static function formatWarning(?Warning $warning): string
    {
        if ($warning == null) {
            return "";
        }

        return "<span class='{$warning->Priority}' style='text-align: center; font-size: 1.4em; font-weight: bold;'>" . $warning->Message . "</span>";
    }

    public static function make_option(bool $selected, string $value, string $text, string $extra = ""): string
    {
        return "<option value='{$value}'" . ($selected ? " selected" : "") . (strlen($extra) ? " {$extra}" : "") . ">{$text}</option>";
    }

    public static function auto_v(string $file): string
    {
        $docroot = $_SERVER['DOCUMENT_ROOT'] ?: '/usr/local/emhttp';

        if ( ! is_string($docroot)) {
            $docroot = '/usr/local/emhttp';
        }

        $path = $docroot . $file;
        clearstatcache(true, $path);
        $time    = file_exists($path) ? filemtime($path) : 'autov_fileDoesntExist';
        $newFile = "{$file}?v=" . $time;

        return $newFile;
    }

    /**
     * @return array<string>
     */
    public static function run_command(string $command, bool $alwaysShow = false, bool $show = true): array
    {
        $output = array();
        $retval = null;
        if ($show) {
            self::logmsg("exec: {$command}");
        }
        exec("{$command} 2>&1", $output, $retval);

        if (($retval != 0) || $alwaysShow) {
            self::logmsg("Command returned {$retval}" . PHP_EOL . implode(PHP_EOL, $output));
        }

        return $output;
    }

    public static function logmsg(string $message, bool $debug = false, bool $rateLimit = false): void
    {
        if ($rateLimit && (intval(date("i")) % 10 != 0)) {
            // Only log rate limited messages every 10 minutes
            return;
        }

        if ($debug) {
            if (defined("TAILSCALE_TRUNK")) {
                $message = "DEBUG: " . $message;
            } else {
                return;
            }
        }
        $timestamp = date('Y/m/d H:i:s');
        $filename  = is_string($_SERVER['PHP_SELF']) ? basename($_SERVER['PHP_SELF']) : "unknown";
        file_put_contents("/var/log/tailscale-utils.log", "{$timestamp} {$filename}: {$message}" . PHP_EOL, FILE_APPEND);
    }

    public static function ip4_in_network(string $ip, string $network): bool
    {
        if (strpos($network, '/') === false) {
            return false;
        }

        list($subnet, $mask) = explode('/', $network, 2);
        $ip_bin_string       = sprintf("%032b", ip2long($ip));
        $net_bin_string      = sprintf("%032b", ip2long($subnet));

        return (substr_compare($ip_bin_string, $net_bin_string, 0, intval($mask)) === 0);
    }

    public static function validateCidr(string $cidr): bool
    {
        $parts = explode('/', $cidr);
        if (count($parts) != 2) {
            return false;
        }

        $ip      = $parts[0];
        $netmask = $parts[1];

        if ( ! preg_match("/^\d+$/", $netmask)) {
            return false;
        }

        $netmask = intval($parts[1]);

        if ($netmask < 0) {
            return false;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $netmask <= 32;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $netmask <= 128;
        }

        return false;
    }

    /**
     * @return array<string>
     */
    public static function getExitRoutes(): array
    {
        return ["0.0.0.0/0", "::/0"];
    }
}
