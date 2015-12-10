#!/usr/bin/env bash

#---------------------------------------------------------------------
usage ()
{
	cat <<EOT

${0##*/}
    Primary provisioning script for Loadsys Cake 3 applications.

    (Starting to be) Designed to be used both by vagrant an on any
    Ubuntu 12.04 LTS (Precise) server. Provide the desired APP_ENV
    value as the first argument (defaults to "production"). That
    value will be written to \`/etc/app_env\` and used by both the
    current user's \`~/.profile\` and
    \`/etc/apache2/envvars\` + \`provision/010-cake.conf\`.

    Will execute \`provision/$APP_ENV.sql\` against the local MySQL
    database and call \`provision/$APP_ENV.sh\` if present.

Usage:
    provision/${0##*/} <APP_ENV_VALUE>

    Provide the value for the APP_ENV environment variable that you
    wish this server to use.

EOT

	exit 0
}
if [ "$1" = '-h' ]; then
	usage
fi

# Set up working vars.
if [ -z "$1" ]; then
	usage
fi

export APP_ENV=${1}

export TARGET_USER=$(whoami)

if [ -r "/etc/provision_path" ]; then
	export PROVISION_DIR="$( cat "/etc/provision_path" )"
else
	export PROVISION_DIR="$( cd -P "$( dirname "$0" )"/. >/dev/null 2>&1 && pwd )"
fi


echo "## Starting: `basename "$0"`."


# Prevent sometimes-troublesome packages from...causing trouble.
echo "## Holding packages that cause trouble..."

sudo apt-mark hold grub-common grub-pc grub-pc-bin grub2-common grub-legacy-ec2


# Install sub-dependencies first.
echo "## Installing dependencies."

export DEBIAN_FRONTEND=noninteractive

echo "UTC" | sudo tee /etc/timezone > /dev/null

sudo dpkg-reconfigure --frontend noninteractive tzdata

sudo apt-get update -y

sudo apt-get upgrade -y

sudo apt-get install -y \
 software-properties-common \
 python-software-properties \
 build-essential \
 curl \
 bzip2 \
 gzip \
 unzip \
 zip \
 mysql-client

sudo add-apt-repository -y ppa:ondrej/php5-5.6

sudo apt-add-repository -y ppa:git-core/ppa


# Install direct requirements.
echo "## Installing LAMP stack components."

sudo apt-get update -y

sudo apt-get install -y git-core apache2 php5 php5-curl php5-intl php5-mcrypt php5-memcached php5-mysql


# Install composer.
echo "## Installing composer."

curl -sS https://raw.githubusercontent.com/loadsys/CakePHP-Shell-Scripts/master/composer > composer && sudo bash composer


# Install Node.js, Grunt and Ember.
# Ref: https://nodesource.com/blog/nodejs-v012-iojs-and-the-nodesource-linux-repositories
# echo "## Installing node.js, Grunt and Ember."
#
# curl -sL https://deb.nodesource.com/setup_0.12 | sudo bash -
#
# sudo apt-get install -y nodejs
#
# sudo npm install -g json grunt-cli ember-cli


# Set up the machine's APP_ENV value.
echo "## Setting app environment."

sudo tee /etc/app_env <<-EOAPPENV > /dev/null

	export APP_ENV=${APP_ENV}

EOAPPENV

sudo chmod a+r /etc/app_env

echo ". /etc/app_env" >> "/home/${TARGET_USER}/.profile"

sudo cp -rv ${PROVISION_DIR}/dot-files/.[a-zA-Z0-9]* "/home/${TARGET_USER}/"

sudo chown -Rv ${TARGET_USER} /home/${TARGET_USER}/.[a-zA-Z0-9]*

sudo cp -rv ${PROVISION_DIR}/dot-files/.[a-zA-Z0-9]* /root/


# Automatically switch to the webroot when logging in.
echo "cd /var/www" >> "/home/${TARGET_USER}/.profile"


# Set up Apache config.
echo "## Setting up Apache virtual host."

sudo cp /etc/apache2/envvars /etc/apache2/envvars.bak

sudo tee -a /etc/apache2/envvars <<-EOENV > /dev/null

	## Load this machine's APP_ENV value.
	. /etc/app_env

EOENV

sudo cp "${PROVISION_DIR}/010-cake.conf" /etc/apache2/sites-available/

sudo a2ensite 010-cake

sudo a2dissite 000-default

sudo a2enmod env rewrite

sudo service apache2 restart


# Set up DB backups on reboot/shutdown.
SHUTDOWN_DB_BACKUP_SCRIPT_NAME="K99_shutdown_db_backup"
SHUTDOWN_DB_BACKUP_SCRIPT="/etc/init.d/${SHUTDOWN_DB_BACKUP_SCRIPT_NAME}"
sudo tee "${SHUTDOWN_DB_BACKUP_SCRIPT}"  <<-EOSHL > /dev/null
	#!/usr/bin/env bash
	. /etc/app_env
	cd "/var/www"
	bin/db-backup

EOSHL

sudo chmod a+x "${SHUTDOWN_DB_BACKUP_SCRIPT}"

sudo ln -s "${SHUTDOWN_DB_BACKUP_SCRIPT}" "/etc/rc0.d/${SHUTDOWN_DB_BACKUP_SCRIPT_NAME}"

sudo ln -s "${SHUTDOWN_DB_BACKUP_SCRIPT}" "/etc/rc6.d/${SHUTDOWN_DB_BACKUP_SCRIPT_NAME}"


# Call the environment-specific provisioning script, if it exists.
ENV_SPECIFIC_SCRIPT="${PROVISION_DIR}/${APP_ENV}.sh"

if [ -x "${ENV_SPECIFIC_SCRIPT}" ]; then
    echo "## Calling environment-specific provisioning script: \`${ENV_SPECIFIC_SCRIPT}\`"

	"${ENV_SPECIFIC_SCRIPT}"
else
    echo "## Environment-specific provisioning script not found. Skipping: \`${ENV_SPECIFIC_SCRIPT}\`"
fi


# Finish up.
echo "## Done: `basename "$0"`"
