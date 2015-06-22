#!/bin/bash
# Primary provisioning script for Loadsys Cake 3 applications.
# (Starting to be) Designed to be used both by vagrant an on any Ubuntu
# 12.04 LTS (Precise) server. Provide the desired APP_ENV value as the
# first argument (defaults to "production"). That value will be written
# to `/etc/app_env` and used by both the current user's `~/.profile`
# and `/etc/apache2/envvars` + `provision/010-cake.conf`.
#
# Will load `provision/$APP_ENV.sql` into the local MySQL database.


# Set up working vars.
APP_ENV=${1-production}

if [ -r "/etc/provision_path" ]; then
	PROVISION_DIR="$( cat "/etc/provision_path" )"
else
	PROVISION_DIR="$( cd -P "$( dirname "$0" )"/. >/dev/null 2>&1 && pwd )"
fi


# Install sub-dependencies first.
echo "## Installing dependencies."

sudo apt-get update -y

sudo apt-get install -y \
 software-properties-common \
 python-software-properties \
 build-essential \
 libsqlite3-dev \
 curl \
 zip \
 git-core \
 memcached

sudo apt-add-repository ppa:brightbox/ruby-ng -y

sudo add-apt-repository -y ppa:ondrej/php5-5.6


# Install direct requirements.
echo "## Installing LAMP stack components."

sudo apt-get update -y

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password password'

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password password'

sudo apt-get install -y mysql-server apache2 php5 php5-curl php5-intl php5-mcrypt php5-mysql ruby1.9.3


# Install Mailcatcher.
# echo "## Installing Mailcatcher."
#
# sudo gem install mailcatcher
#
# sudo tee /etc/init/mailcatcher.conf <<-'EOINIT'
# 	description "Mailcatcher"
# 	start on runlevel [2345]
# 	stop on runlevel [!2345]
# 	respawn
# 	exec /usr/bin/env $(which mailcatcher) --foreground --http-ip=0.0.0.0
#
# EOINIT
#
# sudo service mailcatcher start


# Set up the machine's APP_ENV value.
echo "## Setting app environment."

sudo tee /etc/app_env <<-EOENV
	APP_ENV=${APP_ENV}
EOENV

sudo chmod a+r /etc/app_env

echo ". /etc/app_env" >> /home/vagrant/.profile


# Set up Apache config.
echo "## Setting up Apache virtual host."

sudo cp /etc/apache2/envvars /etc/apache2/envvars.bak

sudo tee -a /etc/apache2/envvars <<-EOENV

	## Load this machine's APP_ENV value.
	. /etc/app_env
EOENV

sudo cp "${PROVISION_DIR}/010-cake.conf" /etc/apache2/sites-available/

sudo a2ensite 010-cake

sudo a2dissite 000-default

sudo a2enmod env rewrite

sudo service apache2 restart


# Configure MySQL databases.
echo "## Setting up MySQL databases, users and passwords."

SQL_IMPORT_FILE="${PROVISION_DIR}/${APP_ENV}.sql"
if [ -r "${SQL_IMPORT_FILE}" ]; then
	mysql -h localhost -u root -p'password' mysql < "${SQL_IMPORT_FILE}"
fi

# Call the environment-specific provisioning script, if it exists.
ENV_SPECIFIC_SCRIPT="${PROVISION_DIR}/${APP_ENV}.sh"
if [ -x "${ENV_SPECIFIC_SCRIPT}" ]; then
	"${ENV_SPECIFIC_SCRIPT}"
fi

# @TODO: Move mysql-server install and .sql file import to vagrant.sh
# @TODO: Add mysql-client install (for cakephp-shell-scripts like db-backup and db-login
# @TODO: Add php5-xdebug to vagrant.sh
