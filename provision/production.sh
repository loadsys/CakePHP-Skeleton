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
#   PROVISION_DIR must be inherited from main.sh
#   APP_ENV must be inherited from main.sh
THIS_DIR="$( cd -P "$( dirname "$0" )"/. >/dev/null 2>&1 && pwd )"

FQDN="@TODO:hostname.domain.com"
NOTIFY_EMAIL="@TODO:serveradmin@domain.com"
SMTP_RELAY_HOST_AND_PORT="@TODO:ses.hostname.here:587"
SMTP_RELAY_PASSWORD="@TODO:ses-password-here"

echo "!! Nothing below has been tested yet!"
exit 0

echo "## Starting: `basename "$0"`."


# Set machine hostname.
# echo "## Setting hostname."
sudo cp /etc/hostname /etc/hostname.bak
echo "${FQDN}" | sudo tee /etc/hostname > /dev/null

# Install CRON jobs as the current user.
# echo "## Installing cron jobs."
#
# crontab -l | {
#     cat;  # Preserve existing tab.
#     echo "#MAILTO="${NOTIFY_EMAIL}"";
#     echo "15 9 * * *      /usr/bin/env /var/www/bin/cake shell_name";
# } | crontab -


# Configure system patches.
sudo add-apt-repository "deb http://us.archive.ubuntu.com/ubuntu/ `lsb_release -sc` universe multiverse"

sudo add-apt-repository "deb http://us.archive.ubuntu.com/ubuntu/ `lsb_release -sc`-updates universe multiverse"


# Install additional production-only utilities.
sudo apt-get install -y \
 emacs23-nox \
 screen \
 wget \
 ca-certificates \
#  gcc \
#  make \
#  autoconf \
#  automake \
#  libtool \
 rsync \
 mailutils \
 unattended-upgrades


# Set up automatic system updates.
# Ref: https://help.ubuntu.com/lts/serverguide/automatic-updates.html
sudo cp /etc/apt/apt.conf.d/50unattended-upgrades /etc/apt/apt.conf.d/50unattended-upgrades.bak

sudo tee /etc/apt/apt.conf.d/50unattended-upgrades <<-'EOF' > /dev/null
    Unattended-Upgrade::Allowed-Origins {
        "${distro_id}:${distro_codename}-security";
    //	"${distro_id}:${distro_codename}-updates";
    //	"${distro_id}:${distro_codename}-proposed";
        "${distro_id}:${distro_codename}-backports";
    };
    Unattended-Upgrade::Package-Blacklist {
        "apache2";
        "php5";
        "php5-common";
        "php-cil";
    //	"libc6";
    //	"libc6-dev";
    //	"libc6-i686";
    };
    //Unattended-Upgrade::AutoFixInterruptedDpkg "false";
    //Unattended-Upgrade::MinimalSteps "true";
    //Unattended-Upgrade::InstallOnShutdown "true";
    Unattended-Upgrade::Mail "root@localhost";
    Unattended-Upgrade::MailOnlyOnError "true";
    Unattended-Upgrade::Remove-Unused-Dependencies "true";
    Unattended-Upgrade::Automatic-Reboot "false";
    //Unattended-Upgrade::Automatic-Reboot-Time "02:00";
    Acquire::http::Dl-Limit "150";

EOF


# Set up Cake log file rotation.
# Ref: http://ad7six.com/blog/2014/10/25/logrotate-rotate-your-log-files/

sudo tee /etc/logrotate.d/cake-apps <<'EOF' > /dev/null
/var/www/logs/*.log {
   su ubuntu www-data
   create 0664 ubuntu www-data
   rotate 12
   weekly
   missingok
   notifempty
   compress
   delaycompress
}

EOF


# Set up user permissions.
sudo usermod -a -G www-data ubuntu

sudo rm -r /var/www/*

sudo chown -R ubuntu:www-data /var/www

sudo chmod -R a+w /var/www/tmp /var/www/logs


# Configure the email sub-system.

DEBIAN_FRONTEND=noninteractive sudo apt-get install -y postfix  # Choose "no configuration" from the menu if it appears.

tee /etc/mailname <<EOF > /dev/null
${FQDN}

EOF


tee /etc/postfix/main.cf <<EOF > /dev/null
relayhost = ${SMTP_RELAY_HOST_AND_PORT}
smtp_sasl_auth_enable = yes
smtp_sasl_security_options = noanonymous
smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
smtp_use_tls = yes
smtp_tls_security_level = encrypt
smtp_tls_note_starttls_offer = yes
smtp_tls_CAfile = /etc/ssl/certs/ca-certificates.crt

EOF


tee /etc/postfix/sasl_passwd <<'EOF' > /dev/null
${SMTP_RELAY_HOST_AND_PORT} ${SMTP_RELAY_PASSWORD}

EOF


sudo cp /etc/aliases /etc/alises.bak

tee /etc/aliases <<EOF > /dev/null
# See man 5 aliases for format
#
# Forward local accounts to root's mailbox.
postmaster: root
ubuntu: root
www-data: root
# Forward root's mail out to monitoring mailbox.
root: ${NOTIFY_EMAIL}

EOF

sudo postalias /etc/aliases




# Finish up.
echo "## Done: `basename "$0"`"
