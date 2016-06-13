#!/bin/bash
#
# Production-specific provisioning script for Loadsys Cake 3 projects.
#
# This script is typically invoked from \`main.sh\` when APP_ENV=production.
# It is intended to contain production-specific modifications to the
# server environment, such as installing cron jobs and DB backups on
# shutdown/restart.
#
# WARNING!
# Actions performed in this script will cause production VMs to deviate
# from all other environments! Only that which is **absolutely**
# necessary to be different should be included here. Everything else must
# go in main.sh so that it applies to all environments equally.


# Set up working vars.
#   PROVISION_DIR must be inherited from caller.
#   APP_ENV must be inherited from caller.
#   TARGET_USER must be inherited from caller.

export FQDN="@TODO:hostname.domain.com"
NOTIFY_EMAIL="@TODO:serveradmin@domain.com"
SMTP_RELAY_HOST_AND_PORT="@TODO:ses.hostname.here:587"
SMTP_RELAY_USERNAME="@TODO:ses.username-here"
SMTP_RELAY_PASSWORD="@TODO:ses.password-here"



echo "## Starting: `basename "$0"`."


# Farm most everything out to the common "bare metal" script.
"${PROVISION_DIR}/baremetal.sh"



# Install CRON jobs as the current user.
# echo "## Installing cron jobs."
#
# crontab -l | {
#     cat;  # Preserve existing tab.
#     echo "#MAILTO="${NOTIFY_EMAIL}"";
#     echo "15 9 * * *      /usr/bin/env /var/www/bin/cake shell_name";
# } | crontab -



# Configure SSL (Apache or AWS load balancer)
# /etc/apache2/mods-available/ssl.conf & /etc/apache2/sites-available/default-ssl.conf

# The following entries must be in the default SSL vhost:
# SSLCipherSuite EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH
# SSLProtocol All -SSLv2 -SSLv3
# SSLHonorCipherOrder On
# #SSLSessionTickets Off
# SSLCompression Off
# SSLUseStapling On
# SSLStaplingCache "shmcb:logs/stapling-cache(150000)"


# Configure the email sub-system.

DEBIAN_FRONTEND=noninteractive sudo apt-get install -y postfix  # Choose "no configuration" from the menu if it appears.

sudo tee /etc/mailname <<EOF > /dev/null
${FQDN}

EOF


sudo tee /etc/postfix/main.cf <<EOF > /dev/null
relayhost = ${SMTP_RELAY_HOST_AND_PORT}
smtp_sasl_auth_enable = yes
smtp_sasl_security_options = noanonymous
smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
smtp_use_tls = yes
smtp_tls_security_level = encrypt
smtp_tls_note_starttls_offer = yes
smtp_tls_CAfile = /etc/ssl/certs/ca-certificates.crt

EOF


sudo tee /etc/postfix/sasl_passwd <<EOF > /dev/null
${SMTP_RELAY_HOST_AND_PORT} ${SMTP_RELAY_USERNAME}:${SMTP_RELAY_PASSWORD}

EOF

sudo postmap /etc/postfix/sasl_passwd

sudo cp /etc/aliases /etc/alises.bak

sudo tee /etc/aliases <<EOF > /dev/null
# See man 5 aliases for format
#
# Forward local accounts to root's mailbox.
postmaster: root
${TARGET_USER}: root
www-data: root
# Forward root's mail out to monitoring mailbox.
root: ${NOTIFY_EMAIL}

EOF

sudo postalias /etc/aliases



# Finish up.
echo "## Done: `basename "$0"`"
