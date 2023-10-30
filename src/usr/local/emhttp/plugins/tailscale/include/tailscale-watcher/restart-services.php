<?php

logmsg("Restarting Unraid services");
exec($restart_command);
exec("/etc/rc.d/rc.nginx term");
exec("/etc/rc.d/rc.nginx start");

