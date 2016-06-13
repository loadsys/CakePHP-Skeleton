#!/bin/bash
#
# Shared script for "bare metal" environments like staging and production.
# Expects a number of ENV vars to be exported before being called,
# typically from either production.sh or staging.sh. Should only ever be
# invoked by staging.sh or production.sh indirectly.


# Set up working vars.
#   PROVISION_DIR must be inherited from caller.
#   APP_ENV must be inherited from caller.
#   TARGET_USER must be inherited from caller.
#   FQDN must be inherited from caller.


echo "## Starting: `basename "$0"`."


# Set machine hostname.
# echo "## Setting hostname."
sudo cp /etc/hostname /etc/hostname.bak
echo "${FQDN}" | sudo tee /etc/hostname > /dev/null


# Configure system patches.
sudo add-apt-repository "deb http://us.archive.ubuntu.com/ubuntu/ `lsb_release -sc` universe multiverse"

sudo add-apt-repository "deb http://us.archive.ubuntu.com/ubuntu/ `lsb_release -sc`-updates universe multiverse"


# Install additional production-only utilities.
sudo apt-get install -y \
 emacs23-nox \
 screen \
 wget \
 ca-certificates \
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

sudo tee /etc/logrotate.d/cake-apps <<EOF > /dev/null
/var/www/logs/*.log {
   su $TARGET_USER www-data
   create 0664 $TARGET_USER www-data
   rotate 12
   weekly
   missingok
   notifempty
   compress
   delaycompress
}

EOF


# Set up user permissions.
sudo usermod -a -G www-data ${TARGET_USER}

sudo chown -R ${TARGET_USER}:www-data /var/www

sudo chmod -R a+w /var/www/tmp /var/www/logs


# Initialize git LFS support in the repo:
cd /var/www
git lfs install


# Finish up.
echo "## Done: `basename "$0"`"
