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

# @TODO: Make this dynamic?
TARGET_USER=vagrant

if [ -r "/etc/provision_path" ]; then
	export PROVISION_DIR="$( cat "/etc/provision_path" )"
else
	export PROVISION_DIR="$( cd -P "$( dirname "$0" )"/. >/dev/null 2>&1 && pwd )"
fi


# Install sub-dependencies first.
echo "## Installing dependencies."

echo "UTC" | sudo tee /etc/timezone

sudo dpkg-reconfigure --frontend noninteractive tzdata

sudo apt-get update -y

sudo apt-get upgrade -y

sudo apt-get install -y \
 software-properties-common \
 python-software-properties \
 build-essential \
 curl \
 zip \
 #memcached \  # This would install memcached on the production web instance, which probably not what you want, so you must manually enable this.
 mysql-client

sudo add-apt-repository -y ppa:ondrej/php5-5.6

sudo apt-add-repository -y ppa:git-core/ppa


# Install direct requirements.
echo "## Installing LAMP stack components."

sudo apt-get update -y

sudo apt-get install -y git-core apache2 php5 php5-curl php5-intl php5-mcrypt php5-mysql


# Set up the machine's APP_ENV value.
echo "## Setting app environment."

sudo tee /etc/app_env <<-EOAPPENV > /dev/null

	export APP_ENV=${APP_ENV}

EOAPPENV

sudo chmod a+r /etc/app_env

echo ". /etc/app_env" >> "/home/${TARGET_USER}/.profile"

sudo cp -r ${PROVISION_DIR}/dot-files/.[a-zA-Z0-9]* "/home/${TARGET_USER}/" && \
 sudo chown -R ${TARGET_USER} /home/${TARGET_USER}/.[a-zA-Z0-9]* && \
 sudo cp -r ${PROVISION_DIR}/dot-files/.[a-zA-Z0-9]* /root/


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


# Call the environment-specific provisioning script, if it exists.
ENV_SPECIFIC_SCRIPT="${PROVISION_DIR}/${APP_ENV}.sh"

if [ -x "${ENV_SPECIFIC_SCRIPT}" ]; then
    echo "## Calling environment-specific provisioning script: \`${ENV_SPECIFIC_SCRIPT}\`"

	"${ENV_SPECIFIC_SCRIPT}"
else
    echo "## Environment-specific provisioning script not found. Skipping: \`${ENV_SPECIFIC_SCRIPT}\`"
fi


# Install Node.js.
# echo "## Installing node.js."
#
# sudo apt-add-repository -y ppa:chris-lea/node.js
#
# sudo apt-get update -y
#
# sudo apt-get install -y nodejs


# Finish up.
echo "## Done: `basename "$0"`"
