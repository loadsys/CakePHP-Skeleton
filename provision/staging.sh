#!/bin/bash
#
# Staging-specific provisioning script for Loadsys Cake 3 projects.
#
# This script is typically invoked from \`main.sh\` when APP_ENV=staging.
# It is intended to contain staging-specific modifications to the
# server environment, such as installing cron jobs and DB backups on
# shutdown/restart.
#
# WARNING!
# Actions performed in this script will cause staging VMs to deviate
# from all other environments! Only that which is **absolutely**
# necessary to be different should be included here. Everything else must
# go in main.sh so that it applies to all environments equally.


# Set up working vars.
#   PROVISION_DIR must be inherited from main.sh
#   APP_ENV must be inherited from main.sh

export FQDN="@TODO: staging.domain-name.com"


echo "## Starting: `basename "$0"`."


# Farm most everything out to:
"${PROVISION_DIR}/baremetal.sh"


# Farm out local MySQL server install to the common "mysql_server" script.
"${PROVISION_DIR}/mysql_server.sh"


# Set strict limits on Apache in staging to prevent running out of memory on EC2 t2.micros.
echo "## Setting strict Apache limits."

sudo cp /etc/apache2/mods-enabled/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf.bak

sudo tee /etc/apache2/mods-enabled/mpm_prefork.conf <<-EOMPM > /dev/null

	# prefork MPM
	# StartServers: number of server processes to start
	# MinSpareServers: minimum number of server processes which are kept spare
	# MaxSpareServers: maximum number of server processes which are kept spare
	# MaxRequestWorkers: maximum number of server processes allowed to start
	# MaxConnectionsPerChild: maximum number of requests a server process serves

	<IfModule mpm_prefork_module>
			StartServers            2
			MinSpareServers         2
			MaxSpareServers         4
			MaxRequestWorkers       25
			MaxConnectionsPerChild  500
	</IfModule>

	# vim: syntax=apache ts=4 sw=4 sts=4 sr noet

EOMPM


# Install staging-only extensions.
echo "## Installing staging-only extensions."

sudo apt-get install -y memcached


# Install Mailcatcher.
"${PROVISION_DIR}/mailcatcher.sh"


# Install a CRON job to restart Mailcatcher daily.
# (Ref: https://github.com/sj26/mailcatcher/issues/210)
echo "## Installing cron job to restart Mailcatcher daily."

sudo crontab -l -u root | {
    cat;  # Preserve existing tab.
    echo "#MAILTO="${NOTIFY_EMAIL}"";
    echo "0 5 * * *      /usr/bin/env sudo service mailcatcher restart > /var/log/mailcatcher_restart.log";
} | sudo crontab -u root -


# Protect Mailcatcher behind Apache basic auth.
# -Install and enable mod_proxy
#
# -/etc/init/mailcatcher.conf :
# sed this line:
#     exec /usr/bin/env $(which mailcatcher) --foreground --http-ip=0.0.0.0
# into this line:
#     exec /usr/bin/env $(which mailcatcher) --foreground --http-ip=0.0.0.0 --ip=127.0.0.1 --http-port 1079
#
# -/etc/apache2/sites-available/020-mailcatcher-proxy :
# <VirtualHost *:1080>
#      ServerName mailcatcher.local
#      ProxyPass / http://127.0.0.1:1079/
#      ProxyPassReverse / http://127.0.0.1:1079/
# </VirtualHost>
#
# -add http basic auth
#
# -sudo a2ensite 020-mailcatcher-proxy


# Finish up.
echo "## Done: `basename "$0"`"
