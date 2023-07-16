<?php

if ($configure_extra_interfaces) {
    logmsg("Restarting Unraid services");
    exec($restart_command);
}
