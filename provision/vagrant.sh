#!/bin/bash
#
# Vagrant-specific provisioning script for Loadsys Cake 3 projects.
#
# This script is typically invoked from \`main.sh\` when APP_ENV=vagrant.
# It is intended to contain development-specific modifications to the
# server environment, such as installing a local MySQL server into the
# vagrant VM, adding the Xdebug PHP extension, and installing Mailcatcher.
# Will also load `provision/$APP_ENV.sql` into the local MySQL database.
#
# WARNING!
# Actions performed in this script will cause development VMs to deviate
# from production server instances! Only that which is **absolutely**
# necessary to be different should be included here. Everything else must
# go in main.sh so that it applies to all environments equally.


# Set up working vars.
MYSQL_ROOT_PASS="password"


# Install a local MySQL server.
echo "## Installing local MySQL server."

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password ${MYSQL_ROOT_PASS}"

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password ${MYSQL_ROOT_PASS}"

sudo apt-get install -y mysql-server

# You should probably run this yourself as it can't be automated.
#mysql_secure_installation


# Configure MySQL databases.
SQL_IMPORT_FILE="${PROVISION_DIR}/${APP_ENV}.sql"

if [ -r "${SQL_IMPORT_FILE}" ]; then
    echo "## Executing environment-specific MySQL script: \`${SQL_IMPORT_FILE}\`"

	mysql -h localhost -u root -p"'""${MYSQL_ROOT_PASS}""'" mysql < "${SQL_IMPORT_FILE}"
fi


# Install development-only PHP extensions.
echo "## Installing PHP development-only extensions."

sudo apt-get install -y libsqlite3-dev php5-sqlite php5-xdebug

sudo php5enmod sqlite3 xdebug


# Install Mailcatcher.
echo "## Installing Mailcatcher."

sudo apt-add-repository ppa:brightbox/ruby-ng -y

sudo apt-get update -y

sudo apt-get install -y ruby1.9.3

sudo gem install mailcatcher

sudo tee "/etc/init/mailcatcher.conf" <<-'EOINIT'
	description "Mailcatcher"
	start on runlevel [2345]
	stop on runlevel [!2345]
	respawn
	exec /usr/bin/env $(which mailcatcher) --foreground --http-ip=0.0.0.0

EOINIT

sudo service mailcatcher start

